@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Register New Facility</h1>

    <form id="facility-form">
        @csrf
        <div class="mb-4">
            <label class="block font-medium">Name</label>
            <input type="text" name="name" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Location</label>
            <input type="text" name="location" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Description</label>
            <textarea name="description" class="w-full border rounded p-2" rows="3" required></textarea>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Partner Organization</label>
            <select name="partner_organization" class="w-full border rounded p-2">
                <option value="">-- None --</option>
                <option value="UniPod">UniPod</option>
                <option value="UIRI">UIRI</option>
                <option value="Lwera">Lwera</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Facility Type</label>
            <select name="facility_type" class="w-full border rounded p-2" required>
                <option value="Lab">Lab</option>
                <option value="Workshop">Workshop</option>
                <option value="Testing Center">Testing Center</option>
                <option value="Maker Space">Maker Space</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Capabilities (comma separated)</label>
            <input type="text" name="capabilities" class="w-full border rounded p-2">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
        <a href="{{ route('facility.index') }}" class="ml-3 text-gray-600">Cancel</a>
    </form>
</div>

<script>
document.getElementById('facility-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    let data = Object.fromEntries(formData.entries());
    if (data.capabilities) {
        data.capabilities = data.capabilities.split(',').map(c => c.trim());
    }

    const response = await fetch('/api/facilities', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify(data)
    });

    const result = await response.json();
    if (result.success) {
        window.location.href = "{{ route('facility.index') }}";
    } else {
        alert(result.message || 'Failed to create facility');
    }
});
</script>
@endsection
