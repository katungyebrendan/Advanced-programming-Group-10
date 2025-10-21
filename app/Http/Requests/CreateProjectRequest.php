<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project;

class CreateProjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Accept program and facility names instead of IDs from the interface
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
            // Resolve program and facility names to IDs for domain processing
            $programName = $this->input('program_name');
            $facilityName = $this->input('facility_name');
            
            if ($programName) {
                $program = \App\Models\Program::where('name', $programName)->first();
                if (!$program) {
                    $validator->errors()->add('program_name', 'Program not found.');
                    return;
                }
                // Store resolved IDs for controller use
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

            // Name Uniqueness Rule - Project name must be unique within a Program
            $programId = $this->input('program_id');
            $title = $this->input('title');
            
            if ($programId && $title) {
                $exists = Project::where('program_id', $programId)
                                ->where('title', $title)
                                ->exists();
                
                if ($exists) {
                    $validator->errors()->add('title', 'A project with this name already exists in this program.');
                }
            }
        });
    }

    public function messages()
    {
        return [
            'program_name.required' => 'Program name is required.',
            'facility_name.required' => 'Facility name is required.',
            'facility_name.exists' => 'Selected facility does not exist.',
            'title.required' => 'Project.Title is required.',
            'program_name.exists' => 'Selected program does not exist.',
            'nature_of_project.in' => 'Invalid nature of project selected.',
            'prototype_stage.in' => 'Invalid prototype stage selected.',
            'status.in' => 'Invalid status selected.',
        ];
    }
}