@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Outcome for Project: {{ $project->title }}</h2>

    <form action="{{ route('outcomes.update', $outcome->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Title</label>
            <input name="title" class="form-control" value="{{ $outcome->title }}" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $outcome->description }}</textarea>
        </div>
        <div class="mb-3">
            <label>Artifact Link</label>
            <input name="artifact_link" class="form-control" value="{{ $outcome->artifact_link }}">
        </div>
        <div class="mb-3">
            <label>Outcome Type</label>
            <input name="outcome_type" class="form-control" value="{{ $outcome->outcome_type }}">
        </div>
        <div class="mb-3">
            <label>Quality Certification</label>
            <input name="quality_certification" class="form-control" value="{{ $outcome->quality_certification }}">
        </div>
        <button class="btn btn-success">Update</button>
        <a href="{{ route('projects.outcomes.index', $project->id) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
