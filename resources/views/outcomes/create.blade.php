@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">
        Add New Outcome for Project: {{ $selectedProject->title }}
    </h1>

    <form action="{{ route('projects.outcomes.store', $selectedProject->project_id) }}" method="POST" class="bg-white shadow-md rounded-lg p-6 space-y-6">
        @csrf

        {{-- Title --}}
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                Title <span class="text-red-500">*</span>
            </label>
            <input type="text" id="title" name="title" value="{{ old('title') }}"
                   class="w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-blue-500 focus:border-blue-500" required>
            @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                Description
            </label>
            <textarea id="description" name="description" rows="4"
                      class="w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
            @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Artifact Link --}}
        <div>
            <label for="artifact_link" class="block text-sm font-medium text-gray-700 mb-1">
                Artifact Link
            </label>
            <input type="url" id="artifact_link" name="artifact_link" value="{{ old('artifact_link') }}"
                   class="w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-blue-500 focus:border-blue-500">
            @error('artifact_link') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Outcome Type --}}
        <div>
            <label for="outcome_type" class="block text-sm font-medium text-gray-700 mb-1">
                Outcome Type
            </label>
            <select id="outcome_type" name="outcome_type"
                    class="w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Type</option>
                <option value="CAD" {{ old('outcome_type') == 'CAD' ? 'selected' : '' }}>CAD</option>
                <option value="PCB" {{ old('outcome_type') == 'PCB' ? 'selected' : '' }}>PCB</option>
                <option value="Prototype" {{ old('outcome_type') == 'Prototype' ? 'selected' : '' }}>Prototype</option>
                <option value="Report" {{ old('outcome_type') == 'Report' ? 'selected' : '' }}>Report</option>
                <option value="Business Plan" {{ old('outcome_type') == 'Business Plan' ? 'selected' : '' }}>Business Plan</option>
            </select>
            @error('outcome_type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Quality Certification --}}
        <div>
            <label for="quality_certification" class="block text-sm font-medium text-gray-700 mb-1">
                Quality Certification
            </label>
            <input type="text" id="quality_certification" name="quality_certification" value="{{ old('quality_certification') }}"
                   class="w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-blue-500 focus:border-blue-500">
            @error('quality_certification') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Commercialization Status --}}
        <div>
            <label for="commercialization_status" class="block text-sm font-medium text-gray-700 mb-1">
                Commercialization Status
            </label>
            <select id="commercialization_status" name="commercialization_status"
                    class="w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Status</option>
                <option value="Demoed" {{ old('commercialization_status') == 'Demoed' ? 'selected' : '' }}>Demoed</option>
                <option value="Market Linked" {{ old('commercialization_status') == 'Market Linked' ? 'selected' : '' }}>Market Linked</option>
                <option value="Launched" {{ old('commercialization_status') == 'Launched' ? 'selected' : '' }}>Launched</option>
            </select>
            @error('commercialization_status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Form Actions --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('projects.outcomes.index', $selectedProject->project_id) }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                Save Outcome
            </button>
        </div>
    </form>
</div>
@endsection