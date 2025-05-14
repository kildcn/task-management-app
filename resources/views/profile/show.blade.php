@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
        <p class="text-gray-600">View your task statistics and performance metrics</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6 text-center">
                    <div class="w-24 h-24 bg-gray-300 rounded-full mx-auto flex items-center justify-center">
                        <span class="text-3xl font-bold text-gray-700">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900">{{ Auth::user()->name }}</h3>
                    <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ ucfirst(Auth::user()->role) }} in {{ Auth::user()->household->name }}
                    </p>

                    <div class="mt-6">
                        <a href="{{ route('profile.edit') }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="lg:col-span-2">
            @if(Auth::user()->taskStats)
                @php $stats = Auth::user()->taskStats @endphp

                <!-- Overview Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Tasks Completed</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ $stats->tasks_completed_count }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Current Streak</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ $stats->current_streak_days }} days</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Points</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ $stats->points }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Stats -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Performance Details</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Task Statistics</h4>
                                <dl class="mt-2 space-y-2">
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">Tasks Created</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ $stats->tasks_created_count }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">Tasks Assigned</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ $stats->tasks_assigned_count }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">Completion Rate</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ number_format($stats->completion_rate, 1) }}%</dd>
                                    </div>
                                </dl>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Achievements</h4>
                                <dl class="mt-2 space-y-2">
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">Longest Streak</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ $stats->longest_streak_days }} days</dd>
                                    </div>
                                    @if($stats->last_completed_at)
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-600">Last Completed</dt>
                                            <dd class="text-sm font-medium text-gray-900">{{ $stats->last_completed_at->diffForHumans() }}</dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>
                        </div>

                        <!-- Progress Bar for Completion Rate -->
                        <div class="mt-6">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Completion Rate</span>
                                <span>{{ number_format($stats->completion_rate, 1) }}%</span>
                            </div>
                            <div class="mt-1 relative pt-1">
                                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
                                    <div style="width:{{ $stats->completion_rate }}%"
                                         class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No statistics yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Start completing tasks to see your statistics here.</p>
                        <div class="mt-6">
                            <a href="{{ route('tasks.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                View Tasks
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="mt-8">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Activity</h3>

                @php
                    $recentTasks = Auth::user()->completedTasks()
                        ->with(['category'])
                        ->orderBy('completed_at', 'desc')
                        ->take(5)
                        ->get();
                @endphp

                @if($recentTasks->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentTasks as $task)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $task->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $task->category->name }} • Completed {{ $task->completed_at->diffForHumans() }}</p>
                                </div>
                                <a href="{{ route('tasks.show', $task) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">No completed tasks yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
