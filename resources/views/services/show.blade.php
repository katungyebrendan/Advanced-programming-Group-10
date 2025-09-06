@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800">Service Details</h1>
        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
            ID: {{ $service->service_id }}
        </span>
    </div>

    <!-- Service Details -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Basic Information -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <h2 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-2">Basic Information</h2>
            <div class="space-y-3">
                <div>
                    <span class="text-sm font-medium text-gray-500">Name</span>
                    <p class="text-gray-900">{{ $service->name }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Facility</span>
                    <p class="text-gray-900">{{ $service->facility->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Description</span>
                    <p class="text-gray-900">{{ $service->description ?? 'No description provided' }}</p>
                </div>
            </div>
        </div>

        <!-- Service Specifications -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <h2 class="text-lg font-semibold text-gray-700 mb-3 border-b pb-2">Service Specifications</h2>
            <div class="space-y-3">
                <div>
                    <span class="text-sm font-medium text-gray-500">Category</span>
                    <p class="text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $service->category ?? '-' }}
                        </span>
                    </p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Skill Type</span>
                    <p class="text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $service->skill_type ?? '-' }}
                        </span>
                    </p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-500">Facility ID</span>
                    <p class="text-gray-900">{{ $service->facility_id }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-3">
        <a href="{{ route('services.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
            Back to Services
        </a>
        <a href="{{ route('services.edit', $service->service_id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
            Edit Service
        </a>
    </div>
</div>
@endsection