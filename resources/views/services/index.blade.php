@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">
        @if(!empty($facilityId))
            Services under Facility ID: {{ $facilityId }}
        @else
            All Services
        @endif
    </h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('services.create') }}" class="btn btn-success mb-3">Add New Service</a>

    @if($services->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Facility</th>
                    <th>Category</th>
                    <th>Skill Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                    <tr>
                        <td>{{ $service->id }}</td>
                        <td>{{ $service->name }}</td>
                        <td>{{ $service->facility->name ?? 'N/A' }}</td>
                        <td>{{ $service->category ?? '-' }}</td>
                        <td>{{ $service->skill_type ?? '-' }}</td>
                        <td>
                            <a href="{{ route('services.show', $service->id) }}" class="btn btn-sm btn-primary">View</a>
                            <a href="{{ route('services.edit', $service->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('services.destroy', $service->id) }}" method="POST" style="display:inline-block;">
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
        <p>No services found.</p>
    @endif
</div>
@endsection
