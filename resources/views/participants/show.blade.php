@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200">
        <h1 class="text-2xl font-semibold text-gray-800">Participant Details</h1>
        <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
            ID: {{ $participant->participant_id }}
        </span>
    </div>

    <!-- Details Table -->
    <div class="overflow-hidden rounded-lg border border-gray-200 mb-6">
        <table class="min-w-full divide-y divide-gray-200">
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-700 bg-gray-50">Full Name</th>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $participant->full_name }}</td>
                </tr>
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-700 bg-gray-50">Email</th>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $participant->email ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-700 bg-gray-50">Affiliation</th>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $participant->affiliation }}</td>
                </tr>
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-700 bg-gray-50">Specialization</th>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $participant->specialization }}</td>
                </tr>
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-700 bg-gray-50">Cross-Skill Trained</th>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        @if($participant->cross_skill_trained)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Yes
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                No
                            </span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-700 bg-gray-50">Institution</th>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $participant->institution }}</td>
                </tr>
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-700 bg-gray-50">Description</th>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $participant->description ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('participants.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm font-medium">
            Back to List
        </a>
        <a href="{{ route('participants.edit', $participant->participant_id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 text-sm font-medium">
            Edit
        </a>
        <a href="{{ route('participants.projects', $participant->participant_id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium">
            View Projects
        </a>
    </div>
</div>
@endsection