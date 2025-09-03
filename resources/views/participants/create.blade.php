{{-- filepath: resources/views/participants/create.blade.php --}}
@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Add Participant</h2>

@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('participants.store') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="block font-semibold">Full Name</label>
        <input type="text" name="full_name" class="form-control w-full" required>
    </div>
    <div>
        <label class="block font-semibold">Email</label>
        <input type="email" name="email" class="form-control w-full" required>
    </div>
    <div>
        <label class="block font-semibold">Affiliation</label>
        <input type="text" name="affiliation" class="form-control w-full">
    </div>
    <div>
        <label class="block font-semibold">Institution</label>
        <input type="text" name="institution" class="form-control w-full">
    </div>
    <div>
        <label class="block font-semibold">Specialization</label>
        <input type="text" name="specialization" class="form-control w-full">
    </div>
    <div>
        <label class="block font-semibold">Cross-Skill Trained</label>
        <select name="cross_skill_trained" class="form-control w-full">
            <option value="0">No</option>
            <option value="1">Yes</option>
        </select>
    </div>
    <div class="flex space-x-2">
        <button class="btn btn-success bg-green-600 text-white px-4 py-2 rounded" type="submit">Save</button>
        <a href="{{ route('participants.index') }}" class="btn btn-secondary bg-gray-400 text-white px-4 py-2 rounded">Cancel</a>
    </div>
</form>