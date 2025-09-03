@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Register New Participant</h1>

    <form action="{{ route('participants.store') }}" method="POST">
        @csrf
        
        <!-- Full Name -->
        <div class="mb-4">
            <label for="full_name" class="block font-medium">Full Name</label>
            <input type="text" name="full_name" id="full_name" class="w-full border rounded p-2" 
                   value="{{ old('full_name') }}" required>
            @error('full_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block font-medium">Email</label>
            <input type="email" name="email" id="email" class="w-full border rounded p-2" 
                   value="{{ old('email') }}">
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Affiliation -->
        <div class="mb-4">
            <label for="affiliation" class="block font-medium">Affiliation</label>
            <select name="affiliation" id="affiliation" class="w-full border rounded p-2" required>
                <option value="">Select affiliation</option>
                @foreach($affiliations as $aff)
                    <option value="{{ $aff }}" {{ old('affiliation') == $aff ? 'selected' : '' }}>{{ $aff }}</option>
                @endforeach
            </select>
            @error('affiliation')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Specialization -->
        <div class="mb-4">
            <label for="specialization" class="block font-medium">Specialization</label>
            <select name="specialization" id="specialization" class="w-full border rounded p-2" required>
                <option value="">Select specialization</option>
                @foreach($specializations as $spec)
                    <option value="{{ $spec }}" {{ old('specialization') == $spec ? 'selected' : '' }}>{{ $spec }}</option>
                @endforeach
            </select>
            @error('specialization')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Cross-Skill Trained -->
        <div class="mb-4">
            <div class="flex items-center">
                <input type="hidden" name="cross_skill_trained" value="0">
                <input type="checkbox" name="cross_skill_trained" id="cross_skill_trained" value="1" 
                       class="mr-2" {{ old('cross_skill_trained') ? 'checked' : '' }}>
                <label for="cross_skill_trained" class="font-medium">Cross-Skill Trained</label>
            </div>
            @error('cross_skill_trained')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Institution -->
        <div class="mb-4">
            <label for="institution" class="block font-medium">Institution</label>
            <select name="institution" id="institution" class="w-full border rounded p-2" required>
                <option value="">Select institution</option>
                @foreach($institutions as $inst)
                    <option value="{{ $inst }}" {{ old('institution') == $inst ? 'selected' : '' }}>{{ $inst }}</option>
                @endforeach
            </select>
            @error('institution')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label for="description" class="block font-medium">Description</label>
            <textarea name="description" id="description" class="w-full border rounded p-2" rows="3">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
        <a href="{{ route('participants.index') }}" class="ml-3 text-gray-600">Cancel</a>
    </form>
</div>

<script>
    // Simple form validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    });
</script>
@endsection