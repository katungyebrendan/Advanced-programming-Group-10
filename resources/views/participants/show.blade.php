{{-- filepath: resources/views/participants/show.blade.php --}}
@extends('layouts.app')

@section('content')
<h2>Participant Details</h2>
<ul class="list-group">
    <li class="list-group-item"><strong>Name:</strong> {{ $participant->full_name }}</li>
    <li class="list-group-item"><strong>Email:</strong> {{ $participant->email }}</li>
    <li class="list-group-item"><strong>Affiliation:</strong> {{ $participant->affiliation }}</li>
    <li class="list-group-item"><strong>Institution:</strong> {{ $participant->institution }}</li>
    <li class="list-group-item"><strong>Specialization:</strong> {{ $participant->specialization }}</li>
    <li class="list-group-item"><strong>Cross-Skill Trained:</strong> {{ $participant->cross_skill_trained ? 'Yes' : 'No' }}</li>
</ul>
<a href="{{ route('participants.index') }}" class="btn btn-secondary mt-3">Back</a>
@endsection