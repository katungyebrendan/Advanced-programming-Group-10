@extends('layouts.app')

@section('content')
 <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow mt-6">
        <h1 class="text-2xl font-bold mb-4">Participant Details</h1>

        <div class="mb-4">
            <h2 class="text-lg font-semibold">Full Name</h2>
            <p>{{ $participant->full_name }}</p>
        </div>

        <div class="mb-4">
            <h2 class="text-lg font-semibold">Email</h2>
            <p>{{ $participant->email ?? 'N/A' }}</p>
        </div>

        <div class="mb-4">
            <h2 class="text-lg font-semibold">Affiliation</h2>
            <p>{{ $participant->affiliation }}</p>
        </div>

        <div class="mb-4">
            <h2 class="text-lg font-semibold">Specialization</h2>
            <p>{{ $participant->specialization }}</p>
        </div>

        <div class="mb-4">
            <h2 class="text-lg font-semibold">Institution</h2>
            <p>{{ $participant->institution }}</p>
        </div>

        <div class="mb-4">
            <h2 class="text-lg font-semibold">Cross Skill Trained</h2>
            <p>{{ $participant->cross_skill_trained ? 'Yes' : 'No' }}</p>
        </div>

        <div class="mb-4">
            <h2 class="text-lg font-semibold">Description</h2>
            <p>{{ $participant->description ?? 'N/A' }}</p>
        </div>

        <div class="mb-4">
            <h2 class="text-lg font-semibold">Project Assignments</h2>
            @if($participant->projects->count() > 0)
                <ul class="list-disc pl-5 mt-2">
                    @foreach($participant->projects as $project)
                        <li>
                            {{ $project->title }} 
                            (Role: {{ $project->pivot->role_on_project ?? 'N/A' }}, 
                            Skill: {{ $project->pivot->skill_role ?? 'N/A' }})
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No project assignments found.</p>
            @endif
        </div>

        <div class="mt-6">
            <a href="{{ route('participants.edit', $participant) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Edit Participant</a>
            <a href="{{ route('participants.manage-projects', $participant) }}" class="bg-green-600 text-white px-4 py-2 rounded ml-3">Manage Projects</a>
            <a href="{{ route('participants.index') }}" class="ml-3 text-gray-600">Back to List</a>
        </div>
    </div>
@endsection