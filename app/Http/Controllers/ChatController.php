<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ConversationParticipant;
use App\Http\Controllers\BaseController;

class ChatController extends BaseController
{
    public function createConversation(Request $request)
    {
        return $this->handleErrors(function() use ($request) {
            $validated = $this->validateRequest($request, [
                'type' => 'required|in:private,group',
                'name' => 'required_if:type,group|string',
                'participant_ids' => 'required|array|min:2',
                'participant_ids.*' => 'required|exists:users,id',
            ]);

            $conversation = $this->createResource(Conversation::class, [
                'type' => $validated['type'],
                'name' => $validated['type'] === 'group' ? $validated['name'] : null,
            ]);

            foreach ($validated['participant_ids'] as $userId) {
                $conversation->participants()->attach($userId, [
                    'is_admin' => $userId === Auth::id() && $validated['type'] === 'group',
                ]);
            }

            return response()->json([
                'message' => 'Conversation created successfully',
                'data' => $conversation
            ]);
        });
    }

    public function sendMessage(Request $request, $conversationId)
    {
        return $this->handleErrors(function() use ($request, $conversationId) {
            $validated = $this->validateRequest($request, [
                'content' => 'required|string',
                'type' => 'required|in:text,image,file',
            ]);

            $conversation = $this->getResource(Conversation::class, $conversationId);
            $senderId = Auth::id();

            // For private conversations, set receiver_id
            if ($conversation->type === 'private') {
                $participants = $conversation->participants;
                $receiver = $participants->where('id', '!=', $senderId)->first();
                if (!$receiver) {
                    throw new \Exception('No receiver found for private conversation');
                }
                $receiverId = $receiver->id;
            } else {
                $receiverId = null; // For group conversations, receiver_id is null
            }

            $message = $this->createResource(Message::class, [
                'conversation_id' => $conversationId,
                'sender_id' => $senderId,
                'receiver_id' => $receiverId,
                'content' => $validated['content'],
                'type' => $validated['type'],
            ]);

            return response()->json([
                'message' => 'Message sent successfully',
                'data' => $message
            ]);
        });
    }

    /**
     * Get all messages for a conversation with complete user details
     * 
     * @param int $conversationId ID of the conversation
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessages($conversationId)
    {
        return $this->handleErrors(function() use ($conversationId) {
            $conversation = $this->getResource(Conversation::class, $conversationId);
            
            // Get messages with sender and receiver details
            $messages = $conversation->messages()
                ->with([
                    'sender.detail',
                    'receiver.detail'
                ])
                ->latest()
                ->paginate(20);

            // Transform messages to include all user details
            $transformedMessages = $messages->getCollection()->map(function($message) {
                $messageData = $message->toArray();
                
                // Add sender details
                if ($message->sender) {
                    $messageData['sender'] = array_merge(
                        $message->sender->toArray(),
                        ['detail' => $message->sender->detail]
                    );
                }
                
                // Add receiver details if exists
                if ($message->receiver) {
                    $messageData['receiver'] = array_merge(
                        $message->receiver->toArray(),
                        ['detail' => $message->receiver->detail]
                    );
                }
                
                return $messageData;
            });

            // Replace the original collection with the transformed one
            $messages->setCollection($transformedMessages);

            return response()->json([
                'message' => 'Messages retrieved successfully',
                'data' => $messages
            ]);
        });
    }

    /**
     * Get all conversations for a user with complete user details
     * 
     * @param int|null $userId Optional user ID. If not provided, uses authenticated user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserConversations($userId = null)
    {
        return $this->handleErrors(function() use ($userId) {
            // If userId is provided, use that user, otherwise use authenticated user
            $targetUser = $userId 
                ? User::with(['doctorDetail', 'patientDetail'])->findOrFail($userId) 
                : Auth::user()->load(['doctorDetail', 'patientDetail']);
            
            // Get all conversations where the user is a participant with all user details
            $conversations = $targetUser->conversations()
                ->with([
                    'participants' => function($query) {
                        $query->with(['doctorDetail', 'patientDetail']);
                    },
                    'messages' => function($query) {
                        $query->with(['sender.doctorDetail', 'sender.patientDetail'])
                              ->latest()
                              ->first(); // Get only the latest message with sender details
                    }
                ])
                ->orderBy('updated_at', 'desc')
                ->get();

            // Transform the conversations to include all user details
            $transformedConversations = $conversations->map(function($conversation) {
                return [
                    'id' => $conversation->id,
                    'type' => $conversation->type,
                    'name' => $conversation->name,
                    'created_at' => $conversation->created_at,
                    'updated_at' => $conversation->updated_at,
                    'participants' => $conversation->participants->map(function($participant) {
                        return array_merge(
                            $participant->toArray(),
                            ['detail' => $participant->doctorDetail ?? $participant->patientDetail]
                        );
                    }),
                    'latest_message' => $conversation->messages->first()
                ];
            });

            return response()->json([
                'message' => 'User conversations retrieved successfully',
                'data' => $transformedConversations,
                'user' => array_merge(
                    $targetUser->toArray(),
                    ['detail' => $targetUser->doctorDetail ?? $targetUser->patientDetail]
                )
            ]);
        });
    }
}
