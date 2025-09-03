@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow-sm border border-gray-200">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Edit Participant</h1>

    <form action="{{ route('participants.update', $participant->participant_id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Full Name -->
        <div class="mb-4">
            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input type="text" name="full_name" id="full_name" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                   value="{{ old('full_name', $participant->full_name) }}" required>
            @error('full_name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" id="email" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                   value="{{ old('email', $participant->email) }}">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Affiliation -->
        <div class="mb-4">
            <label for="affiliation" class="block text-sm font-medium text-gray-700 mb-1">Affiliation</label>
            <select name="affiliation" id="affiliation" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">Select affiliation</option>
                @foreach($affiliations as $aff)
                    <option value="{{ $aff }}" {{ old('affiliation', $participant->affiliation) == $aff ? 'selected' : '' }}>{{ $aff }}</option>
                @endforeach
            </select>
            @error('affiliation')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Specialization -->
        <div class="mb-4">
            <label for="specialization" class="block text-sm font-medium text-gray-700 mb-1">Specialization</label>
            <select name="specialization" id="specialization" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">Select specialization</option>
                @foreach($specializations as $spec)
                    <option value="{{ $spec }}" {{ old('specialization', $participant->specialization) == $spec ? 'selected' : '' }}>{{ $spec }}</option>
                @endforeach
            </select>
            @error('specialization')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Cross-Skill Trained -->
        <div class="mb-4">
            <div class="flex items-center">
                <input type="hidden" name="cross_skill_trained" value="0">
                <input type="checkbox" name="cross_skill_trained" id="cross_skill_trained" value="1"
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                       {{ old('cross_skill_trained', $participant->cross_skill_trained) ? 'checked' : '' }}>
                <label for="cross_skill_trained" class="ml-2 block text-sm text-gray-700">Cross-Skill Trained</label>
            </div>
            @error('cross_skill_trained')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Institution -->
        <div class="mb-4">
            <label for="institution" class="block text-sm font-medium text-gray-700 mb-1">Institution</label>
            <select name="institution" id="institution" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">Select institution</option>
                @foreach($institutions as $inst)
                    <option value="{{ $inst }}" {{ old('institution', $participant->institution) == $inst ? 'selected' : '' }}>{{ $inst }}</option>
                @endforeach
            </select>
            @error('institution')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" id="description" rows="3"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $participant->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-medium">
                Update Participant
            </button>
            <a href="{{ route('participants.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 text-sm font-medium">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection