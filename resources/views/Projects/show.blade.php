@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Project Details</h2>

    <div class="card mb-3">
        <div class="card-header">
            <strong>{{ $project->title }}</strong>
        </div>
        <div class="card-body">
            <p><strong>Description:</strong> {{ $project->description }}</p>
            <p><strong>Facility:</strong> {{ optional($project->facility)->name ?? 'N/A' }}</p>
            <p><strong>Program:</strong> {{ optional($project->program)->name ?? 'N/A' }}</p>
            <p><strong>Innovation Focus:</strong> {{ $project->innovation_focus }}</p>
            <p><strong>Prototype Stage:</strong> {{ $project->prototype_stage }}</p>
            <p><strong>Commercialization Plan:</strong> {{ $project->commercialization_plan }}</p>
            
            <p><strong>Participants:</strong>
                @if(!empty($project->participants))
                    <ul>
                        @foreach($project->participants as $participant)
                            <li>{{ $participant }}</li>
                        @endforeach
                    </ul>
                @else
                    <span>No participants assigned</span>
                @endif
            </p>

            <p><strong>Outcomes:</strong>
                @if($project->outcomes->isNotEmpty())
                    <ul>
                        @foreach($project->outcomes as $outcome)
                            <li>{{ $outcome->title ?? $outcome->description }}</li>
                        @endforeach
                    </ul>
                @else
                    <span>No outcomes yet</span>
                @endif
            </p>
        </div>
    </div>

    <a href="{{ url('/projects') }}" class="btn btn-secondary">Back to Projects List</a>
</div>
@endsection
