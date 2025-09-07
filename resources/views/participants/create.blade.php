@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Create New Participant</h2>
        <p class="text-gray-600">Add participant details and assign to projects</p>
    </div>
    
    <div class="card">
        <form method="POST" action="{{ route('participants.store') }}">
            @csrf
            
            <div class="p-6">
                <!-- Basic Participant Information -->
                <div class="mb-8">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Participant Information</h3>
                            <p class="text-gray-600 text-sm">Basic details about the participant</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="full_name" id="full_name" class="form-input" required 
                                   value="{{ old('full_name') }}" placeholder="Enter full name">
                            @error('full_name')
                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" id="email" class="form-input" 
                                   value="{{ old('email') }}" placeholder="email@example.com">
                            @error('email')
                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label for="affiliation" class="block text-sm font-medium text-gray-700 mb-2">
                                Affiliation <span class="text-red-500">*</span>
                            </label>
                            <select name="affiliation" id="affiliation" class="form-select" required>
                                <option value="">Select Affiliation</option>
                                @foreach($affiliations as $affiliation)
                                    <option value="{{ $affiliation }}" {{ old('affiliation') == $affiliation ? 'selected' : '' }}>
                                        {{ $affiliation }}
                                    </option>
                                @endforeach
                            </select>
                            @error('affiliation')
                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">
                                Specialization <span class="text-red-500">*</span>
                            </label>
                            <select name="specialization" id="specialization" class="form-select" required>
                                <option value="">Select Specialization</option>
                                @foreach($specializations as $specialization)
                                    <option value="{{ $specialization }}" {{ old('specialization') == $specialization ? 'selected' : '' }}>
                                        {{ $specialization }}
                                    </option>
                                @endforeach
                            </select>
                            @error('specialization')
                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="institution" class="block text-sm font-medium text-gray-700 mb-2">
                                Institution <span class="text-red-500">*</span>
                            </label>
                            <select name="institution" id="institution" class="form-select" required>
                                <option value="">Select Institution</option>
                                @foreach($institutions as $institution)
                                    <option value="{{ $institution }}" {{ old('institution') == $institution ? 'selected' : '' }}>
                                        {{ $institution }}
                                    </option>
                                @endforeach
                            </select>
                            @error('institution')
                                <div class="mt-2 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg">
                            <input type="checkbox" name="cross_skill_trained" id="cross_skill_trained" 
                                   class="form-checkbox h-5 w-5 text-blue-600 rounded" value="1" {{ old('cross_skill_trained') ? 'checked' : '' }}>
                            <label class="font-medium text-gray-700 cursor-pointer" for="cross_skill_trained">
                                Cross Skill Trained
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" class="form-textarea" rows="3" 
                                  placeholder="Optional description">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="mt-2 flex items-center text-red-600 text-sm">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Project Assignments -->
                <div class="border-t pt-8">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Project Assignments</h3>
                            <p class="text-gray-600 text-sm">Select projects for this participant</p>
                        </div>
                    </div>
                    
                    @if($projects->count() > 0)
                        <div class="space-y-3">
                            @foreach($projects as $project)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-300 transition-colors duration-200">
                                    <div class="flex items-start space-x-3">
                                        <input type="checkbox" name="projects[]" value="{{ $project->project_id }}" 
                                               id="project_{{ $project->project_id }}"
                                               class="form-checkbox h-5 w-5 text-blue-600 rounded project-checkbox mt-1">
                                        <div class="flex-1">
                                            <label class="font-medium text-gray-900 cursor-pointer" for="project_{{ $project->project_id }}">
                                                {{ $project->title }}
                                            </label>
                                            
                                            <div class="project-details mt-4" style="display: none;">
                                                <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label for="role_{{ $project->project_id }}" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                                            <input type="text" name="roles[{{ $project->project_id }}]" 
                                                                   id="role_{{ $project->project_id }}" class="form-input" 
                                                                   placeholder="e.g., Student, Lecturer">
                                                        </div>
                                                        <div>
                                                            <label for="skill_role_{{ $project->project_id }}" class="block text-sm font-medium text-gray-700 mb-2">Skill Role</label>
                                                            <input type="text" name="skill_roles[{{ $project->project_id }}]" 
                                                                   id="skill_role_{{ $project->project_id }}" class="form-input" 
                                                                   placeholder="e.g., Developer, Designer">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 flex items-center space-x-3">
                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-yellow-800 font-medium">No projects available</p>
                                <p class="text-yellow-700 text-sm">
                                    <a href="{{ route('projects.create') }}" class="underline hover:no-underline">Create a project first</a> to assign participants.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="border-t bg-gray-50 px-6 py-4">
                <div class="flex flex-col sm:flex-row gap-3 justify-between">
                    <a href="{{ route('participants.index') }}" class="btn-secondary text-center">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        Create Participant
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show/hide project details when checkbox is checked/unchecked
        document.querySelectorAll('.project-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const details = this.closest('.border').querySelector('.project-details');
                details.style.display = this.checked ? 'block' : 'none';
                
                // Add/remove visual emphasis
                const container = this.closest('.border');
                if (this.checked) {
                    container.classList.add('border-blue-300', 'bg-blue-50');
                    container.classList.remove('border-gray-200');
                } else {
                    container.classList.remove('border-blue-300', 'bg-blue-50');
                    container.classList.add('border-gray-200');
                }
            });
            
            // Initialize visibility if checkbox is already checked (after validation error)
            if (checkbox.checked) {
                const details = checkbox.closest('.border').querySelector('.project-details');
                details.style.display = 'block';
            }
        });
    });
</script>
@endsection