<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * TRAINING LAB: document upload/download. Review authorization AND validation separately.
 */
class DocumentController extends Controller
{
    public function store(Request $request, $projectId): RedirectResponse
    {
        $project = Project::findOrFail($projectId);

        // Tenant check on the parent project
        abort_unless($project->organization_id === $request->user()->organization_id, 404);

        $request->validate([
            'file' => ['required', 'file'],
        ]);

        $file = $request->file('file');

        $path = $file->storeAs(
            "documents/{$project->id}",
            $file->getClientOriginalName(),
            'local'
        );

        Document::create([
            'project_id' => $project->id,
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'uploaded_by' => $request->user()->id,
        ]);

        return redirect()->route('projects.show', $project->id)->with('status', 'Document uploaded.');
    }

    /** Download by document ID. Files live on the private "local" disk (storage/app/private). */
    public function download($id)
    {
        $document = Document::findOrFail($id);

        return Storage::disk('local')->download($document->path, $document->original_name);
    }
}
