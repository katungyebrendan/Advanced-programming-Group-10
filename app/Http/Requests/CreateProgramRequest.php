<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateProgramRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Required Fields Rule & Uniqueness Rule (case-insensitive)
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $exists = \App\Models\Program::whereRaw('LOWER(name) = LOWER(?)', [strtolower($value)])->exists();
                    if ($exists) {
                        $fail('Program.Name already exists.');
                    }
                }
            ],
            'description' => 'required|string',
            
            // Optional fields
            'national_alignment' => 'nullable|string',
            'focus_areas' => 'nullable|array',
            'phases' => 'nullable|array',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // National Alignment Rule
            $focusAreas = $this->input('focus_areas', []);
            $nationalAlignment = $this->input('national_alignment');
            
            if (!empty($focusAreas) && !empty($nationalAlignment)) {
                $validAlignments = ['NDPIII', 'DigitalRoadmap2023_2028', '4IR'];
                $alignmentTokens = explode(',', str_replace(' ', '', $nationalAlignment));
                
                $hasValidAlignment = false;
                foreach ($alignmentTokens as $token) {
                    if (in_array(trim($token), $validAlignments)) {
                        $hasValidAlignment = true;
                        break;
                    }
                }
                
                if (!$hasValidAlignment) {
                    $validator->errors()->add('national_alignment', 
                        'Program.NationalAlignment must include at least one recognized alignment when FocusAreas are specified.');
                }
            }
        });
    }

    public function messages()
    {
        return [
            'name.required' => 'Program.Name is required.',
            'description.required' => 'Program.Description is required.',
            'name.unique' => 'Program.Name already exists.',
        ];
    }
}