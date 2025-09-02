@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Edit Facility</h1>

    <form action="{{ route('facilities.update', $facility->facility_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-medium">Name</label>
            <input type="text" name="name" class="w-full border rounded p-2" value="{{ $facility->name }}" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Location</label>
            <input type="text" name="location" class="w-full border rounded p-2" value="{{ $facility->location }}" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Description</label>
            <textarea name="description" class="w-full border rounded p-2" rows="3" required>{{ $facility->description }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Partner Organization</label>
            <select name="partner_organization" class="w-full border rounded p-2">
                <option value="">-- None --</option>
                <option value="UniPod" @if($facility->partner_organization == 'UniPod') selected @endif>UniPod</option>
                <option value="UIRI" @if($facility->partner_organization == 'UIRI') selected @endif>UIRI</option>
                <option value="Lwera" @if($facility->partner_organization == 'Lwera') selected @endif>Lwera</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Facility Type</label>
            <select name="facility_type" class="w-full border rounded p-2" required>
                <option value="Lab" @if($facility->facility_type == 'Lab') selected @endif>Lab</option>
                <option value="Workshop" @if($facility->facility_type == 'Workshop') selected @endif>Workshop</option>
                <option value="Testing Center" @if($facility->facility_type == 'Testing Center') selected @endif>Testing Center</option>
                <option value="Maker Space" @if($facility->facility_type == 'Maker Space') selected @endif>Maker Space</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Capabilities (comma separated)</label>
            {{-- This line is the fix. It checks if capabilities is an array before imploding. --}}
            <input type="text" name="capabilities" class="w-full border rounded p-2" value="{{ is_array($facility->capabilities) ? implode(', ', $facility->capabilities) : $facility->capabilities ?? '' }}">
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Update</button>
        <a href="{{ route('facilities.index') }}" class="ml-3 text-gray-600">Cancel</a>
    </form>
</div>
@endsection