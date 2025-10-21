<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Facility;

class CreateFacilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Required Fields Rule
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'facility_type' => 'required|string|in:Lab,Workshop,Testing Center,Maker Space',
            
            // Optional fields
            'description' => 'nullable|string',
            'partner_organization' => 'nullable|string|in:UniPod,UIRI,Lwera',
            // Accept capabilities as nullable free-form input (comma-separated string from the form)
            'capabilities' => 'nullable',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Uniqueness Rule - combination of Name and Location
            $name = $this->input('name');
            $location = $this->input('location');
            
            if ($name && $location) {
                $exists = Facility::where('name', $name)
                                ->where('location', $location)
                                ->exists();
                
                if ($exists) {
                    $validator->errors()->add('name', 'A facility with this name already exists at this location.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Facility.Name is required.',
            'location.required' => 'Facility.Location is required.',
            'facility_type.required' => 'Facility.FacilityType is required.',
            'facility_type.in' => 'Invalid facility type selected',
            'partner_organization.in' => 'Invalid partner organization selected'
        ];
    }
}