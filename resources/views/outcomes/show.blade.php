@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Outcome Details</h2>

    <table class="table table-bordered">
        <tr><th>ID</th><td>{{ $outcome->id }}</td></tr>
        <tr><th>Title</th><td>{{ $outcome->title }}</td></tr>
        <tr><th>Description</th><td>{{ $outcome->description ?? '-' }}</td></tr>
        <tr><th>Artifact Link</th><td>{{ $outcome->artifact_link ?? '-' }}</td></tr>
        <tr><th>Outcome Type</th><td>{{ $outcome->outcome_type ?? '-' }}</td></tr>
        <tr><th>Quality Certification</th><td>{{ $outcome->quality_certification ?? '-' }}</td></tr>
        <tr><th>Project</th><td>{{ $outcome->project->title ?? 'N/A' }}</td></tr>
    </table>

    <a href="{{ route('projects.outcomes.index', $outcome->project_id) }}" class="btn btn-secondary">Back</a>
    <a href="{{ route('outcomes.edit', $outcome->id) }}" class="btn btn-warning">Edit</a>
</div>
@endsection
