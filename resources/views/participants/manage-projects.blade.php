@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Manage Projects</h2>
        <p class="text-gray-600">for: <span class="font-semibold text-gray-800">{{ $participant->full_name }}</span></p>
    </div>
    
    <form method="POST" action="{{ route('participants.update-projects', $participant) }}">
        @csrf
        @method('PUT')
        
        <div class="card">
            <div class="p-6">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Project Assignments</h3>
                        <p class="text-gray-600 text-sm">Select and configure project roles</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    @foreach($projects as $project)
                        @php
                            $participantProject = $participant->projects->find($project->project_id);
                            $isChecked = in_array($project->project_id, old('projects', $participant->projects->pluck('project_id')->toArray()));
                        @endphp
                        
                        <div class="border border-gray-200 rounded-xl p-4 hover:border-gray-300 transition-colors duration-200">
                            <div class="flex items-start space-x-3">
                                <input type="checkbox" name="projects[]" value="{{ $project->project_id }}"
                                       id="project_{{ $project->project_id }}"
                                       class="form-checkbox h-5 w-5 text-blue-600 rounded project-checkbox mt-1"
                                       {{ $isChecked ? 'checked' : '' }}>
                                
                                <div class="flex-1">
                                    <label class="font-semibold text-gray-900 cursor-pointer" for="project_{{ $project->project_id }}">
                                        {{ $project->title }}
                                    </label>
                                    
                                    <div class="project-details mt-4" style="display: {{ $isChecked ? 'block' : 'none' }};">
                                        <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="role_{{ $project->project_id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                        Role on Project
                                                    </label>
                                                    <input type="text" name="roles[{{ $project->project_id }}]"
                                                           id="role_{{ $project->project_id }}" class="form-input"
                                                           value="{{ old('roles.'.$project->project_id, $participantProject->pivot->role_on_project ?? '') }}"
                                                           placeholder="e.g., Student, Lecturer">
                                                </div>
                                                <div>
                                                    <label for="skill_role_{{ $project->project_id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                        Skill Role
                                                    </label>
                                                    <input type="text" name="skill_roles[{{ $project->project_id }}]"
                                                           id="skill_role_{{ $project->project_id }}" class="form-input"
                                                           value="{{ old('skill_roles.'.$project->project_id, $participantProject->pivot->skill_role ?? '') }}"
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
            </div>
        </div>
        
        <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-between">
            <a href="{{ route('participants.show', $participant) }}" class="btn-secondary text-center">
                <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
                Back to Participant
            </a>
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Update Project Assignments
            </button>
        </div>
    </form>
</div>

<script>
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
        
        // Initialize state
        checkbox.dispatchEvent(new Event('change'));
    });
</script>
@endsection