@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Edit Facility</h1>

    <form id="edit-facility-form">
        @csrf
        @method('PUT')

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

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Update</button>
        <a href="{{ route('facility.index') }}" class="ml-3 text-gray-600">Cancel</a>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const pathSegments = window.location.pathname.split("/");
    const facilityId = pathSegments[pathSegments.length - 2];

    // Load existing data
    try {
        const response = await fetch(`/api/facilities/${facilityId}`);
        const result = await response.json();

        if (result.success) {
            const f = result.data;
            document.querySelector('[name="name"]').value = f.name;
            document.querySelector('[name="location"]').value = f.location;
            document.querySelector('[name="description"]').value = f.description;
            document.querySelector('[name="partner_organization"]').value = f.partner_organization ?? "";
            document.querySelector('[name="facility_type"]').value = f.facility_type;
            document.querySelector('[name="capabilities"]').value = (f.capabilities || []).join(", ");
        } else {
            alert(result.message || 'Failed to load facility data');
        }
    } catch (error) {
        alert('An error occurred while fetching data.');
    }

    // Handle update
    document.getElementById('edit-facility-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        let data = Object.fromEntries(formData.entries());
        if (data.capabilities) {
            data.capabilities = data.capabilities.split(',').map(c => c.trim());
        }

        try {
            const updateResponse = await fetch(`/api/facilities/${facilityId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify(data)
            });

            const updateResult = await updateResponse.json();
            if (updateResult.success) {
                window.location.href = "{{ route('facility.index') }}";
            } else {
                alert(updateResult.message || 'Failed to update facility');
            }
        } catch (error) {
            alert('An error occurred during the update.');
        }
    });
});
</script>
@endsection