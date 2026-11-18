<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Models\Document;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

/**
 * REFERENCE SOLUTION (instructor): fixes R02 (download authorization) and R03 (upload validation).
 */
class DocumentController extends Controller
{
    public function store(StoreDocumentRequest $request, $projectId): RedirectResponse
    {
        $project = Project::findOrFail($projectId);
        abort_unless($project->organization_id === $request->user()->organization_id, 404);

        $file = $request->file('file');

        // Server-generated storage name; client filename kept only as display metadata.
        $path = $file->store("documents/{$project->id}", 'local');

        Document::create([
            'project_id' => $project->id,
            'original_name' => basename($file->getClientOriginalName()),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'uploaded_by' => $request->user()->id,
        ]);

        return redirect()->route('projects.show', $project->id)->with('status', 'Document uploaded.');
    }

    /** R02 fix: authorize via DocumentPolicy before streaming the private file. */
    public function download($id)
    {
        $document = Document::with('project')->findOrFail($id);
        $this->authorize('view', $document);

        return Storage::disk('local')->download($document->path, $document->original_name);
    }
}
