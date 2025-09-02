@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Facilities</h1>

    {{-- Link to the facility creation page --}}
    <a href="{{ route('facilities.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">+ Add Facility</a>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <table class="table-auto border-collapse border border-gray-400 w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Location</th>
                <th class="border px-4 py-2">Type</th>
                <th class="border px-4 py-2">Partner</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($facilities as $facility)
                <tr>
                    <td class="border px-4 py-2">{{ $facility->facility_id }}</td>
                    <td class="border px-4 py-2">{{ $facility->name }}</td>
                    <td class="border px-4 py-2">{{ $facility->location }}</td>
                    <td class="border px-4 py-2">{{ $facility->facility_type }}</td>
                    <td class="border px-4 py-2">{{ $facility->partner_organization ?? '-' }}</td>
                    <td class="border px-4 py-2 space-x-2">
                        {{-- View, Edit, and Delete links/forms --}}
                        <a href="{{ route('facilities.show', $facility->facility_id) }}" class="text-blue-600">View</a>
                        <a href="{{ route('facilities.edit', $facility->facility_id) }}" class="text-yellow-600">Edit</a>
                        
                        {{-- Delete form --}}
                        <form action="{{ route('facilities.destroy', $facility->facility_id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this facility?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-gray-500 py-4">No facilities found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection