@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Projects</h1>
        <a href="{{ route('projects.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
            Create New Project
        </a>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white p-4 rounded shadow mb-6">
        <form method="GET" action="{{ route('projects.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" 
                       id="search"
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Search projects..."
                       class="w-full border border-gray-300 rounded-md p-2 text-sm">
            </div>

            <div>
                <label for="program_id" class="block text-sm font-medium text-gray-700 mb-1">Program</label>
                <select id="program_id" name="program_id" class="w-full border border-gray-300 rounded-md p-2 text-sm">
                    <option value="">All Programs</option>
                    @foreach($programs as $program)
                        <option value="{{ $program->program_id }}" 
                                {{ request('program_id') == $program->program_id ? 'selected' : '' }}>
                            {{ $program->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="facility_id" class="block text-sm font-medium text-gray-700 mb-1">Facility</label>
                <select id="facility_id" name="facility_id" class="w-full border border-gray-300 rounded-md p-2 text-sm">
                    <option value="">All Facilities</option>
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->facility_id }}" 
                                {{ request('facility_id') == $facility->facility_id ? 'selected' : '' }}>
                            {{ $facility->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="nature" class="block text-sm font-medium text-gray-700 mb-1">Nature</label>
                <select id="nature" name="nature" class="w-full border border-gray-300 rounded-md p-2 text-sm">
                    <option value="">All Types</option>
                    @foreach(\App\Models\Project::NATURE_OPTIONS as $nature)
                        <option value="{{ $nature }}" 
                                {{ request('nature') == $nature ? 'selected' : '' }}>
                            {{ $nature }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" 
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors mr-2">
                    Filter
                </button>
                <a href="{{ route('projects.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Clear
                </a>
            </div>
        </form>
    </div>

    {{-- Projects Grid --}}
    @if($projects->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($projects as $project)
                <div class="bg-white rounded-lg shadow-md p-6 border hover:shadow-lg transition-shadow">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="text-lg font-semibold">{{ $project->title }}</h3>
                        @php
                            $stageColors = [
                                'Concept' => 'bg-gray-100 text-gray-800',
                                'Prototype' => 'bg-yellow-100 text-yellow-800',
                                'MVP' => 'bg-blue-100 text-blue-800',
                                'Market Launch' => 'bg-green-100 text-green-800'
                            ];
                            $colorClass = $stageColors[$project->prototype_stage] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="{{ $colorClass }} text-xs px-2 py-1 rounded">
                            {{ $project->prototype_stage }}
                        </span>
                    </div>
                    
                    <p class="text-gray-600 mb-3 text-sm">
                        {{ Str::limit($project->description, 100) }}
                    </p>

                    <div class="space-y-2 mb-4">
                        <p class="text-sm">
                            <strong>Program:</strong> 
                            <span class="text-blue-600">{{ $project->program->name }}</span>
                        </p>
                        <p class="text-sm">
                            <strong>Facility:</strong> 
                            <span class="text-green-600">{{ $project->facility->name }}</span>
                        </p>
                        <p class="text-sm">
                            <strong>Nature:</strong> {{ $project->nature_of_project }}
                        </p>
                        @if($project->innovation_focus)
                            <p class="text-sm">
                                <strong>Focus:</strong> {{ $project->innovation_focus }}
                            </p>
                        @endif
                    </div>

                    {{-- Progress Bar --}}
                    <div class="mb-4">
                        <div class="flex justify-between text-xs mb-1">
                            <span>Progress</span>
                            <span>{{ $project->getProgressPercentage() }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" 
                                {!! 'style="width: ' . $project->getProgressPercentage() . ';"' !!}></div>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-4">
                        <a href="{{ route('projects.show', $project->project_id) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View
                        </a>
                        <a href="{{ route('projects.edit', $project->project_id) }}" 
                           class="text-green-600 hover:text-green-800 text-sm font-medium">
                            Edit
                        </a>
                        @if($project->canBeDeleted())
                            <form action="{{ route('projects.destroy', $project->project_id) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this project?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $projects->withQueryString()->links() }}
        </div>
    @else
        <div class="text-center py-8">
            <p class="text-gray-500 mb-4">No projects found.</p>
            <a href="{{ route('projects.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                Create Your First Project
            </a>
        </div>
    @endif
</div>
@endsection