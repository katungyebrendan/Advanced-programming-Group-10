@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Facility Details</h1>

    <div class="mb-4">
        <h2 class="text-lg font-semibold">Name</h2>
        <p>{{ $facility->name }}</p>
    </div>

    <div class="mb-4">
        <h2 class="text-lg font-semibold">Location</h2>
        <p>{{ $facility->location }}</p>
    </div>

    <div class="mb-4">
        <h2 class="text-lg font-semibold">Description</h2>
        <p>{{ $facility->description }}</p>
    </div>

    <div class="mb-4">
        <h2 class="text-lg font-semibold">Partner Organization</h2>
        <p>{{ $facility->partner_organization ?? 'N/A' }}</p>
    </div>

    <div class="mb-4">
        <h2 class="text-lg font-semibold">Facility Type</h2>
        <p>{{ $facility->facility_type }}</p>
    </div>

    <div class="mb-4">
        <h2 class="text-lg font-semibold">Capabilities</h2>
        {{-- This is the corrected line. It checks if capabilities is an array before imploding it. --}}
        <p>{{ is_array($facility->capabilities) ? implode(', ', $facility->capabilities) : $facility->capabilities ?? 'N/A' }}</p>
    </div>

    <div class="mt-6">
        <a href="{{ route('facilities.edit', $facility->facility_id) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Edit Facility</a>
        <a href="{{ route('facilities.index') }}" class="ml-3 text-gray-600">Back to List</a>
    </div>
</div>
@endsection