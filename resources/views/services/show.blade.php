@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Service Details</h2>
    <table class="table table-bordered">
        <tr><th>ID</th><td>{{ $service->id }}</td></tr>
        <tr><th>Name</th><td>{{ $service->name }}</td></tr>
        <tr><th>Facility</th><td>{{ $service->facility->name ?? 'N/A' }}</td></tr>
        <tr><th>Description</th><td>{{ $service->description ?? '-' }}</td></tr>
        <tr><th>Category</th><td>{{ $service->category ?? '-' }}</td></tr>
        <tr><th>Skill Type</th><td>{{ $service->skill_type ?? '-' }}</td></tr>
    </table>
    <a href="{{ route('services.index') }}" class="btn btn-secondary">Back</a>
    <a href="{{ route('services.edit', $service->id) }}" class="btn btn-warning">Edit</a>
</div>
@endsection
