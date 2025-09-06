@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Edit Service</h2>

    <form action="{{ route('services.update', $service->service_id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Name -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
            <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded" required
                   value="{{ old('name', $service->name) }}">
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Facility -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Facility *</label>
            <select name="facility_id" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                <option value="">Select Facility</option>
                @foreach($facilities as $facility)
                    <option value="{{ $facility->facility_id }}" 
                        {{ old('facility_id', $service->facility_id) == $facility->facility_id ? 'selected' : '' }}>
                        {{ $facility->name }}
                    </option>
                @endforeach
            </select>
            @error('facility_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" class="w-full px-3 py-2 border border-gray-300 rounded" rows="3">{{ old('description', $service->description) }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Category -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
            <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" 
                        {{ old('category', $service->category) == $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                @endforeach
            </select>
            @error('category')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Skill Type -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Skill Type *</label>
            <select name="skill_type" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                <option value="">Select Skill Type</option>
                @foreach($skillTypes as $skillType)
                    <option value="{{ $skillType }}" 
                        {{ old('skill_type', $service->skill_type) == $skillType ? 'selected' : '' }}>
                        {{ $skillType }}
                    </option>
                @endforeach
            </select>
            @error('skill_type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Update Service
            </button>
            <a href="{{ route('services.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection