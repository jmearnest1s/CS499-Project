<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Report;

class ReportController extends Controller
{
    public function store(Request $request, Post $post)
    {
        // Validate the request
        $request->validate([
            'reason' => 'required|string',
        ]);

        // Create a new report
        Report::create([
            'post_id' => $post->id,
            'user_id' => auth()->user()->id,
            'reason' => $request->input('reason'),
        ]);

        return redirect()->back()->with('success', 'Post reported successfully.');
    }
}
