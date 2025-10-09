<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Facility;

class UpdateFacilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $facilityId = $this->route('facility') ? $this->route('facility')->facility_id : $this->route('id');
        
        return [
            // Required Fields Rule
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'facility_type' => 'required|string|in:Lab,Workshop,Testing Center,Maker Space',
            
            // Optional fields
            'description' => 'nullable|string',
            'partner_organization' => 'nullable|string|in:UniPod,UIRI,Lwera',
            'capabilities' => 'nullable|array',
            'capabilities.*' => 'string|max:100'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Uniqueness Rule - combination of Name and Location (excluding current record)
            $name = $this->input('name');
            $location = $this->input('location');
            $facilityId = $this->route('facility') ? $this->route('facility')->facility_id : $this->route('id');
            
            if ($name && $location && $facilityId) {
                $exists = Facility::where('name', $name)
                                ->where('location', $location)
                                ->where('facility_id', '!=', $facilityId)
                                ->exists();
                
                if ($exists) {
                    $validator->errors()->add('name', 'A facility with this name already exists at this location.');
                }
            }

            // Capabilities Rule - check if services/equipment exist and require capabilities
            if ($facilityId) {
                $facility = Facility::find($facilityId);
                if ($facility) {
                    $hasServices = $facility->services()->count() > 0;
                    $hasEquipment = $facility->equipment()->count() > 0;
                    $capabilities = $this->input('capabilities', []);
                    
                    if (($hasServices || $hasEquipment) && empty($capabilities)) {
                        $validator->errors()->add('capabilities', 
                            'Facility.Capabilities must be populated when Services/Equipment exist.');
                    }
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
