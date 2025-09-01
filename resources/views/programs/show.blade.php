@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $program->name }}</h1>
    <p><strong>National alignment:</strong> {{ $program->national_alignment }}</p>
    <p><strong>Description:</strong><br>{{ $program->description }}</p>
    <p><strong>Focus areas:</strong> {{ is_array($program->focus_areas) ? implode(', ', $program->focus_areas) : $program->focus_areas }}</p>
    <p><strong>Phases:</strong> {{ is_array($program->phases) ? implode(', ', $program->phases) : $program->phases }}</p>

    <a href="{{ route('programs.edit', $program) }}" class="btn btn-primary">Edit</a>
    <a href="{{ route('programs.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
