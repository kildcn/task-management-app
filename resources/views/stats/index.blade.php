@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Household Statistics</h1>
        <p class="text-gray-600 mt-1">Track performance and progress across your household</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $householdStats->sum('tasks_created_count') }}</p>
                    <p class="text-sm text-gray-600">Tasks Created</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $householdStats->sum('tasks_completed_count') }}</p>
                    <p class="text-sm text-gray-600">Tasks Completed</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ round($householdStats->avg('completion_rate'), 1) }}%</p>
                    <p class="text-sm text-gray-600">Avg Completion Rate</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $householdStats->sum('points') }}</p>
                    <p class="text-sm text-gray-600">Total Points</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Member Performance -->
    <div class="bg-white rounded-xl border border-gray-200 mb-8">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Member Performance</h2>
        </div>
        <div class="p-6">
            <div class="space-y-6">
                @foreach($householdStats->sortByDesc('points') as $stat)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-400 to-purple-600 rounded-full flex items-center justify-center mr-4">
                            <span class="text-lg font-bold text-white">{{ substr($stat->user->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $stat->user->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $stat->points }} points</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-8">
                        <!-- Tasks Created -->
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ $stat->tasks_created_count }}</p>
                            <p class="text-xs text-gray-500">Created</p>
                            <div class="w-16 bg-gray-200 rounded-full h-2 mt-1">
                                <div class="bg-blue-600 h-2 rounded-full"
                                     style="width: {{ $maxCreated > 0 ? ($stat->tasks_created_count / $maxCreated) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <!-- Tasks Completed -->
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-600">{{ $stat->tasks_completed_count }}</p>
                            <p class="text-xs text-gray-500">Completed</p>
                            <div class="w-16 bg-gray-200 rounded-full h-2 mt-1">
                                <div class="bg-green-600 h-2 rounded-full"
                                     style="width: {{ $maxCompleted > 0 ? ($stat->tasks_completed_count / $maxCompleted) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <!-- Completion Rate -->
                        <div class="text-center">
                            <p class="text-2xl font-bold text-purple-600">{{ round($stat->completion_rate, 1) }}%</p>
                            <p class="text-xs text-gray-500">Completion Rate</p>
                            <div class="w-16 bg-gray-200 rounded-full h-2 mt-1">
                                <div class="bg-purple-600 h-2 rounded-full"
                                     style="width: {{ $stat->completion_rate }}%"></div>
                            </div>
                        </div>

                        <!-- Current Streak -->
                        <div class="text-center">
                            @if($stat->current_streak_days > 0)
                                <div class="flex items-center justify-center">
                                    <svg class="w-5 h-5 text-orange-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-lg font-bold text-orange-600">{{ $stat->current_streak_days }}</span>
                                </div>
                                <p class="text-xs text-gray-500">Day Streak</p>
                            @else
                                <p class="text-sm text-gray-400">No Streak</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Category Breakdown -->
    <div class="bg-white rounded-xl border border-gray-200 mb-8">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Task Distribution by Category</h2>
        </div>
        <div class="p-6">
            @if(count($categoryStats) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($categoryStats as $categoryName => $stats)
                    <div class="border border-gray-200 rounded-lg p-5">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $categoryName }}</h3>

                        @foreach($stats as $stat)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-sm font-medium text-gray-700">{{ substr($stat['user'], 0, 1) }}</span>
                                </div>
                                <span class="text-sm text-gray-900">{{ $stat['user'] }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="text-center">
                                    <p class="text-sm font-semibold text-blue-600">{{ $stat['created'] }}</p>
                                    <p class="text-xs text-gray-500">Created</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-semibold text-green-600">{{ $stat['completed'] }}</p>
                                    <p class="text-xs text-gray-500">Completed</p>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @php
                            $categoryTotal = collect($stats)->sum('created');
                            $categoryCompleted = collect($stats)->sum('completed');
                            $categoryRate = $categoryTotal > 0 ? ($categoryCompleted / $categoryTotal) * 100 : 0;
                        @endphp
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Category Total</span>
                                <span class="text-sm font-medium text-gray-900">{{ $categoryCompleted }}/{{ $categoryTotal }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300"
                                     style="width: {{ $categoryRate }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 text-center">{{ round($categoryRate, 1) }}% completion</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2h0l3.5-2.5"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No category data</h3>
                    <p class="mt-1 text-sm text-gray-500">Start creating tasks to see category breakdown.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Task Distribution Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Creation vs Completion -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Creation vs Completion</h2>

            @php
                $totalCreated = $householdStats->sum('tasks_created_count') ?: 1;
                $totalCompleted = $householdStats->sum('tasks_completed_count') ?: 1;
            @endphp

            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Tasks Created</span>
                        <span class="text-sm font-medium text-gray-900">{{ $totalCreated }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full" style="width: 100%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Tasks Completed</span>
                        <span class="text-sm font-medium text-gray-900">{{ $totalCompleted }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-600 h-3 rounded-full"
                             style="width: {{ ($totalCompleted / $totalCreated) * 100 }}%"></div>
                    </div>
                </div>
            </div>

            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900">{{ round(($totalCompleted / $totalCreated) * 100, 1) }}%</p>
                    <p class="text-sm text-gray-600">Overall Completion Rate</p>
                </div>
            </div>
        </div>

        <!-- Household Balance -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Household Balance</h2>

            @php
                $creationValues = $householdStats->pluck('tasks_created_count');
                $creationMean = $creationValues->avg();
                $creationStdDev = $creationValues->count() > 1 ? sqrt($creationValues->map(fn($x) => pow($x - $creationMean, 2))->avg()) : 0;
                $creationBalance = $creationMean > 0 ? max(0, 100 - (($creationStdDev / $creationMean) * 100)) : 100;

                $completionValues = $householdStats->pluck('tasks_completed_count');
                $completionMean = $completionValues->avg();
                $completionStdDev = $completionValues->count() > 1 ? sqrt($completionValues->map(fn($x) => pow($x - $completionMean, 2))->avg()) : 0;
                $completionBalance = $completionMean > 0 ? max(0, 100 - (($completionStdDev / $completionMean) * 100)) : 100;
            @endphp

            <div class="space-y-6">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Task Creation Balance</span>
                        <span class="text-sm font-medium {{ $creationBalance >= 70 ? 'text-green-600' : ($creationBalance >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ round($creationBalance) }}/100
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="{{ $creationBalance >= 70 ? 'bg-green-500' : ($creationBalance >= 50 ? 'bg-yellow-500' : 'bg-red-500') }} h-3 rounded-full transition-all duration-300"
                             style="width: {{ $creationBalance }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $creationBalance >= 70 ? 'Excellent distribution' : ($creationBalance >= 50 ? 'Good distribution' : 'Uneven distribution') }}
                    </p>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Task Completion Balance</span>
                        <span class="text-sm font-medium {{ $completionBalance >= 70 ? 'text-green-600' : ($completionBalance >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ round($completionBalance) }}/100
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="{{ $completionBalance >= 70 ? 'bg-green-500' : ($completionBalance >= 50 ? 'bg-yellow-500' : 'bg-red-500') }} h-3 rounded-full transition-all duration-300"
                             style="width: {{ $completionBalance }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $completionBalance >= 70 ? 'Excellent distribution' : ($completionBalance >= 50 ? 'Good distribution' : 'Uneven distribution') }}
                    </p>
                </div>
            </div>

            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-blue-800">
                        Balance scores show how evenly tasks are distributed among household members.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-8">
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Dashboard
        </a>
    </div>
</div>
@endsection
