@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Participant</h2>
    <form action="{{ route('participants.update', $participant->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Name</label>
            <input name="name" class="form-control" value="{{ $participant->name }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input name="email" class="form-control" type="email" value="{{ $participant->email }}">
        </div>
        <div class="mb-3">
            <label>Phone</label>
            <input name="phone" class="form-control" value="{{ $participant->phone }}">
        </div>
        <button class="btn btn-success">Update</button>
        <a href="{{ route('participants.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
