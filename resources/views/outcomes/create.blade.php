@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Outcome for Project: {{ $project->title }}</h2>

    <form action="{{ route('projects.outcomes.store', $project->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Title</label>
            <input name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>Artifact Link</label>
            <input name="artifact_link" class="form-control">
        </div>
        <div class="mb-3">
            <label>Outcome Type</label>
            <input name="outcome_type" class="form-control">
        </div>
        <div class="mb-3">
            <label>Quality Certification</label>
            <input name="quality_certification" class="form-control">
        </div>
        <button class="btn btn-success">Save</button>
        <a href="{{ route('projects.outcomes.index', $project->id) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
