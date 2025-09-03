@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">
        @if(!empty($facilityId))
            Projects under Facility ID: {{ $facilityId }}
        @elseif(!empty($programId))
            Projects under Program ID: {{ $programId }}
        @else
            All Projects
        @endif
    </h2>

    @if($projects->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Program</th>
                    <th>Facility</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>{{ $project->id }}</td>
                        <td>{{ $project->title }}</td>
                        <td>{{ $project->program->name ?? 'N/A' }}</td>
                        <td>{{ $project->facility->name ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-sm btn-primary">View</a>
                            <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No projects found.</p>
    @endif
</div>
@endsection
