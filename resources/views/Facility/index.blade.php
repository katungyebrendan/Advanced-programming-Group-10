@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Facilities</h1>

    <a href="{{ route('facility.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">+ Add Facility</a>

    <table class="table-auto border-collapse border border-gray-400 w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Location</th>
                <th class="border px-4 py-2">Type</th>
                <th class="border px-4 py-2">Partner</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody id="facility-table">
            <!-- Facilities will load here -->
        </tbody>
    </table>
</div>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const response = await fetch("/api/facilities");
    const result = await response.json();

    if (result.success) {
        let rows = "";
        result.data.forEach(f => {
            rows += `
                <tr>
                    <td class="border px-4 py-2">${f.facility_id}</td>
                    <td class="border px-4 py-2">${f.name}</td>
                    <td class="border px-4 py-2">${f.location}</td>
                    <td class="border px-4 py-2">${f.facility_type}</td>
                    <td class="border px-4 py-2">${f.partner_organization ?? "-"}</td>
                    <td class="border px-4 py-2 space-x-2">
                        <a href="/facility/${f.facility_id}" class="text-blue-600">View</a>
                        <a href="/facility/${f.facility_id}/edit" class="text-yellow-600">Edit</a>
                        <button onclick="deleteFacility(${f.facility_id})" class="text-red-600">Delete</button>
                    </td>
                </tr>
            `;
        });
        document.getElementById("facility-table").innerHTML = rows;
    } else {
        document.getElementById("facility-table").innerHTML =
            `<tr><td colspan="6" class="text-center text-red-600">Failed to load facilities</td></tr>`;
    }
});

async function deleteFacility(id) {
    if (!confirm("Are you sure you want to delete this facility?")) return;

    const response = await fetch(`/api/facilities/${id}`, {
        method: "DELETE",
        headers: { "X-CSRF-TOKEN": getCsrfToken() }
    });

    const result = await response.json();
    if (result.success) {
        alert("Facility deleted successfully");
        location.reload();
    } else {
        alert(result.message || "Failed to delete facility");
    }
}
</script>
@endsection
