@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Register New Equipment</h1>

    <form action="{{ route('equipment.store') }}" method="POST">
        @csrf

        {{-- Facility Dropdown --}}
        <div class="mb-4">
            <label class="block font-medium">Facility</label>
            <select name="facility_id" class="w-full border rounded p-2" required>
                <option value="">-- Select Facility --</option>
                @foreach($facilities as $facility)
                    <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Equipment Name --}}
        <div class="mb-4">
            <label class="block font-medium">Name</label>
            <input type="text" name="name" class="w-full border rounded p-2" required>
        </div>

        {{-- Capabilities --}}
        <div class="mb-4">
            <label class="block font-medium">Capabilities</label>
            <input type="text" name="capabilities" class="w-full border rounded p-2">
        </div>

        {{-- Description --}}
        <div class="mb-4">
            <label class="block font-medium">Description</label>
            <textarea name="description" class="w-full border rounded p-2" rows="3"></textarea>
        </div>

        {{-- Inventory Code --}}
        <div class="mb-4">
            <label class="block font-medium">Inventory Code</label>
            <input type="text" name="inventory_code" class="w-full border rounded p-2">
        </div>

        {{-- Usage Domain --}}
        <div class="mb-4">
            <label class="block font-medium">Usage Domain</label>
            <select name="usage_domain" class="w-full border rounded p-2">
                <option value="">-- Select Domain --</option>
                @foreach($usageDomains as $domain)
                    <option value="{{ $domain }}">{{ $domain }}</option>
                @endforeach
            </select>
        </div>

        {{-- Support Phase --}}
        <div class="mb-4">
            <label class="block font-medium">Support Phase</label>
            <select name="support_phase" class="w-full border rounded p-2">
                <option value="">-- Select Phase --</option>
                @foreach($supportPhases as $phase)
                    <option value="{{ $phase }}">{{ $phase }}</option>
                @endforeach
            </select>
        </div>

        {{-- Submit --}}
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
        <a href="{{ route('equipment.index') }}" class="ml-3 text-gray-600">Cancel</a>
    </form>
</div>
@endsection
