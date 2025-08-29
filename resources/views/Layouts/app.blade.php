<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facility Management</title>

    <!-- Tailwind CSS (via CDN, you can also compile with Laravel Mix if preferred) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Navbar -->
    <nav class="bg-blue-700 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="/" class="font-bold text-lg">Capstone System</a>
            <div class="space-x-6">
                <a href="/facility" class="hover:underline">Facilities</a>
                <a href="/facility/create" class="hover:underline">Register Facility</a>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="container mx-auto py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-200 py-4 mt-8">
        <div class="container mx-auto text-center text-gray-600">
            &copy; {{ date('Y') }} Capstone Project. All rights reserved.
        </div>
    </footer>

    <!-- Global Scripts -->
    <script>
        // Setup CSRF for all fetch requests
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }
    </script>
</body>
</html>
