<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;

class MhcController extends BaseController
{
    // List all MHC content
    public function index()
    {
        return $this->handleErrors(function () {
            $contents = Content::all();
            return response()->json([
                'success' => true,
                'data' => $contents
            ]);
        });
    }

    // Show a single MHC content
    public function show($id)
    {
        return $this->handleErrors(function () use ($id) {
            $content = Content::find($id);
            if (!$content) {
                return response()->json(['success' => false, 'message' => 'Content not found.'], 404);
            }
            return response()->json(['success' => true, 'data' => $content]);
        });
    }

    // Create new MHC content
    public function store(Request $request)
    {
        return $this->handleErrors(function () use ($request) {
            $validator = \Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'content' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $filePath = null;
            if ($request->hasFile('content')) {
                $file = $request->file('content');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = 'contents/' . date('Y/m');
                if (!file_exists(public_path($path))) {
                    mkdir(public_path($path), 0777, true);
                }
                $file->move(public_path($path), $fileName);
                $filePath = $path . '/' . $fileName;
            }
            $content = new Content();
            $content->title = $request->title;
            $content->description = $request->description;
            $content->content = $filePath;
            $content->save();
            return response()->json(['success' => true, 'data' => $content, 'message' => 'Content created successfully.']);
        });
    }

    // Update MHC content
    public function update(Request $request, $id)
    {
        return $this->handleErrors(function () use ($request, $id) {
            $content = Content::find($id);
            if (!$content) {
                return response()->json(['success' => false, 'message' => 'Content not found.'], 404);
            }
            $validator = \Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'content' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            if ($request->hasFile('content')) {
                if ($content->content && file_exists(public_path($content->content))) {
                    unlink(public_path($content->content));
                }
                $file = $request->file('content');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = 'contents/' . date('Y/m');
                if (!file_exists(public_path($path))) {
                    mkdir(public_path($path), 0777, true);
                }
                $file->move(public_path($path), $fileName);
                $content->content = $path . '/' . $fileName;
            }
            $content->title = $request->title;
            $content->description = $request->description;
            $content->save();
            return response()->json(['success' => true, 'data' => $content, 'message' => 'Content updated successfully.']);
        });
    }

    // Delete MHC content
    public function destroy($id)
    {
        return $this->handleErrors(function () use ($id) {
            $content = Content::find($id);
            if (!$content) {
                return response()->json(['success' => false, 'message' => 'Content not found.'], 404);
            }
            if ($content->content && file_exists(public_path($content->content))) {
                unlink(public_path($content->content));
            }
            $content->delete();
            return response()->json(['success' => true, 'message' => 'Content deleted successfully.']);
        });
    }
} 