<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Participant;

class CreateParticipantRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Required Fields Rule
            'full_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    $exists = Participant::whereRaw('LOWER(email) = LOWER(?)', [strtolower($value)])->exists();
                    if ($exists) {
                        $fail('Participant.Email already exists.');
                    }
                }
            ],
            'affiliation' => 'required|string|in:CS,SE,Engineering,Other',
            
            // Optional fields
            'specialization' => 'nullable|string|in:Software,Hardware,Business',
            'cross_skill_trained' => 'nullable|boolean',
            'institution' => 'nullable|string|in:SCIT,CEDAT,UniPod,UIRI,Lwera',
            'description' => 'nullable|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Specialization Requirement Rule - Cross Skill Trained can only be true if Specialization is set
            $crossSkillTrained = $this->input('cross_skill_trained');
            $specialization = $this->input('specialization');
            
            if ($crossSkillTrained && empty($specialization)) {
                $validator->errors()->add('cross_skill_trained', 'Cross-skill flag requires Specialization.');
            }
        });
    }

    public function messages()
    {
        return [
            'full_name.required' => 'Participant.FullName is required.',
            'email.required' => 'Participant.Email is required.',
            'affiliation.required' => 'Participant.Affiliation is required.',
            'email.email' => 'Participant.Email must be a valid email address.',
            'affiliation.in' => 'Participant.Affiliation must be one of: CS, SE, Engineering, Other.',
            'specialization.in' => 'Participant.Specialization must be one of: Software, Hardware, Business.',
            'institution.in' => 'Participant.Institution must be one of: SCIT, CEDAT, UniPod, UIRI, Lwera.',
        ];
    }
}