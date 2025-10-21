@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Equipment List</h1>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('equipment.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Register Equipment</a>
    </div>

    <table class="w-full border-collapse border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Facility</th>
                <th class="border px-4 py-2">Capabilities</th>
                <th class="border px-4 py-2">Usage Domain</th>
                <th class="border px-4 py-2">Support Phase</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($equipments as $item)
                <tr>
                    <td class="border px-4 py-2">{{ $item->name }}</td>
                    <td class="border px-4 py-2">
                        Facility ID: {{ $item->facilityId }}
                    </td>
                    <td class="border px-4 py-2">{{ $item->capabilities ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ $item->usageDomain ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ $item->supportPhase ?? '-' }}</td>
                    <td class="border px-4 py-2 space-x-2">
                        <a href="{{ route('equipment.show', $item->id) }}" class="text-blue-600 hover:underline">View</a>
                        <a href="{{ route('equipment.edit', $item->id) }}" class="text-yellow-600 hover:underline">Edit</a>
                        <form action="{{ route('equipment.destroy', $item->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this equipment?')" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <td colspan="6" class="border px-4 py-2 text-center text-gray-500">No equipment registered yet.</td>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
