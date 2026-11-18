@extends('layouts.app')

@section('content')
<div class="card">
    <h2>New project</h2>
    <form method="POST" action="{{ route('projects.store') }}">
        @csrf
        <label>Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required>
        <label>Description</label>
        <textarea name="description" rows="4">{{ old('description') }}</textarea>
        <button class="primary" type="submit">Create</button>
    </form>
</div>
@endsection
