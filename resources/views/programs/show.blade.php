@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">{{ $program->name }}</h1>

    <p><strong>Description:</strong> {{ $program->description }}</p>
    <p class="mt-2"><strong>National Alignment:</strong> {{ $program->national_alignment }}</p>
    <p class="mt-2"><strong>Focus Areas:</strong> 
        {{ is_array($program->focus_areas) ? implode(', ', $program->focus_areas) : $program->focus_areas }}
    </p>
    <p class="mt-2"><strong>Phases:</strong> 
        {{ is_array($program->phases) ? implode(', ', $program->phases) : $program->phases }}
    </p>

    <div class="mt-6 space-x-3">
        <a href="{{ route('programs.edit', $program) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Edit</a>
        <a href="{{ route('programs.index') }}" class="text-gray-600">Back</a>
    </div>
</div>
@endsection
