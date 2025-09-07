@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow mt-6">
        <h1 class="text-2xl font-bold mb-6">Edit Participant: {{ $participant->full_name }}</h1>
        
        <form method="POST" action="{{ route('participants.update', $participant) }}">
            @csrf
            @method('PUT')
            
            <!-- Basic Participant Information -->
            <div class="mb-6 p-4 border rounded">
                <h2 class="text-lg font-semibold mb-4">Participant Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                        <input type="text" name="full_name" id="full_name" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required 
                               value="{{ old('full_name', $participant->full_name) }}">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" class="mt-1 block w-full border border-gray-300 rounded-md p-2" 
                               value="{{ old('email', $participant->email) }}">
                    </div>
                    <div>
                        <label for="affiliation" class="block text-sm font-medium text-gray-700">Affiliation *</label>
                        <input type="text" name="affiliation" id="affiliation" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required 
                               value="{{ old('affiliation', $participant->affiliation) }}">
                    </div>
                    <div>
                        <label for="specialization" class="block text-sm font-medium text-gray-700">Specialization *</label>
                        <input type="text" name="specialization" id="specialization" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required 
                               value="{{ old('specialization', $participant->specialization) }}">
                    </div>
                    <div>
                        <label for="institution" class="block text-sm font-medium text-gray-700">Institution *</label>
                        <input type="text" name="institution" id="institution" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required 
                               value="{{ old('institution', $participant->institution) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cross Skill Trained</label>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="cross_skill_trained" value="1" class="rounded border-gray-300" 
                                    {{ old('cross_skill_trained', $participant->cross_skill_trained) ? 'checked' : '' }}>
                                <span class="ml-2">Yes</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md p-2">{{ old('description', $participant->description) }}</textarea>
                </div>
            </div>

            <!-- Project Assignments -->
            <div class="mb-6 p-4 border rounded">
                <h2 class="text-lg font-semibold mb-4">Project Assignments</h2>
                
                @foreach($projects as $project)
                    @php
                        $participantProject = $participant->projects->find($project->project_id);
                        $isChecked = in_array($project->project_id, old('projects', $participant->projects->pluck('project_id')->toArray()));
                    @endphp
                    
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="projects[]" value="{{ $project->project_id }}" 
                                   class="project-checkbox rounded border-gray-300"
                                   {{ $isChecked ? 'checked' : '' }}>
                            <span class="ml-2 font-medium">{{ $project->title }}</span>
                        </label>

                        <div class="project-details ml-6 mt-2" style="display: {{ $isChecked ? 'block' : 'none' }};">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="role_{{ $project->project_id }}" class="block text-sm font-medium text-gray-700">Role on Project:</label>
                                    <input type="text" name="roles[{{ $project->project_id }}]" 
                                           id="role_{{ $project->project_id }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2" 
                                           value="{{ old('roles.'.$project->project_id, $participantProject->pivot->role_on_project ?? '') }}"
                                           placeholder="e.g., Student, Lecturer">
                                </div>
                                <div>
                                    <label for="skill_role_{{ $project->project_id }}" class="block text-sm font-medium text-gray-700">Skill Role:</label>
                                    <input type="text" name="skill_roles[{{ $project->project_id }}]" 
                                           id="skill_role_{{ $project->project_id }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2" 
                                           value="{{ old('skill_roles.'.$project->project_id, $participantProject->pivot->skill_role ?? '') }}"
                                           placeholder="e.g., Developer, Designer">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Participant</button>
                <a href="{{ route('participants.index') }}" class="ml-3 text-gray-600">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        document.querySelectorAll('.project-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const details = this.closest('.mb-4').querySelector('.project-details');
                details.style.display = this.checked ? 'block' : 'none';
            });
            
            // Trigger change on page load to set initial state
            checkbox.dispatchEvent(new Event('change'));
        });
    </script>
@endsection