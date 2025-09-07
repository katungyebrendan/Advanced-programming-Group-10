@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Outcomes for Project: {{ $project->title }}</h1>
        <a href="{{ route('projects.outcomes.create', $project->project_id) }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
            Add Outcome
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($outcomes->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($outcomes as $outcome)
                <div class="bg-white rounded-lg shadow-md p-6 border hover:shadow-lg transition-shadow">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="text-lg font-semibold">{{ $outcome->title }}</h3>
                        <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-800">
                            {{ $outcome->outcome_type ?? 'N/A' }}
                        </span>
                    </div>
                    
                    <p class="text-gray-600 mb-3 text-sm">
                        {{ \Illuminate\Support\Str::limit($outcome->description, 100) }}
                    </p>

                    <div class="space-y-1 text-sm mb-4">
                        <p><strong>Quality Cert:</strong> {{ $outcome->quality_certification ?? '-' }}</p>
                        <p><strong>Status:</strong> {{ $outcome->commercialization_status ?? '-' }}</p>
                        @if($outcome->artifact_link)
                            <p><strong>Artifact:</strong> 
                                <a href="{{ $outcome->artifact_link }}" target="_blank" class="text-blue-600 hover:underline">
                                    View
                                </a>
                            </p>
                        @endif
                    </div>

                    <div class="flex gap-2 mt-4">
                        <a href="{{ route('outcomes.show', $outcome->outcome_id) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View
                        </a>
                        <a href="{{ route('outcomes.edit', $outcome->outcome_id) }}" 
                           class="text-green-600 hover:text-green-800 text-sm font-medium">
                            Edit
                        </a>
                        <form action="{{ route('outcomes.destroy', $outcome->outcome_id) }}" method="POST" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this outcome?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $outcomes->links() }}
        </div>
    @else
        <div class="text-center py-8">
            <p class="text-gray-500 mb-4">No outcomes found for this project.</p>
            <a href="{{ route('projects.outcomes.create', $project->project_id) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                Add First Outcome
            </a>
        </div>
    @endif
</div>
@endsection
