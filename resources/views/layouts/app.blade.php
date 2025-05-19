<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Task Manager') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional head content from individual pages -->
    @yield('head')
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
    <div class="min-h-screen">
        <!-- Enhanced Navigation -->
        <nav class="bg-white/80 backdrop-blur-lg shadow-lg border-b border-white/20 sticky top-0 z-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Enhanced Logo -->
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                TaskFlow
                            </span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    @auth
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2H3z"></path>
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('tasks.index') }}" class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Tasks
                        </a>
                        <a href="{{ route('calendar.index') }}" class="nav-link {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Calendar
                        </a>
                        <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2h0l3.5-2.5"></path>
                            </svg>
                            Categories
                        </a>
                        <a href="{{ route('stats.index') }}" class="nav-link {{ request()->routeIs('stats.*') ? 'active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Analytics
                        </a>

                        <!-- User menu -->
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-semibold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <span class="text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn-ghost">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="btn-ghost">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary">Get Started</a>
                    </div>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-white/50 backdrop-blur-sm shadow-sm">
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
                <div class="glass-card border border-green-200/50 text-green-800 px-4 py-3 rounded-xl shadow-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="font-medium">{{ session('success') }}</div>
                    </div>
                </div>
            </div>
            @endif

            @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="glass-card border border-red-200/50 text-red-800 px-4 py-3 rounded-xl shadow-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L12.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="font-medium">{{ session('error') }}</div>
                    </div>
                </div>
            </div>
            @endif

            @if (session('info'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                <div class="glass-card border border-blue-200/50 text-blue-800 px-4 py-3 rounded-xl shadow-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="font-medium">{{ session('info') }}</div>
                    </div>
                </div>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Calendar JavaScript -->
    <script>
        // Calendar functionality placeholder
        window.updateCalendarHeader = function() {
            // Calendar update logic will be defined in dashboard view
        };
    </script>
</body>
</html>

<style>
/* Custom component styles */
.nav-link {
    @apply flex items-center px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-indigo-600 hover:bg-indigo-50/50 transition-all duration-200;
}

.nav-link.active {
    @apply text-indigo-600 bg-indigo-50/50 shadow-sm;
}

.btn-primary {
    @apply inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-semibold rounded-lg hover:from-indigo-600 hover:to-purple-700 focus:ring-4 focus:ring-indigo-200 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5;
}

.btn-secondary {
    @apply inline-flex items-center px-6 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-lg border border-gray-200 hover:bg-gray-50 hover:border-gray-300 focus:ring-4 focus:ring-gray-200 transition-all duration-200 shadow hover:shadow-lg;
}

.btn-ghost {
    @apply inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 text-sm font-medium rounded-lg hover:bg-gray-50/50 transition-all duration-200;
}

.glass-card {
    @apply bg-white/70 backdrop-blur-lg border border-white/20 shadow-lg hover:shadow-xl transition-all duration-300;
}

.stats-card {
    @apply glass-card p-6 rounded-xl hover:scale-[1.02] transition-all duration-300 border border-white/30;
}

.priority-urgent {
    @apply bg-gradient-to-r from-red-500 to-pink-500;
}

.priority-high {
    @apply bg-gradient-to-r from-orange-500 to-red-500;
}

.priority-medium {
    @apply bg-gradient-to-r from-yellow-500 to-orange-500;
}

.priority-low {
    @apply bg-gradient-to-r from-green-500 to-blue-500;
}

/* Enhanced calendar styles */
.calendar-container {
    @apply bg-white/60 backdrop-blur-lg rounded-xl shadow-lg border border-white/30;
}

.calendar-day {
    @apply bg-white/50 backdrop-blur-sm rounded-lg p-3 h-24 cursor-pointer border border-white/30 hover:border-indigo-300 hover:shadow-lg hover:scale-105 transition-all duration-200;
}

.calendar-day.today {
    @apply border-indigo-400 bg-indigo-50/50 shadow-lg;
}

.calendar-day.has-tasks {
    @apply border-purple-200 bg-purple-50/30;
}

.task-indicator {
    @apply h-1 rounded-full mb-1 first:mt-2;
}

/* Task item styling */
.task-item {
    @apply glass-card p-6 rounded-xl hover:shadow-xl hover:scale-[1.01] transition-all duration-200 border-l-4;
}

.task-item.completed {
    @apply opacity-75 bg-green-50/30;
}

.task-item.overdue {
    @apply border-l-red-500 bg-red-50/20;
}

/* Category badges */
.category-badge {
    @apply inline-flex items-center px-3 py-1 rounded-full text-sm font-medium backdrop-blur-sm;
}

/* Priority badges */
.priority-badge {
    @apply inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold;
}

.priority-badge.urgent {
    @apply bg-red-100 text-red-800 border border-red-200;
}

.priority-badge.high {
    @apply bg-orange-100 text-orange-800 border border-orange-200;
}

.priority-badge.medium {
    @apply bg-yellow-100 text-yellow-800 border border-yellow-200;
}

.priority-badge.low {
    @apply bg-green-100 text-green-800 border border-green-200;
}

/* Form styling */
.form-input {
    @apply block w-full rounded-lg border-gray-200 bg-white/50 backdrop-blur-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all duration-200;
}

.form-label {
    @apply block text-sm font-semibold text-gray-700 mb-2;
}

.form-group {
    @apply space-y-4;
}

/* Progress bars */
.progress-bar {
    @apply w-full bg-gray-200 rounded-full h-2 overflow-hidden;
}

.progress-fill {
    @apply h-full bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full transition-all duration-500;
}

/* Animation utilities */
.fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.slide-up {
    animation: slideUp 0.5s ease-out;
}

@keyframes slideUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Hover effects */
.hover-lift {
    @apply transition-all duration-200 hover:-translate-y-1 hover:shadow-lg;
}

/* Status indicators */
.status-indicator {
    @apply inline-flex items-center px-2 py-1 rounded-full text-xs font-medium;
}

.status-indicator.completed {
    @apply bg-green-100 text-green-800;
}

.status-indicator.pending {
    @apply bg-yellow-100 text-yellow-800;
}

.status-indicator.overdue {
    @apply bg-red-100 text-red-800;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .nav-link span {
        @apply hidden;
    }

    .calendar-day {
        @apply h-16 p-2;
    }

    .task-item {
        @apply p-4;
    }
}
</style>
