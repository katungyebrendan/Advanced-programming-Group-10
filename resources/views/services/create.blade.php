@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Service</h2>
    <form action="{{ route('services.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Facility</label>
            <select name="facility_id" class="form-control" required>
                @foreach($facilities as $facility)
                    <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>Category</label>
            <input name="category" class="form-control">
        </div>
        <div class="mb-3">
            <label>Skill Type</label>
            <input name="skill_type" class="form-control">
        </div>
        <button class="btn btn-success">Save</button>
        <a href="{{ route('services.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
