@extends('layouts.app')

@section('content')
<div class="container">
    <h2>All Participants</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('participants.create') }}" class="btn btn-success mb-3">Add Participant</a>

    @if($participants->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($participants as $participant)
                    <tr>
                        <td>{{ $participant->id }}</td>
                        <td>{{ $participant->name }}</td>
                        <td>{{ $participant->email ?? '-' }}</td>
                        <td>{{ $participant->phone ?? '-' }}</td>
                        <td>
                            <a href="{{ route('participants.show', $participant->id) }}" class="btn btn-sm btn-primary">View</a>
                            <a href="{{ route('participants.edit', $participant->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('participants.destroy', $participant->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                            <a href="{{ route('participants.projects', $participant->id) }}" class="btn btn-sm btn-info">Projects</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No participants found.</p>
    @endif
</div>
@endsection
