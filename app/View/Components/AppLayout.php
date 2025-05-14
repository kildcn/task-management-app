<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Task Manager') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom CSS for enhanced design -->
    <style>
        /* Custom CSS for better visual design */
        .glass-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .calendar-day {
            transition: all 0.2s ease;
        }

        .calendar-day:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .task-indicator {
            height: 3px;
            margin-bottom: 2px;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Stats card hover effects */
        .stats-card {
            transition: all 0.2s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Priority indicator line */
        .priority-line {
            width: 4px;
            height: 48px;
            border-radius: 2px;
            margin-right: 12px;
        }

        /* Enhanced buttons */
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border: none;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca 0%, #7c3aed 100%);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.6);
            transform: translateY(-1px);
        }

        /* Navigation gradient */
        .nav-gradient {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        /* Task list item hover effect */
        .task-item {
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
        }

        .task-item:hover {
            background: rgba(99, 102, 241, 0.05);
            border-left: 4px solid #6366f1;
            transform: translateX(4px);
        }

        /* Progress bars with gradients */
        .progress-bar {
            background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 100%);
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        /* Completion rate indicators */
        .completion-excellent { background: linear-gradient(90deg, #059669 0%, #10b981 100%); }
        .completion-good { background: linear-gradient(90deg, #d97706 0%, #f59e0b 100%); }
        .completion-needs-work { background: linear-gradient(90deg, #dc2626 0%, #ef4444 100%); }

        body {
            background: linear-gradient(to bottom right, rgb(249, 250, 251), rgb(243, 244, 246));
            min-height: 100vh;
        }
    </style>

    <!-- Additional head content from individual pages -->
    @yield('head')
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="min-h-screen">
        <!-- Enhanced Navigation -->
        <nav class="bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-200/50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Enhanced Logo -->
                        <a href="{{ route('dashboard') }}" class="text-2xl font-bold gradient-text">
                            Task Manager
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    @auth
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors
                            {{ request()->routeIs('dashboard') ? 'text-indigo-600' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('tasks.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors
                            {{ request()->routeIs('tasks.*') ? 'text-indigo-600' : '' }}">
                            Tasks
                        </a>
                        <a href="{{ route('categories.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors
                            {{ request()->routeIs('categories.*') ? 'text-indigo-600' : '' }}">
                            Categories
                        </a>
                        <a href="{{ route('stats.index') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors
                            {{ request()->routeIs('stats.*') ? 'text-indigo-600' : '' }}">
                            Statistics
                        </a>

                        <!-- User menu -->
                        <div class="flex items-center space-x-3">
                            <span class="text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn-primary text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm">Register</a>
                    </div>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <!-- Page Content -->
        <main>
            <!-- Enhanced Alert Messages -->
            @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="glass-card border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-sm">
                    <div class="flex">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            </div>
            @endif

            @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="glass-card border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-sm">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L12.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            </div>
            @endif

            @if (session('info'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="glass-card border border-blue-200 text-blue-800 px-4 py-3 rounded-lg shadow-sm">
                    <div class="flex">
                        <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('info') }}
                    </div>
                </div>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Calendar JavaScript -->
    <script>
        if (typeof updateCalendarHeader === 'function') {
            updateCalendarHeader();
        }
    </script>
</body>
</html>
