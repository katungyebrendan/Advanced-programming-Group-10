@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Facilities</h1>

    {{-- Link to the facility creation page --}}
    <a href="{{ route('facilities.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300 transform hover:scale-105 inline-block mb-6 shadow-lg">
        + Add Facility
    </a>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif
    
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-xl overflow-x-auto">
        {{-- Table Header --}}
        <div class="grid grid-cols-[auto_1.5fr_1.5fr_1fr_1.5fr_1fr] items-center px-6 py-3 bg-gray-200 text-gray-700 uppercase text-sm leading-normal font-semibold border-b border-gray-300 gap-x-4">
            <div>ID</div>
            <div>Name</div>
            <div>Location</div>
            <div>Type</div>
            <div>Partner</div>
            <div class="text-center">Actions</div>
        </div>
        
        {{-- Table Body --}}
        @forelse ($facilities as $facility)
            <div class="grid grid-cols-[auto_1.5fr_1.5fr_1fr_1.5fr_1fr] items-center px-6 py-4 border-b border-gray-200 hover:bg-gray-100 transition-colors duration-200 gap-x-4">
                <div>{{ $facility->facility_id }}</div>
                <div>{{ $facility->name }}</div>
                <div>{{ $facility->location }}</div>
                <div>{{ $facility->facility_type }}</div>
                <div>{{ $facility->partner_organization ?? '-' }}</div>
                <div class="text-center">
                    {{-- Action buttons with vertical stacking and professional design --}}
                    <div class="flex flex-col items-center space-y-2">
                        <a href="{{ route('facilities.show', $facility->facility_id) }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-4 rounded-lg transition duration-300 w-full text-center">
                            View
                        </a>
                        <a href="{{ route('facilities.edit', $facility->facility_id) }}" 
                           class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-1 px-4 rounded-lg transition duration-300 w-full text-center">
                            Edit
                        </a>
                        {{-- Direct form for delete button --}}
                        <form action="{{ route('facilities.destroy', $facility->facility_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this facility?');" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-4 rounded-lg transition duration-300 w-full text-center">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-4">No facilities found.</div>
        @endforelse
    </div>
</div>
@endsection
