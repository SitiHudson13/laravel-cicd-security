@extends('layouts.app')

@section('content')
<div class="card">
    <h2>Search results</h2>
    <p class="muted">Query: {{ $q }}</p>
    <table>
        <thead><tr><th>ID</th><th>Name</th><th>Description</th></tr></thead>
        <tbody>
        @forelse($results as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td><a href="{{ route('projects.show', $row->id) }}">{{ $row->name }}</a></td>
                <td>{{ $row->description }}</td>
            </tr>
        @empty
            <tr><td colspan="3" class="muted">No matching projects.</td></tr>
        @endforelse
        </tbody>
    </table>
    <a href="{{ route('projects.index') }}">Back to projects</a>
</div>
@endsection
