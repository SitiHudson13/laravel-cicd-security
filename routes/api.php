<?php

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// JSON list of the signed-in user's organization projects (session-authenticated for the lab).
Route::middleware(['web', 'auth'])->get('/projects', function (Request $request) {
    return Project::where('organization_id', $request->user()->organization_id)
        ->get(['id', 'name', 'description']);
});
