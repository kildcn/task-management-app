@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Household Statistics</h1>
        <p class="text-gray-600">Compare task creation and completion across household members</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Tasks Created</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $householdStats->sum('tasks_created_count') }}</dd>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Tasks Completed</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $householdStats->sum('tasks_completed_count') }}</dd>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Average Completion Rate</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($householdStats->avg('completion_rate'), 1) }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Creation vs Completion Comparison -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Creation Distribution -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Task Creation Distribution</h3>

                @php
                    $totalCreated = $householdStats->sum('tasks_created_count') ?: 1;
                @endphp

                @foreach($householdStats->sortByDesc('tasks_created_count') as $stat)
                @php
                    $percentage = ($stat->tasks_created_count / $totalCreated) * 100;
                @endphp
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                <span class="text-sm font-medium text-white">{{ substr($stat->user->name, 0, 1) }}</span>
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ $stat->user->name }}</span>
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $stat->tasks_created_count }} tasks ({{ number_format($percentage, 1) }}%)
                        </div>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full transition-all duration-500"
                             style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
                @endforeach

                <!-- Fairness Score for Creation -->
                @php
                    $creationValues = $householdStats->pluck('tasks_created_count');
                    $stdDev = $creationValues->count() > 1 ? sqrt($creationValues->map(fn($x) => pow($x - $creationValues->avg(), 2))->avg()) : 0;
                    $fairnessScore = $creationValues->avg() > 0 ? max(0, 100 - (($stdDev / $creationValues->avg()) * 100)) : 100;
                @endphp
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Creation Balance Score</span>
                        <span class="text-sm font-medium {{ $fairnessScore >= 70 ? 'text-green-600' : ($fairnessScore >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ number_format($fairnessScore, 0) }}/100
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completion Distribution -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Task Completion Distribution</h3>

                @php
                    $totalCompleted = $householdStats->sum('tasks_completed_count') ?: 1;
                @endphp

                @foreach($householdStats->sortByDesc('tasks_completed_count') as $stat)
                @php
                    $percentage = ($stat->tasks_completed_count / $totalCompleted) * 100;
                @endphp
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                <span class="text-sm font-medium text-white">{{ substr($stat->user->name, 0, 1) }}</span>
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ $stat->user->name }}</span>
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $stat->tasks_completed_count }} tasks ({{ number_format($percentage, 1) }}%)
                        </div>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-600 h-3 rounded-full transition-all duration-500"
                             style="width: {{ $percentage }}%"></div>
                    </div>

                    <div class="mt-1 text-xs {{ $stat->completion_rate >= 80 ? 'text-green-600' : ($stat->completion_rate >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ number_format($stat->completion_rate, 1) }}% personal completion rate
                    </div>
                </div>
                @endforeach

                <!-- Fairness Score for Completion -->
                @php
                    $completionValues = $householdStats->pluck('tasks_completed_count');
                    $stdDev = $completionValues->count() > 1 ? sqrt($completionValues->map(fn($x) => pow($x - $completionValues->avg(), 2))->avg()) : 0;
                    $fairnessScore = $completionValues->avg() > 0 ? max(0, 100 - (($stdDev / $completionValues->avg()) * 100)) : 100;
                @endphp
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Completion Balance Score</span>
                        <span class="text-sm font-medium {{ $fairnessScore >= 70 ? 'text-green-600' : ($fairnessScore >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ number_format($fairnessScore, 0) }}/100
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Member Comparison Chart -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Member Performance Comparison</h3>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <tbody class="space-y-4">
                        @foreach($householdStats as $stat)
                        <tr class="border-b border-gray-100 last:border-b-0">
                            <td class="py-4 pr-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-indigo-400 to-indigo-600 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm font-medium text-white">{{ substr($stat->user->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $stat->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $stat->points }} points</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="space-y-1">
                                    <div class="flex justify-between text-xs">
                                        <span class="text-blue-600">Created</span>
                                        <span>{{ $stat->tasks_created_count }}</span>
                                    </div>
                                    <div class="w-24 bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full"
                                             style="width: {{ $maxCreated > 0 ? ($stat->tasks_created_count / $maxCreated) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="space-y-1">
                                    <div class="flex justify-between text-xs">
                                        <span class="text-green-600">Completed</span>
                                        <span>{{ $stat->tasks_completed_count }}</span>
                                    </div>
                                    <div class="w-24 bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full"
                                             style="width: {{ $maxCompleted > 0 ? ($stat->tasks_completed_count / $maxCompleted) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="space-y-1">
                                    <div class="flex justify-between text-xs">
                                        <span class="text-purple-600">Rate</span>
                                        <span>{{ number_format($stat->completion_rate, 1) }}%</span>
                                    </div>
                                    <div class="w-24 bg-gray-200 rounded-full h-2">
                                        <div class="bg-purple-600 h-2 rounded-full"
                                             style="width: {{ $stat->completion_rate }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 pl-4">
                                <div class="text-center">
                                    @if($stat->current_streak_days > 0)
                                        <div class="inline-flex items-center px-2 py-1 rounded-full bg-orange-100 text-orange-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-xs font-medium">{{ $stat->current_streak_days }}d</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">No streak</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Category Breakdown -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Task Distribution by Category</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categoryStats as $categoryName => $stats)
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">{{ $categoryName }}</h4>

                    @foreach($stats as $stat)
                    <div class="flex justify-between items-center py-2">
                        <div class="flex items-center">
                            <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center mr-2">
                                <span class="text-xs font-medium text-gray-700">{{ substr($stat['user'], 0, 1) }}</span>
                            </div>
                            <span class="text-xs text-gray-600">{{ $stat['user'] }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="text-xs">
                                <span class="text-blue-600">{{ $stat['created'] }}</span>
                                <span class="text-gray-400">/</span>
                                <span class="text-green-600">{{ $stat['completed'] }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @php
                        $categoryTotal = collect($stats)->sum('created');
                        $categoryCompleted = collect($stats)->sum('completed');
                        $categoryRate = $categoryTotal > 0 ? ($categoryCompleted / $categoryTotal) * 100 : 0;
                    @endphp
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Total</span>
                            <span class="font-medium">{{ $categoryTotal }} / {{ $categoryCompleted }}</span>
                        </div>
                        <div class="mt-1 w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $categoryRate }}%"></div>
                        </div>
                        <div class="mt-1 text-xs text-gray-500 text-center">{{ number_format($categoryRate, 1) }}% completion</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Back to Dashboard -->
    <div class="mt-8">
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Dashboard
        </a>
    </div>
</div>
@endsection
