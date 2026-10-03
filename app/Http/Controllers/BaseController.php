<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

abstract class BaseController extends Controller
{
    // Base method for error handling
    protected function handleErrors($callback)
    {
        try {
            return $callback();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Resource not found',
                'message' => $e->getMessage()
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('API Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Base method for validation
    protected function validateRequest($request, $rules)
    {
        try {
            return $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        }
    }

    // Base method for creating resources
    protected function createResource($model, $data)
    {
        return $model::create($data);
    }

    // Base method for updating resources
    protected function updateResource($model, $id, $data)
    {
        $resource = $model::findOrFail($id);
        $resource->update($data);
        return $resource;
    }

    // Base method for deleting resources
    protected function deleteResource($model, $id)
    {
        $resource = $model::findOrFail($id);
        $resource->delete();
        return response()->json([
            'message' => 'Resource deleted successfully'
        ]);
    }

    // Base method for getting a single resource
    protected function getResource($model, $id)
    {
        return $model::findOrFail($id);
    }

    // Base method for listing resources
    protected function listResources($query, $paginate = true, $perPage = 15)
    {
        return $paginate ? $query->paginate($perPage) : $query->get();
    }
}
