@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">{{ $equipment->name }}</h1>

    <div class="mb-3">
        <strong>Facility:</strong>
        {{ $equipment->facility ? $equipment->facility->name : 'N/A' }}
    </div>

    <div class="mb-3">
        <strong>Capabilities:</strong>
        {{ $equipment->capabilities ?? '-' }}
    </div>

    <div class="mb-3">
        <strong>Description:</strong>
        {{ $equipment->description ?? '-' }}
    </div>

    <div class="mb-3">
        <strong>Inventory Code:</strong>
        {{ $equipment->inventory_code ?? '-' }}
    </div>

    <div class="mb-3">
        <strong>Usage Domain:</strong>
        {{ $equipment->usage_domain ?? '-' }}
    </div>

    <div class="mb-3">
        <strong>Support Phase:</strong>
        {{ $equipment->support_phase ?? '-' }}
    </div>

    <div class="mt-6 space-x-3">
        <a href="{{ route('equipment.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Back</a>
        <a href="{{ route('equipment.edit', $equipment) }}" class="bg-yellow-500 text-white px-4 py-2 rounded">Edit</a>
        <form action="{{ route('equipment.destroy', $equipment) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Delete this equipment?')" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
        </form>
    </div>
</div>
@endsection
