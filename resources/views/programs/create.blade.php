@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Register New Program</h1>

    {{-- Display validation errors --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Display error message --}}
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('programs.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label for="name" class="block font-medium text-gray-700 mb-1">Name *</label>
            <input type="text" 
                   id="name"
                   name="name" 
                   value="{{ old('name') }}"
                   class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                   required>
        </div>

        <div class="mb-4">
            <label for="description" class="block font-medium text-gray-700 mb-1">Description</label>
            <textarea id="description"
                      name="description" 
                      class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                      rows="3">{{ old('description') }}</textarea>
        </div>

        <div class="mb-4">
            <label for="national_alignment" class="block font-medium text-gray-700 mb-1">National Alignment</label>
            <input type="text" 
                   id="national_alignment"
                   name="national_alignment" 
                   value="{{ old('national_alignment') }}"
                   class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>

        <div class="mb-4">
            <label for="focus_areas" class="block font-medium text-gray-700 mb-1">Focus Areas</label>
            <select id="focus_areas"
                    name="focus_areas[]" 
                    multiple 
                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    style="min-height: 120px;">
                @foreach($focusOptions as $focus)
                    <option value="{{ $focus }}" 
                            {{ in_array($focus, old('focus_areas', [])) ? 'selected' : '' }}>
                        {{ $focus }}
                    </option>
                @endforeach
            </select>
            <small class="text-gray-500">Hold Ctrl (Cmd on Mac) to select multiple options.</small>
        </div>

        <div class="mb-4">
            <label for="phases" class="block font-medium text-gray-700 mb-1">Phases</label>
            <select id="phases"
                    name="phases[]" 
                    multiple 
                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    style="min-height: 120px;">
                @foreach($phaseOptions as $phase)
                    <option value="{{ $phase }}"
                            {{ in_array($phase, old('phases', [])) ? 'selected' : '' }}>
                        {{ $phase }}
                    </option>
                @endforeach
            </select>
            <small class="text-gray-500">Hold Ctrl (Cmd on Mac) to select multiple options.</small>
        </div>

        <div class="flex gap-3">
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                Save Program
            </button>
            <a href="{{ route('programs.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md font-medium transition-colors">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection