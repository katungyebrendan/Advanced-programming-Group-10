@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Programs</h1>

    <a href="{{ route('programs.create') }}" 
       class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
        + Add Program
    </a>

    <table class="table-auto border-collapse border border-gray-400 w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">National Alignment</th>
                <th class="border px-4 py-2">Focus Areas</th>
                <th class="border px-4 py-2">Phases</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody id="program-table">
            <!-- Programs will load here -->
        </tbody>
    </table>
</div>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const response = await fetch("/api/programs");
    const result = await response.json();
    if (result.success) {
        let rows = "";
        result.data.forEach(p => {
            rows += `
                <tr>
                    <td class="border px-4 py-2">${p.id}</td>
                    <td class="border px-4 py-2">${p.name}</td>
                    <td class="border px-4 py-2">${p.national_alignment ?? "-"}</td>
                    <td class="border px-4 py-2">${(p.focus_areas || []).join(", ")}</td>
                    <td class="border px-4 py-2">${(p.phases || []).join(", ")}</td>
                    <td class="border px-4 py-2 space-x-2">
                        <a href="/program/${p.id}" class="text-blue-600">View</a>
                        <a href="/program/${p.id}/edit" class="text-yellow-600">Edit</a>
                        <button onclick="deleteProgram(${p.id})" class="text-red-600">Delete</button>
                    </td>
                </tr>
            `;
        });
        document.getElementById("program-table").innerHTML = rows;
    } else {
        document.getElementById("program-table").innerHTML =
            `<tr><td colspan="6" class="text-center text-red-600">Failed to load programs</td></tr>`;
    }
});

async function deleteProgram(id) {
    if (!confirm("Are you sure you want to delete this program?")) return;
    const response = await fetch(`/api/programs/${id}`, {
        method: "DELETE",
        headers: { "X-CSRF-TOKEN": getCsrfToken() }
    });
    const result = await response.json();
    if (result.success) {
        alert("Program deleted successfully");
        location.reload();
    } else {
        alert(result.message || "Failed to delete program");
    }
}
</script>
@endsection
