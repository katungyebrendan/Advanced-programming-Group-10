<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Equipment;

class UpdateEquipmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $equipmentId = $this->route('equipment') ? $this->route('equipment')->id : $this->route('id');
        
        return [
            // Required Fields Rule
            'facility_id' => 'required|exists:facilities,facility_id',
            'name' => 'required|string|max:255',
            'inventory_code' => [
                'required',
                'string',
                'max:100',
                'unique:equipment,inventory_code,' . $equipmentId
            ],
            
            // Optional fields
            'description' => 'nullable|string',
            'capabilities' => 'nullable|string',
            'usage_domain' => 'nullable|string|in:Electronics,Mechanical,Software,General',
            'support_phase' => 'nullable|string',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Usage Domain-Support Phase Coherence Rule
            $usageDomain = $this->input('usage_domain');
            $supportPhase = $this->input('support_phase');
            
            if ($usageDomain === 'Electronics' && !empty($supportPhase)) {
                $supportPhases = array_map('trim', explode(',', $supportPhase));
                $validPhases = ['Prototyping', 'Testing'];
                
                $hasValidPhase = false;
                foreach ($supportPhases as $phase) {
                    if (in_array($phase, $validPhases)) {
                        $hasValidPhase = true;
                        break;
                    }
                }
                
                // Check if it's Training only
                $isTrainingOnly = count($supportPhases) === 1 && in_array('Training', $supportPhases);
                
                if ($isTrainingOnly || !$hasValidPhase) {
                    $validator->errors()->add('support_phase', 
                        'Electronics equipment must support Prototyping or Testing.');
                }
            }
        });
    }

    public function messages()
    {
        return [
            'facility_id.required' => 'Equipment.FacilityId is required.',
            'name.required' => 'Equipment.Name is required.',
            'inventory_code.required' => 'Equipment.InventoryCode is required.',
            'facility_id.exists' => 'Equipment.FacilityId must reference a valid facility.',
            'inventory_code.unique' => 'Equipment.InventoryCode already exists.',
        ];
    }
}