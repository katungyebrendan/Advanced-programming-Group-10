@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Facility Details</h1>

    <div id="facility-details">Loading...</div>

    <a href="{{ route('facility.index') }}" class="mt-4 inline-block text-blue-600">Back to list</a>
</div>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const pathSegments = window.location.pathname.split("/");
    const facilityId = pathSegments[pathSegments.length - 1];

    try {
        const response = await fetch(`/api/facilities/${facilityId}`);
        const result = await response.json();

        const detailsDiv = document.getElementById("facility-details");
        if (result.success) {
            const f = result.data;
            detailsDiv.innerHTML = `
                <p><strong>ID:</strong> ${f.facility_id}</p>
                <p><strong>Name:</strong> ${f.name}</p>
                <p><strong>Location:</strong> ${f.location}</p>
                <p><strong>Description:</strong> ${f.description}</p>
                <p><strong>Type:</strong> ${f.facility_type}</p>
                <p><strong>Partner:</strong> ${f.partner_organization ?? "-"}</p>
                <p><strong>Capabilities:</strong> ${(f.capabilities || []).join(", ")}</p>
            `;
        } else {
            detailsDiv.innerHTML = `<p class="text-red-600">${result.message || 'Facility not found.'}</p>`;
        }
    } catch (error) {
        document.getElementById("facility-details").innerHTML = `<p class="text-red-600">An error occurred while fetching details.</p>`;
    }
});
</script>
@endsection