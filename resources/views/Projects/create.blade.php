@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Create New Project</h1>

    {{-- Display validation errors --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Display error message --}}
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('projects.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Left Column --}}
            <div>
                <div class="mb-4">
                    <label for="title" class="block font-medium text-gray-700 mb-1">Project Title *</label>
                    <input type="text" 
                           id="title"
                           name="title" 
                           value="{{ old('title') }}"
                           class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           required>
                </div>

                <div class="mb-4">
                    <label for="program_name" class="block font-medium text-gray-700 mb-1">Program *</label>
                    <select id="program_name"
                            name="program_name" 
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select a program...</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->name }}" 
                                    {{ old('program_name') == $program->name ? 'selected' : '' }}>
                                {{ $program->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="facility_name" class="block font-medium text-gray-700 mb-1">Facility *</label>
                    <select id="facility_name"
                            name="facility_name" 
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select a facility...</option>
                        @foreach($facilities as $facility)
                            <option value="{{ $facility->name }}" 
                                    {{ old('facility_name') == $facility->name ? 'selected' : '' }}>
                                {{ $facility->name }} - {{ $facility->location }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="nature_of_project" class="block font-medium text-gray-700 mb-1">Nature of Project *</label>
                    <select id="nature_of_project"
                            name="nature_of_project" 
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select nature...</option>
                        @foreach(\App\Models\Project::NATURE_OPTIONS as $nature)
                            <option value="{{ $nature }}" 
                                    {{ old('nature_of_project') == $nature ? 'selected' : '' }}>
                                {{ $nature }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="prototype_stage" class="block font-medium text-gray-700 mb-1">Prototype Stage *</label>
                    <select id="prototype_stage"
                            name="prototype_stage" 
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select stage...</option>
                        @foreach(\App\Models\Project::PROTOTYPE_STAGES as $stage)
                            <option value="{{ $stage }}" 
                                    {{ old('prototype_stage') == $stage ? 'selected' : '' }}>
                                {{ $stage }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Right Column --}}
            <div>
                <div class="mb-4">
                    <label for="innovation_focus" class="block font-medium text-gray-700 mb-1">Innovation Focus</label>
                    <select id="innovation_focus"
                            name="innovation_focus" 
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select focus area...</option>
                        @foreach(\App\Models\Project::INNOVATION_FOCUS_OPTIONS as $focus)
                            <option value="{{ $focus }}" 
                                    {{ old('innovation_focus') == $focus ? 'selected' : '' }}>
                                {{ $focus }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="description" class="block font-medium text-gray-700 mb-1">Description *</label>
                    <textarea id="description"
                              name="description" 
                              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                              rows="4"
                              required>{{ old('description') }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="testing_requirements" class="block font-medium text-gray-700 mb-1">Testing Requirements</label>
                    <textarea id="testing_requirements"
                              name="testing_requirements" 
                              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                              rows="3">{{ old('testing_requirements') }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="commercialization_plan" class="block font-medium text-gray-700 mb-1">Commercialization Plan</label>
                    <textarea id="commercialization_plan"
                              name="commercialization_plan" 
                              class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                              rows="3">{{ old('commercialization_plan') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                Create Project
            </button>
            <a href="{{ route('projects.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md font-medium transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection