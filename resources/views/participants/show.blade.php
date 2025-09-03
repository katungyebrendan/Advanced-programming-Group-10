@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Participant Details</h2>
    <table class="table table-bordered">
        <tr><th>ID</th><td>{{ $participant->id }}</td></tr>
        <tr><th>Name</th><td>{{ $participant->name }}</td></tr>
        <tr><th>Email</th><td>{{ $participant->email ?? '-' }}</td></tr>
        <tr><th>Phone</th><td>{{ $participant->phone ?? '-' }}</td></tr>
    </table>
    <a href="{{ route('participants.index') }}" class="btn btn-secondary">Back</a>
    <a href="{{ route('participants.edit', $participant->id) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('participants.projects', $participant->id) }}" class="btn btn-info">Projects</a>
</div>
@endsection
