<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capstone System</title>

    <!-- Google Font: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            display: grid;
            grid-template-rows: auto 1fr auto;
            min-height: 100vh;
        }
        .nav-link {
            @apply px-3 py-2 rounded-lg transition-all duration-300 hover:bg-gray-800 hover:text-white cursor-pointer;
        }
    </style>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100 text-gray-800">

    <!-- Navbar -->
    <nav class="bg-gray-900 text-white shadow-lg py-4 px-6 rounded-b-xl">
        <div class="container mx-auto flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
            <a href="{{ route('programs.index') }}" class="font-bold text-2xl tracking-wide">Capstone System</a>
            <div class="flex flex-wrap justify-center sm:justify-end items-center gap-2 sm:gap-6 text-sm md:text-base">
                <a href="{{ route('facilities.index') }}" 
                   class="nav-link {{ request()->routeIs('facilities.*') ? 'underline underline-offset-4' : '' }}">
                    Facilities
                </a>
                <a href="{{ route('programs.index') }}" 
                   class="nav-link {{ request()->routeIs('programs.*') ? 'underline underline-offset-4' : '' }}">
                    Programs
                </a>
                <a href="{{ route('equipment.index') }}" 
                   class="nav-link {{ request()->routeIs('equipment.*') ? 'underline underline-offset-4' : '' }}">
                    Equipment
                </a>
                <a href="{{ route('services.index') }}" 
                   class="nav-link {{ request()->routeIs('services.*') ? 'underline underline-offset-4' : '' }}">
                    Services
                </a>
                <a href="{{ route('projects.index') }}" 
                   class="nav-link {{ request()->routeIs('projects.*') ? 'underline underline-offset-4' : '' }}">
                    Projects
                </a>
                <a href="{{ route('participants.index') }}" 
                   class="nav-link {{ request()->routeIs('participants.*') ? 'underline underline-offset-4' : '' }}">
                    Participants
                </a>

                @php $firstProject = \App\Models\Project::first(); @endphp
                @if($firstProject)
                    <a href="{{ route('projects.outcomes.index', $firstProject->project_id) }}" 
                       class="nav-link {{ request()->routeIs('projects.outcomes.*') ? 'underline underline-offset-4' : '' }}">
                        Outcomes
                    </a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto py-8 px-4 overflow-y-auto">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto text-center text-sm text-gray-400">
            &copy; {{ date('Y') }} Capstone System. All rights reserved.
        </div>
    </footer>

    <script>
        // CSRF token for JS fetch requests
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }
    </script>
</body>
</html>
