<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * LAB 3 (intentionally vulnerable): no structured validation and no authorization on the project.
 */
class CommentController extends Controller
{
    public function store(Request $request, Project $project): RedirectResponse
    {
        $project->comments()->create([
            'content' => $request->input('content'),
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('projects.show', $project)->with('status', 'Comment posted.');
    }
}
