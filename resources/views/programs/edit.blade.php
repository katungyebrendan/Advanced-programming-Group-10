@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Edit Program</h1>

    <form action="{{ route('programs.update', $program) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-medium">Name</label>
            <input type="text" name="name" 
                   value="{{ old('name', $program->name) }}" 
                   class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Description</label>
            <textarea name="description" 
                      class="w-full border rounded p-2" rows="3">{{ old('description', $program->description) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block font-medium">National Alignment</label>
            <input type="text" name="national_alignment" 
                   value="{{ old('national_alignment', $program->national_alignment) }}" 
                   class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium">Focus Areas (comma separated)</label>
            <input type="text" name="focus_areas" 
                   value="{{ old('focus_areas', is_array($program->focus_areas) ? implode(', ', $program->focus_areas) : '') }}" 
                   class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium">Phases (comma separated)</label>
            <input type="text" name="phases" 
                   value="{{ old('phases', is_array($program->phases) ? implode(', ', $program->phases) : '') }}" 
                   class="w-full border rounded p-2">
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Update</button>
        <a href="{{ route('programs.index') }}" class="ml-3 text-gray-600">Cancel</a>
    </form>
</div>
@endsection
