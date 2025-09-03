{{-- filepath: resources/views/services/show.blade.php --}}
@extends('layouts.app')

@section('content')
<h2>Service Details</h2>
<ul class="list-group">
    <li class="list-group-item"><strong>Name:</strong> {{ $service->name }}</li>
    <li class="list-group-item"><strong>Facility:</strong> {{ $service->facility->name ?? '' }}</li>
    <li class="list-group-item"><strong>Description:</strong> {{ $service->description }}</li>
    <li class="list-group-item"><strong>Category:</strong> {{ $service->category }}</li>
    <li class="list-group-item"><strong>Skill Type:</strong> {{ $service->skill_type }}</li>
</ul>
<a href="{{ route('services.index') }}" class="btn btn-secondary mt-3">Back</a>
@endsection