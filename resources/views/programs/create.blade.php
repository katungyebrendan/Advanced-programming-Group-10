@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Register New Program</h1>

    <form action="{{ route('programs.store') }}" method="POST">
    @csrf

    <div class="mb-4">
        <label class="block font-medium">Name</label>
        <input type="text" name="name" class="w-full border rounded p-2" required>
    </div>

    <div class="mb-4">
        <label class="block font-medium">Description</label>
        <textarea name="description" class="w-full border rounded p-2" rows="3"></textarea>
    </div>

    <div class="mb-4">
        <label class="block font-medium">National Alignment</label>
        <input type="text" name="national_alignment" class="w-full border rounded p-2">
    </div>

    <div class="mb-4">
        <label class="block font-medium">Focus Areas (comma separated)</label>
        <input type="text" name="focus_areas" class="w-full border rounded p-2">
    </div>

    <div class="mb-4">
        <label class="block font-medium">Phases (comma separated)</label>
        <input type="text" name="phases" class="w-full border rounded p-2">
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
    <a href="{{ route('programs.index') }}" class="ml-3 text-gray-600">Cancel</a>
    </form>

</div>

<script>
document.getElementById('program-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    let data = Object.fromEntries(formData.entries());
    if (data.focus_areas) data.focus_areas = data.focus_areas.split(',').map(f => f.trim());
    if (data.phases) data.phases = data.phases.split(',').map(f => f.trim());

    const response = await fetch('/api/programs', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() },
        body: JSON.stringify(data)
    });
    const result = await response.json();
    if (result.success) {
        window.location.href = "{{ route('programs.index') }}";
    } else {
        alert(result.message || 'Failed to create program');
    }
});
</script>
@endsection


