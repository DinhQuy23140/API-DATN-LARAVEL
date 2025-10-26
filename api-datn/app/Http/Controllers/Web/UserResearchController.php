<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\UserResearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserResearchController extends Controller
{
    /**
     * Remove the specified user research (only the owner can delete).
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $ur = UserResearch::findOrFail($id);
        // ensure current user owns this entry
        if ($ur->user_id !== Auth::id()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        try {
            $ur->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Delete failed'], 500);
        }
    }

    /**
     * Store a newly created user research record.
     * Accepts 'research_id' from the request and associates it with the current user.
     * Returns JSON when requested or redirects back with a status message.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'research_id' => ['required','integer','exists:research,id'],
        ]);

        $userId = Auth::id();

        // prevent duplicates
        $exists = UserResearch::where('user_id', $userId)->where('research_id', $data['research_id'])->first();
        if ($exists) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Already added'], 200);
            }
            return redirect()->back()->with('status', 'Hướng nghiên cứu đã tồn tại.');
        }

        try {
            $ur = UserResearch::create([
                'user_id' => $userId,
                'research_id' => $data['research_id'],
            ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'item' => $ur], 201);
            }

            return redirect()->back()->with('status', 'Thêm hướng nghiên cứu thành công');
        } catch (\Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Create failed'], 500);
            }
            return redirect()->back()->with('error', 'Không thể thêm hướng nghiên cứu.');
        }
    }
}
