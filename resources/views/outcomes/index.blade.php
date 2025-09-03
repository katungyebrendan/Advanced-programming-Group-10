@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Outcomes for Project: {{ $project->title }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('projects.outcomes.create', $project->id) }}" class="btn btn-success mb-3">Add Outcome</a>

    @if($outcomes->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Artifact</th>
                    <th>Quality Cert.</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($outcomes as $outcome)
                    <tr>
                        <td>{{ $outcome->id }}</td>
                        <td>{{ $outcome->title }}</td>
                        <td>{{ $outcome->outcome_type ?? '-' }}</td>
                        <td>{{ $outcome->artifact_link ?? '-' }}</td>
                        <td>{{ $outcome->quality_certification ?? '-' }}</td>
                        <td>
                            <a href="{{ route('outcomes.show', $outcome->id) }}" class="btn btn-sm btn-primary">View</a>
                            <a href="{{ route('outcomes.edit', $outcome->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('outcomes.destroy', $outcome->id) }}" method="POST" style="display:inline-block;">
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
        <p>No outcomes found for this project.</p>
    @endif
</div>
@endsection
