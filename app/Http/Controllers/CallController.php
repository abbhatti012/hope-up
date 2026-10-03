<?php

namespace App\Http\Controllers;

use App\Models\CallHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;

class CallController extends BaseController
{
    public function initiateCall(Request $request)
    {
        return $this->handleErrors(function() use ($request) {
            $validated = $this->validateRequest($request, [
                'callee_id' => 'required|exists:users,id',
                'call_type' => 'required|in:voice,video',
            ]);

            $call = $this->createResource(CallHistory::class, [
                'caller_id' => Auth::id(),
                'callee_id' => $validated['callee_id'],
                'call_type' => $validated['call_type'],
                'status' => 'initiated',
                'started_at' => now(),
            ]);

            return response()->json([
                'message' => 'Call initiated successfully',
                'data' => $call
            ]);
        });
    }

    public function acceptCall($callId)
    {
        return $this->handleErrors(function() use ($callId) {
            $call = $this->getResource(CallHistory::class, $callId);

            if ($call->status !== 'initiated') {
                throw new \Exception('Call cannot be accepted');
            }

            $call->update([
                'status' => 'accepted',
                'accepted_at' => now()
            ]);

            return response()->json([
                'message' => 'Call accepted successfully',
                'data' => $call
            ]);
        });
    }

    public function rejectCall($callId)
    {
        return $this->handleErrors(function() use ($callId) {
            $call = $this->getResource(CallHistory::class, $callId);

            if ($call->status !== 'initiated') {
                throw new \Exception('Call cannot be rejected');
            }

            $call->update([
                'status' => 'rejected',
                'rejected_at' => now()
            ]);

            $call->update([
                'status' => 'rejected',
                'ended_at' => now(),
            ]);

            return response()->json(['message' => 'Call rejected successfully', 'data' => $call]);
        });
    }

    public function endCall($callId)
    {
        return $this->handleErrors(function() use ($callId) {
            $call = $this->getResource(CallHistory::class, $callId);

            if (!in_array($call->status, ['accepted', 'initiated'])) {
                throw new \Exception('Call cannot be ended');
            }

            $call->update([
                'status' => 'ended',
                'ended_at' => now()
            ]);

            return response()->json([
                'message' => 'Call ended successfully',
                'data' => $call
            ]);
        });
    }

    public function getCallHistory(Request $request)
    {
        return $this->handleErrors(function() use ($request) {
            $validated = $this->validateRequest($request, [
                'user_id' => 'nullable|exists:users,id',
                'call_type' => 'nullable|in:voice,video',
                'status' => 'nullable|in:initiated,accepted,rejected,ended,missed',
                'limit' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1',
            ]);

            $query = CallHistory::query()
                ->where(function ($query) use ($validated) {
                    if ($validated['user_id']) {
                        $query->where('caller_id', $validated['user_id'])
                            ->orWhere('callee_id', $validated['user_id']);
                    }
                })
                ->when($validated['call_type'], function ($query) use ($validated) {
                    $query->where('call_type', $validated['call_type']);
                })
                ->when($validated['status'], function ($query) use ($validated) {
                    $query->where('status', $validated['status']);
                })
                ->orderBy('started_at', 'desc');

            $calls = $query->paginate($validated['limit'] ?? 15);

            return response()->json(['data' => $calls]);
        });
    }
}
