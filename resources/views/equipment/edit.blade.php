@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Edit Equipment</h1>

    <form action="{{ route('equipment.update', $equipment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-medium">Facility</label>
            <select name="facility_id" class="w-full border rounded p-2" required>
                @foreach($facilities as $facility)
                    <option value="{{ $facility->id }}" {{ $equipment->facilityId == $facility->id ? 'selected' : '' }}>
                        {{ $facility->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Name</label>
            <input type="text" name="name" class="w-full border rounded p-2" value="{{ $equipment->name }}" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Capabilities</label>
            <input type="text" name="capabilities" class="w-full border rounded p-2" value="{{ $equipment->capabilities }}">
        </div>

        <div class="mb-4">
            <label class="block font-medium">Description</label>
            <textarea name="description" class="w-full border rounded p-2" rows="3">{{ $equipment->description }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Inventory Code</label>
            <input type="text" name="inventory_code" class="w-full border rounded p-2" value="{{ $equipment->inventoryCode }}">
        </div>

        <div class="mb-4">
            <label class="block font-medium">Usage Domain</label>
            <select name="usage_domain" class="w-full border rounded p-2">
                <option value="">-- Select Domain --</option>
                @foreach($usageDomains as $domain)
                    <option value="{{ $domain }}" {{ $equipment->usageDomain == $domain ? 'selected' : '' }}>
                        {{ $domain }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Support Phase</label>
            <select name="support_phase" class="w-full border rounded p-2">
                <option value="">-- Select Phase --</option>
                @foreach($supportPhases as $phase)
                    <option value="{{ $phase }}" {{ $equipment->supportPhase == $phase ? 'selected' : '' }}>
                        {{ $phase }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('equipment.index') }}" class="ml-3 text-gray-600">Cancel</a>
        </div>
    </form>
</div>
@endsection
