@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Register New Facility</h1>

    <div id="error-messages" class="mb-4 text-red-600 font-bold"></div>

    <form id="facility-form">
        @csrf
        <div class="mb-4">
            <label class="block font-medium">Name</label>
            <input type="text" name="name" class="w-full border rounded p-2" required>
            <p class="text-red-500 text-sm mt-1" id="error-name"></p>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Location</label>
            <input type="text" name="location" class="w-full border rounded p-2" required>
            <p class="text-red-500 text-sm mt-1" id="error-location"></p>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Description</label>
            <textarea name="description" class="w-full border rounded p-2" rows="3" required></textarea>
            <p class="text-red-500 text-sm mt-1" id="error-description"></p>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Partner Organization</label>
            <select name="partner_organization" class="w-full border rounded p-2">
                <option value="">-- None --</option>
                <option value="UniPod">UniPod</option>
                <option value="UIRI">UIRI</option>
                <option value="Lwera">Lwera</option>
            </select>
            <p class="text-red-500 text-sm mt-1" id="error-partner_organization"></p>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Facility Type</label>
            <select name="facility_type" class="w-full border rounded p-2" required>
                <option value="Lab">Lab</option>
                <option value="Workshop">Workshop</option>
                <option value="Testing Center">Testing Center</option>
                <option value="Maker Space">Maker Space</option>
            </select>
            <p class="text-red-500 text-sm mt-1" id="error-facility_type"></p>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Capabilities (comma separated)</label>
            <input type="text" name="capabilities" class="w-full border rounded p-2">
            <p class="text-red-500 text-sm mt-1" id="error-capabilities"></p>
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

    // Reset error messages
    document.querySelectorAll('[id^="error-"]').forEach(el => el.textContent = '');
    const generalErrorDiv = document.getElementById('error-messages');
    generalErrorDiv.textContent = '';

    try {
        const response = await fetch('/api/facilities', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) { // Status is 200-299
            window.location.href = "{{ route('facility.index') }}";
        } else if (response.status === 422) { // Status is 422 (Validation error)
            generalErrorDiv.textContent = 'Please correct the highlighted errors.';
            for (const field in result.errors) {
                const errorElement = document.getElementById(`error-${field}`);
                if (errorElement) {
                    errorElement.textContent = result.errors[field][0];
                }
            }
        } else { // Handle other API errors
            generalErrorDiv.textContent = result.message || 'An unknown error occurred.';
        }
    } catch (error) {
        generalErrorDiv.textContent = 'An error occurred while communicating with the server.';
        console.error('Fetch error:', error);
    }
});
</script>
@endsection