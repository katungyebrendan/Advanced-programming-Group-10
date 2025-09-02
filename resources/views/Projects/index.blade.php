@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">
        @if($facilityId)
            Projects under Facility ID: {{ $facilityId }}
        @elseif($programId)
            Projects under Program ID: {{ $programId }}
        @else
            All Projects
        @endif
    </h2>

    @if($projects->isEmpty())
        <p>No projects found.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Project ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Facility</th>
                    <th>Program</th>
                    <th>Participants</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>{{ $project->project_id }}</td>
                        <td>{{ $project->title }}</td>
                        <td>{{ $project->description }}</td>
                        <td>{{ optional($project->facility)->name ?? 'N/A' }}</td>
                        <td>{{ optional($project->program)->name ?? 'N/A' }}</td>
                        <td>
                            @if(!empty($project->participants))
                                <ul>
                                    @foreach($project->participants as $participant)
                                        <li>{{ $participant }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <span>No participants</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
