@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen p-4 bg-gray-100 dark:bg-gray-900">
    <div class="w-full max-w-lg p-8 space-y-6 bg-white border border-gray-200 rounded-xl shadow-xl dark:bg-gray-800 dark:border-gray-700">
        <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white">Add New Service</h2>
        <p class="text-center text-gray-600 dark:text-gray-400">Fill out the form below to create a new service record.</p>

        <form action="{{ route('services.store') }}" method="POST" class="space-y-5">
            @csrf
            
            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                <input type="text" id="name" name="name" class="w-full px-4 py-2 text-gray-900 transition-colors duration-200 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- Facility Select Field -->
            <div>
                <label for="facility" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Facility</label>
                <select id="facility" name="facility_id" class="w-full px-4 py-2 text-gray-900 transition-colors duration-200 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Select a facility</option>
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Description Field -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                <textarea id="description" name="description" rows="3" class="w-full px-4 py-2 text-gray-900 transition-colors duration-200 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <!-- Category Field -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                <input type="text" id="category" name="category" class="w-full px-4 py-2 text-gray-900 transition-colors duration-200 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Skill Type Field -->
            <div>
                <label for="skill_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Skill Type</label>
                <input type="text" id="skill_type" name="skill_type" class="w-full px-4 py-2 text-gray-900 transition-colors duration-200 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-4 space-x-4">
                <a href="{{ route('services.index') }}" class="flex-1 px-4 py-2 text-sm font-medium text-center text-white transition-colors duration-200 bg-red-600 rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Cancel
                </a>
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium text-center text-white transition-colors duration-200 bg-blue-600 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
