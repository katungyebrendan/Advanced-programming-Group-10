<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateFacilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Add your authorization logic here
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:facilities,name',
            'location' => 'required|string|max:500',
            'description' => 'required|string',
            'partner_organization' => 'nullable|string|in:UniPod,UIRI,Lwera',
            'facility_type' => 'required|string|in:Lab,Workshop,Testing Center,Maker Space',
            'capabilities' => 'nullable|array',
            'capabilities.*' => 'string|max:100'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Facility name is required',
            'name.unique' => 'A facility with this name already exists',
            'location.required' => 'Facility location is required',
            'description.required' => 'Facility description is required',
            'facility_type.required' => 'Facility type is required',
            'facility_type.in' => 'Invalid facility type selected',
            'partner_organization.in' => 'Invalid partner organization selected'
        ];
    }
}