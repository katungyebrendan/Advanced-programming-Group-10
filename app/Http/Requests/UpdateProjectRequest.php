<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project;

class UpdateProjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Accept program/facility names like the create flow; resolve to IDs in withValidator()
            'program_name' => 'required|string|exists:programs,name',
            'facility_name' => 'required|string|exists:facilities,name',
            'title' => 'required|string|max:255',
            
            // Optional fields
            'nature_of_project' => 'nullable|string|in:Research,Prototype,Applied Work',
            'description' => 'nullable|string',
            'innovation_focus' => 'nullable|string',
            'prototype_stage' => 'nullable|string|in:Concept,Prototype,MVP,Market Launch',
            'testing_requirements' => 'nullable|string',
            'commercialization_plan' => 'nullable|string',
            'status' => 'nullable|string|in:Planning,Active,Completed,On Hold',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Resolve names to IDs
            $programName = $this->input('program_name');
            $facilityName = $this->input('facility_name');

            if ($programName) {
                $program = \App\Models\Program::where('name', $programName)->first();
                if (!$program) {
                    $validator->errors()->add('program_name', 'Program not found.');
                    return;
                }
                $this->merge(['program_id' => $program->program_id]);
            }

            if ($facilityName) {
                $facility = \App\Models\Facility::where('name', $facilityName)->first();
                if (!$facility) {
                    $validator->errors()->add('facility_name', 'Facility not found.');
                    return;
                }
                $this->merge(['facility_id' => $facility->facility_id]);
            }
            // Name Uniqueness Rule - Project name must be unique within a Program (excluding current record)
            $programId = $this->input('program_id');
            $title = $this->input('title');
            $projectId = $this->route('project') ? $this->route('project')->project_id : $this->route('id');
            
            if ($programId && $title && $projectId) {
                $exists = Project::where('program_id', $programId)
                                ->where('title', $title)
                                ->where('project_id', '!=', $projectId)
                                ->exists();
                
                if ($exists) {
                    $validator->errors()->add('title', 'A project with this name already exists in this program.');
                }
            }

            // Team Tracking Rule - Project must have at least one team member assigned
            if ($projectId) {
                $project = Project::find($projectId);
                if ($project && $project->participants()->count() === 0) {
                    $validator->after(function ($validator) {
                        $validator->getMessageBag()->add('warning', 'Project must have at least one team member assigned.');
                    });
                }
            }

            // Outcome Validation Rule - If Status is 'Completed', at least one Outcome must be attached
            $status = $this->input('status');
            if ($status === 'Completed' && $projectId) {
                $project = Project::find($projectId);
                if ($project && $project->outcomes()->count() === 0) {
                    $validator->errors()->add('status', 'Completed projects must have at least one documented outcome.');
                }
            }

            // Facility Compatibility Rule
            $facilityId = $this->input('facility_id');
            $testingRequirements = $this->input('testing_requirements');
            
            if ($facilityId && $testingRequirements) {
                $facility = \App\Models\Facility::find($facilityId);
                if ($facility && empty($facility->capabilities)) {
                    $validator->errors()->add('facility_id', 
                        'Project requirements not compatible with facility capabilities.');
                }
            }
        });
    }

    public function messages()
    {
        return [
            'program_name.required' => 'Program name is required.',
            'program_name.exists' => 'Selected program does not exist.',
            'facility_name.required' => 'Facility name is required.',
            'facility_name.exists' => 'Selected facility does not exist.',
            'title.required' => 'Project.Title is required.',
            'nature_of_project.in' => 'Invalid nature of project selected.',
            'prototype_stage.in' => 'Invalid prototype stage selected.',
            'status.in' => 'Invalid status selected.',
        ];
    }
}