@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Programs</h1>

    <a href="{{ route('programs.create') }}" class="btn btn-success mb-3">New Program</a>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>National Alignment</th>
                <th>Focus Areas</th>
                <th>Phases</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($programs as $p)
            <tr>
                <td><a href="{{ route('programs.show', $p) }}">{{ $p->name }}</a></td>
                <td>{{ $p->national_alignment }}</td>
                <td>{{ is_array($p->focus_areas) ? implode(', ', $p->focus_areas) : $p->focus_areas }}</td>
                <td>{{ is_array($p->phases) ? implode(', ', $p->phases) : $p->phases }}</td>
                <td style="white-space:nowrap">
                    <a class="btn btn-sm btn-primary" href="{{ route('programs.edit', $p) }}">Edit</a>

                    <form action="{{ route('programs.destroy', $p) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Delete this program?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5">No programs yet.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $programs->links() }}
</div>
@endsection
