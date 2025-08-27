<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFacilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $facilityId = $this->route('facility'); // assumes route parameter is 'facility'
        
        return [
            'name' => 'required|string|max:255|unique:facilities,name,' . $facilityId . ',facility_id',
            'location' => 'required|string|max:500',
            'description' => 'required|string',
            'partner_organization' => 'nullable|string|in:UniPod,UIRI,Lwera',
            'facility_type' => 'required|string|in:Lab,Workshop,Testing Center,Maker Space',
            'capabilities' => 'nullable|array',
            'capabilities.*' => 'string|max:100'
        ];
    }
}
