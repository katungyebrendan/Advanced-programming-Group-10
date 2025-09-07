@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">
            Outcome Details
        </h1>

        <dl class="divide-y divide-gray-200">
            <div class="py-3 flex justify-between">
                <dt class="font-medium text-gray-700">ID</dt>
                <dd class="text-gray-900">{{ $outcome->outcome_id }}</dd>
            </div>

            <div class="py-3 flex justify-between">
                <dt class="font-medium text-gray-700">Title</dt>
                <dd class="text-gray-900">{{ $outcome->title }}</dd>
            </div>

            <div class="py-3 flex justify-between">
                <dt class="font-medium text-gray-700">Description</dt>
                <dd class="text-gray-900">{{ $outcome->description ?? '-' }}</dd>
            </div>

            <div class="py-3 flex justify-between">
                <dt class="font-medium text-gray-700">Artifact Link</dt>
                <dd>
                    @if($outcome->artifact_link)
                        <a href="{{ $outcome->artifact_link }}" target="_blank" class="text-blue-600 hover:underline">
                            View Artifact
                        </a>
                    @else
                        <span class="text-gray-500">-</span>
                    @endif
                </dd>
            </div>

            <div class="py-3 flex justify-between">
                <dt class="font-medium text-gray-700">Outcome Type</dt>
                <dd>
                    <span class="px-2 py-1 rounded text-sm 
                        @switch($outcome->outcome_type)
                            @case('CAD') bg-purple-100 text-purple-800 @break
                            @case('PCB') bg-yellow-100 text-yellow-800 @break
                            @case('Prototype') bg-blue-100 text-blue-800 @break
                            @case('Report') bg-green-100 text-green-800 @break
                            @case('Business Plan') bg-pink-100 text-pink-800 @break
                            @default bg-gray-100 text-gray-800
                        @endswitch">
                        {{ $outcome->outcome_type ?? 'N/A' }}
                    </span>
                </dd>
            </div>

            <div class="py-3 flex justify-between">
                <dt class="font-medium text-gray-700">Quality Certification</dt>
                <dd class="text-gray-900">{{ $outcome->quality_certification ?? '-' }}</dd>
            </div>

            <div class="py-3 flex justify-between">
                <dt class="font-medium text-gray-700">Commercialization Status</dt>
                <dd class="text-indigo-700 font-medium">{{ $outcome->commercialization_status ?? '-' }}</dd>
            </div>

            <div class="py-3 flex justify-between">
                <dt class="font-medium text-gray-700">Project</dt>
                <dd class="text-gray-900">{{ $outcome->project->title ?? 'N/A' }}</dd>
            </div>
        </dl>

        {{-- Actions --}}
        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('projects.outcomes.index', $outcome->project_id) }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                Back
            </a>
            <a href="{{ route('outcomes.edit', $outcome->outcome_id) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                Edit
            </a>
            <form action="{{ route('outcomes.destroy', $outcome->outcome_id) }}" method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this outcome?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
