@extends('layouts.app')

@section('content')
<div class="card">
    <h2>{{ $project->name }} <span class="muted">(#{{ $project->id }})</span></h2>
    <p class="muted">Organization: {{ $project->organization->name }}</p>
    <p>{{ $project->description }}</p>
    <a class="btn" href="{{ route('projects.edit', $project->id) }}">Edit</a>
    @if(auth()->user()->isOrgAdmin())
        <form method="POST" action="{{ route('projects.destroy', $project->id) }}" style="display:inline"
              onsubmit="return confirm('Delete this project?')">
            @csrf
            @method('DELETE')
            <button class="danger" type="submit">Delete</button>
        </form>
    @endif
</div>

<div class="card">
    <h3>Documents</h3>
    <table>
        <thead><tr><th>ID</th><th>File</th><th>Size</th><th></th></tr></thead>
        <tbody>
        @forelse($project->documents as $document)
            <tr>
                <td>{{ $document->id }}</td>
                <td>{{ $document->original_name }}</td>
                <td>{{ $document->size }} bytes</td>
                <td><a href="{{ route('projects.documents.download', [$project->id, $document->id]) }}">Download</a></td>
            </tr>
        @empty
            <tr><td colspan="4" class="muted">No documents.</td></tr>
        @endforelse
        </tbody>
    </table>

    <h4>Upload a document</h4>
    <form method="POST" action="{{ route('documents.store', $project->id) }}" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button class="primary" type="submit">Upload</button>
    </form>
</div>
@endsection
