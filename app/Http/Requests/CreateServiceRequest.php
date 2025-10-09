<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Service;

class CreateServiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Required Fields Rule
            'facility_id' => 'required|exists:facilities,facility_id',
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:Machining,Testing,Training',
            'skill_type' => 'required|string|in:Hardware,Software,Integration',
            
            // Optional fields
            'description' => 'nullable|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Scoped Uniqueness Rule - Service name must be unique within a Facility
            $facilityId = $this->input('facility_id');
            $name = $this->input('name');
            
            if ($facilityId && $name) {
                $exists = Service::where('facility_id', $facilityId)
                                ->where('name', $name)
                                ->exists();
                
                if ($exists) {
                    $validator->errors()->add('name', 'A service with this name already exists in this facility.');
                }
            }
        });
    }

    public function messages()
    {
        return [
            'facility_id.required' => 'Service.FacilityId is required.',
            'name.required' => 'Service.Name is required.',
            'category.required' => 'Service.Category is required.',
            'skill_type.required' => 'Service.SkillType is required.',
            'facility_id.exists' => 'Service.FacilityId must reference a valid facility.',
            'category.in' => 'Service.Category must be one of: Machining, Testing, Training.',
            'skill_type.in' => 'Service.SkillType must be one of: Hardware, Software, Integration.',
        ];
    }
}