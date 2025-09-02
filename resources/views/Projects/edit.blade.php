@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Edit Project</h2>

    {{-- Display validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Display success message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('projects.update', $project->project_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Project Title</label>
            <input type="text" class="form-control" name="title" id="title" value="{{ old('title', $project->title) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Project Description</label>
            <textarea class="form-control" name="description" id="description" rows="3">{{ old('description', $project->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="facility_id" class="form-label">Facility ID</label>
            <input type="number" class="form-control" name="facility_id" id="facility_id" value="{{ old('facility_id', $project->facility_id) }}">
        </div>

        <div class="mb-3">
            <label for="program_id" class="form-label">Program ID</label>
            <input type="number" class="form-control" name="program_id" id="program_id" value="{{ old('program_id', $project->program_id) }}">
        </div>

        <div class="mb-3">
            <label for="innovation_focus" class="form-label">Innovation Focus</label>
            <input type="text" class="form-control" name="innovation_focus" id="innovation_focus" value="{{ old('innovation_focus', $project->innovation_focus) }}">
        </div>

        <div class="mb-3">
            <label for="prototype_stage" class="form-label">Prototype Stage</label>
            <input type="text" class="form-control" name="prototype_stage" id="prototype_stage" value="{{ old('prototype_stage', $project->prototype_stage) }}">
        </div>

        <div class="mb-3">
            <label for="commercialization_plan" class="form-label">Commercialization Plan</label>
            <input type="text" class="form-control" name="commercialization_plan" id="commercialization_plan" value="{{ old('commercialization_plan', $project->commercialization_plan) }}">
        </div>

        <div class="mb-3">
            <label for="participants" class="form-label">Participants (comma-separated)</label>
            <input type="text" class="form-control" name="participants" id="participants" value="{{ old('participants', $project->participants) }}">
        </div>

        <button type="submit" class="btn btn-primary">Update Project</button>
    </form>
</div>
@endsection
