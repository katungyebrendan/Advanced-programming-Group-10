@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Programs</h1>
        <a href="{{ route('programs.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
            Add New Program
        </a>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Programs Grid --}}
    @if($programs->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($programs as $program)
                <div class="bg-white rounded-lg shadow-md p-6 border">
                    <h3 class="text-lg font-semibold mb-2">{{ $program->name }}</h3>
                    
                    @if($program->description)
                        <p class="text-gray-600 mb-3 text-sm">
                            {{ Str::limit($program->description, 100) }}
                        </p>
                    @endif

                    @if($program->national_alignment)
                        <p class="text-sm text-blue-600 mb-2">
                            <strong>Alignment:</strong> {{ $program->national_alignment }}
                        </p>
                    @endif

                    @if($program->focus_areas && count($program->focus_areas) > 0)
                        <div class="mb-2">
                            <strong class="text-sm">Focus Areas:</strong>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach($program->focus_areas as $area)
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                                        {{ $area }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($program->phases && count($program->phases) > 0)
                        <div class="mb-4">
                            <strong class="text-sm">Phases:</strong>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach($program->phases as $phase)
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">
                                        {{ $phase }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex gap-2 mt-4">
                        <a href="{{ route('programs.show', $program->program_id) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View
                        </a>
                        <a href="{{ route('programs.edit', $program->program_id) }}" 
                           class="text-green-600 hover:text-green-800 text-sm font-medium">
                            Edit
                        </a>
                        @if($program->canBeDeleted())
                            <form action="{{ route('programs.destroy', $program->program_id) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this program?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $programs->links() }}
        </div>
    @else
        <div class="text-center py-8">
            <p class="text-gray-500 mb-4">No programs found.</p>
            <a href="{{ route('programs.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                Create Your First Program
            </a>
        </div>
    @endif
</div>
@endsection