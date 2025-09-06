@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Edit Project</h1>

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('projects.update', $project->project_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Left Column --}}
            <div>
                {{-- Title --}}
                <div class="mb-4">
                    <label for="title" class="block font-medium text-gray-700 mb-1">Project Title *</label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $project->title) }}"
                           class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>

                {{-- Program --}}
                <div class="mb-4">
                    <label for="program_id" class="block font-medium text-gray-700 mb-1">Program *</label>
                    <select id="program_id" 
                            name="program_id"
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select a program...</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->program_id }}" 
                                    {{ old('program_id', $project->program_id) == $program->program_id ? 'selected' : '' }}>
                                {{ $program->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Facility --}}
                <div class="mb-4">
                    <label for="facility_id" class="block font-medium text-gray-700 mb-1">Facility *</label>
                    <select id="facility_id" 
                            name="facility_id"
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select a facility...</option>
                        @foreach($facilities as $facility)
                            <option value="{{ $facility->facility_id }}" 
                                    {{ old('facility_id', $project->facility_id) == $facility->facility_id ? 'selected' : '' }}>
                                {{ $facility->name }} - {{ $facility->location }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nature --}}
                <div class="mb-4">
                    <label for="nature_of_project" class="block font-medium text-gray-700 mb-1">Nature of Project *</label>
                    <select id="nature_of_project" 
                            name="nature_of_project"
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select nature...</option>
                        @foreach(\App\Models\Project::NATURE_OPTIONS as $nature)
                            <option value="{{ $nature }}" 
                                    {{ old('nature_of_project', $project->nature_of_project) == $nature ? 'selected' : '' }}>
                                {{ $nature }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Prototype Stage --}}
                <div class="mb-4">
                    <label for="prototype_stage" class="block font-medium text-gray-700 mb-1">Prototype Stage *</label>
                    <select id="prototype_stage" 
                            name="prototype_stage"
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select stage...</option>
                        @foreach(\App\Models\Project::PROTOTYPE_STAGES as $stage)
                            <option value="{{ $stage }}" 
                                    {{ old('prototype_stage', $project->prototype_stage) == $stage ? 'selected' : '' }}>
                                {{ $stage }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Right Column --}}
            <div>
                {{-- Innovation Focus --}}
                <div class="mb-4">
                    <label for="innovation_focus" class="block font-medium text-gray-700 mb-1">Innovation Focus</label>
                    <select id="innovation_focus" 
                            name="innovation_focus"
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select focus area...</option>
                        @foreach(\App\Models\Project::INNOVATION_FOCUS_OPTIONS as $focus)
                            <option value="{{ $focus }}" 
                                    {{ old('innovation_focus', $project->innovation_focus) == $focus ? 'selected' : '' }}>
                                {{ $focus }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Description --}}
                <div class="mb-4">
                    <label for="description" class="block font-medium text-gray-700 mb-1">Description *</label>
                    <textarea id="description" 
                              name="description"
                              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              rows="4"
                              required>{{ old('description', $project->description) }}</textarea>
                </div>

                {{-- Testing Requirements --}}
                <div class="mb-4">
                    <label for="testing_requirements" class="block font-medium text-gray-700 mb-1">Testing Requirements</label>
                    <textarea id="testing_requirements" 
                              name="testing_requirements"
                              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              rows="3">{{ old('testing_requirements', $project->testing_requirements) }}</textarea>
                </div>

                {{-- Commercialization Plan --}}
                <div class="mb-4">
                    <label for="commercialization_plan" class="block font-medium text-gray-700 mb-1">Commercialization Plan</label>
                    <textarea id="commercialization_plan" 
                              name="commercialization_plan"
                              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              rows="3">{{ old('commercialization_plan', $project->commercialization_plan) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Participants --}}
        <div class="mb-4">
            <label for="participants" class="block font-medium text-gray-700 mb-1">Participants (comma-separated)</label>
            <input type="text" 
                   id="participants" 
                   name="participants" 
                   value="{{ old('participants', $project->participants) }}"
                   class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                Update Project
            </button>
            <a href="{{ route('projects.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md font-medium transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
