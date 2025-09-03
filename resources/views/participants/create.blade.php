@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Participant</h2>
    <form action="{{ route('participants.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input name="email" class="form-control" type="email">
        </div>
        <div class="mb-3">
            <label>Phone</label>
            <input name="phone" class="form-control">
        </div>
        <button class="btn btn-success">Save</button>
        <a href="{{ route('participants.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
