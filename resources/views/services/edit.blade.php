@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Service</h2>
    <form action="{{ route('services.update', $service->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Name</label>
            <input name="name" class="form-control" value="{{ $service->name }}" required>
        </div>
        <div class="mb-3">
            <label>Facility</label>
            <select name="facility_id" class="form-control" required>
                @foreach($facilities as $facility)
                    <option value="{{ $facility->id }}" {{ $service->facility_id == $facility->id ? 'selected' : '' }}>
                        {{ $facility->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $service->description }}</textarea>
        </div>
        <div class="mb-3">
            <label>Category</label>
            <input name="category" class="form-control" value="{{ $service->category }}">
        </div>
        <div class="mb-3">
            <label>Skill Type</label>
            <input name="skill_type" class="form-control" value="{{ $service->skill_type }}">
        </div>
        <button class="btn btn-success">Update</button>
        <a href="{{ route('services.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
