@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Participants</h1>

    <a href="{{ route('participants.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">+ Add Participant</a>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <table class="table-auto border-collapse border border-gray-400 w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Full Name</th>
                <th class="border px-4 py-2">Email</th>
                <th class="border px-4 py-2">Affiliation</th>
                <th class="border px-4 py-2">Specialization</th>
                <th class="border px-4 py-2">Cross-Skilled</th>
                <th class="border px-4 py-2">Institution</th>
                <th class="border px-4 py-2">Projects</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($participants as $participant)
                <tr>
                    <td class="border px-4 py-2">{{ $participant->participant_id }}</td>
                    <td class="border px-4 py-2">{{ $participant->full_name }}</td>
                    <td class="border px-4 py-2">{{ $participant->email ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ $participant->affiliation }}</td>
                    <td class="border px-4 py-2">{{ $participant->specialization }}</td>
                    <td class="border px-4 py-2 text-center">
                        @if($participant->cross_skill_trained)
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Yes</span>
                        @else
                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">No</span>
                        @endif
                    </td>
                    <td class="border px-4 py-2">{{ $participant->institution }}</td>
                    <td class="border px-4 py-2 text-center">{{ $participant->projects_count }}</td>
                    <td class="border px-4 py-2 space-x-2">
                        <a href="{{ route('participants.show', $participant->participant_id) }}" class="text-blue-600 hover:text-blue-800">View</a>
                        <a href="{{ route('participants.edit', $participant->participant_id) }}" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                        
                        <form action="{{ route('participants.destroy', $participant->participant_id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this participant?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                        </form>
                        
                        <a href="{{ route('participants.manage-projects', $participant->participant_id) }}" class="text-indigo-600 hover:text-indigo-800">Projects</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-gray-500 py-4">No participants found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- PAGINATION - Only works if you use paginate() in controller --}}
    @if($participants->hasPages())
        <div class="mt-4">
            {{ $participants->links() }}
        </div>
    @endif
</div>
@endsection