@extends('layouts.app')

@section('head')
<style>
    /* Calendar specific styles */
    .calendar-container {
        max-width: 100%;
        overflow-x: auto;
    }

    .calendar-grid {
        min-width: 600px;
    }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h1>
        <p class="text-gray-600 mt-2">Here's what's happening with your tasks today.</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="glass-card rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-200 stats-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">My Tasks</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ Auth::user()->assignedTasks()->whereNull('completed_at')->count() }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-purple-600 text-sm font-medium">Active tasks</span>
            </div>
        </div>

        <div class="glass-card rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-200 stats-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Completed Today</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ Auth::user()->completedTasks()->whereDate('completed_at', today())->count() }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-green-600 text-sm font-medium">+{{ Auth::user()->completedTasks()->whereDate('completed_at', today())->count() }} from yesterday</span>
            </div>
        </div>

        <div class="glass-card rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-200 stats-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Overdue</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $overdueTasks->count() }}</p>
                </div>
                <div class="p-3 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L12.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                @if($overdueTasks->count() > 0)
                    <span class="text-red-600 text-sm font-medium">Needs attention</span>
                @else
                    <span class="text-green-600 text-sm font-medium">All caught up!</span>
                @endif
            </div>
        </div>

        <div class="glass-card rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-200 stats-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Weekly Completion</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $weeklyCompletionRate }}%</p>
                </div>
                <div class="p-3 bg-indigo-100 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                @if($weeklyCompletionRate >= 80)
                    <span class="text-green-600 text-sm font-medium">Excellent progress</span>
                @elseif($weeklyCompletionRate >= 60)
                    <span class="text-orange-600 text-sm font-medium">Good progress</span>
                @else
                    <span class="text-red-600 text-sm font-medium">Keep going!</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Enhanced Calendar -->
        <div class="lg:col-span-2">
            <div class="glass-card rounded-xl shadow-sm">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-900">Task Calendar</h3>
                        <div class="flex items-center space-x-4">
                            <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors" onclick="previousMonth()">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <h4 class="text-lg font-semibold text-gray-900 min-w-[140px] text-center" id="currentMonth">
                                {{ now()->format('F Y') }}
                            </h4>
                            <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors" onclick="nextMonth()">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Calendar Container -->
                    <div class="calendar-container">
                        <!-- Calendar Grid -->
                        <div class="calendar-grid grid grid-cols-7 gap-1">
                            <!-- Day headers -->
                            <div class="text-center text-xs font-semibold text-gray-500 py-3">Sun</div>
                            <div class="text-center text-xs font-semibold text-gray-500 py-3">Mon</div>
                            <div class="text-center text-xs font-semibold text-gray-500 py-3">Tue</div>
                            <div class="text-center text-xs font-semibold text-gray-500 py-3">Wed</div>
                            <div class="text-center text-xs font-semibold text-gray-500 py-3">Thu</div>
                            <div class="text-center text-xs font-semibold text-gray-500 py-3">Fri</div>
                            <div class="text-center text-xs font-semibold text-gray-500 py-3">Sat</div>

                            @php
                                $currentMonth = now();
                                $startOfMonth = $currentMonth->copy()->startOfMonth();
                                $endOfMonth = $currentMonth->copy()->endOfMonth();
                                $startDate = $startOfMonth->copy()->startOfWeek();
                                $endDate = $endOfMonth->copy()->endOfWeek();

                                // Group tasks by date
                                $tasksByDate = $allTasks->groupBy(function($task) {
                                    return $task->due_date ? $task->due_date->format('Y-m-d') : null;
                                })->filter(function($tasks, $date) {
                                    return $date !== null;
                                });
                            @endphp

                            @for($date = $startDate->copy(); $date->lte($endDate); $date->addDay())
                                @php
                                    $isCurrentMonth = $date->month === $currentMonth->month;
                                    $isToday = $date->isToday();
                                    $dateString = $date->format('Y-m-d');
                                    $dailyTasks = $tasksByDate->get($dateString, collect());
                                @endphp

                                <div class="calendar-day bg-white rounded-lg p-2 h-20 cursor-pointer border-2
                                    @if($isToday) border-indigo-300 bg-indigo-50
                                    @else border-gray-100 hover:border-indigo-200
                                    @endif">
                                    <div class="text-sm font-medium
                                        @if($isToday) text-indigo-600
                                        @elseif($isCurrentMonth) text-gray-900
                                        @else text-gray-400
                                        @endif">
                                        {{ $date->format('j') }}
                                    </div>

                                    @if($dailyTasks->count() > 0)
                                        @foreach($dailyTasks->take(3) as $task)
                                            <div class="task-indicator rounded mt-1"
                                                style="background-color:
                                                @if($task->priority === 'urgent') #dc2626
                                                @elseif($task->priority === 'high') #ea580c
                                                @elseif($task->priority === 'medium') #ca8a04
                                                @else #16a34a
                                                @endif"></div>
                                        @endforeach
                                        @if($dailyTasks->count() > 3)
                                            <div class="text-xs text-gray-500 mt-1">+{{ $dailyTasks->count() - 3 }} more</div>
                                        @endif
                                    @endif

                                    @if($isToday)
                                        <div class="text-xs text-indigo-600 font-medium mt-1">Today</div>
                                    @endif
                                </div>
                            @endfor
                        </div>

                        <!-- Priority Legend -->
                        <div class="mt-6 flex flex-wrap gap-4">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-red-500 rounded mr-2"></div>
                                <span class="text-xs text-gray-600">Urgent</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-orange-500 rounded mr-2"></div>
                                <span class="text-xs text-gray-600">High</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-yellow-500 rounded mr-2"></div>
                                <span class="text-xs text-gray-600">Medium</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded mr-2"></div>
                                <span class="text-xs text-gray-600">Low</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Household Stats -->
        <div class="lg:col-span-1">
            <div class="glass-card rounded-xl shadow-sm">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Household Stats</h3>

                    <!-- Stats Toggle -->
                    <div class="flex rounded-lg border border-gray-200 mb-6 overflow-hidden">
                        <button class="flex-1 py-3 px-4 text-sm font-medium bg-indigo-50 text-indigo-700 border-r border-gray-200 transition-colors"
                                onclick="switchStatsView('created')" id="created-btn">
                            Created
                        </button>
                        <button class="flex-1 py-3 px-4 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors"
                                onclick="switchStatsView('completed')" id="completed-btn">
                            Completed
                        </button>
                    </div>

                    <!-- Created Tasks Stats -->
                    <div id="created-stats" class="space-y-4">
                        <div class="text-xs text-gray-500 mb-3 uppercase tracking-wide">Task Creation Distribution</div>

                        @php
                            $totalCreated = $householdStats->sum('tasks_created_count') ?: 1;
                        @endphp

                        @foreach($householdStats->sortByDesc('tasks_created_count') as $stat)
                            @php
                                $percentage = ($stat->tasks_created_count / $totalCreated) * 100;
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center flex-1">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm font-medium text-white">{{ substr($stat->user->name, 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $stat->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $stat->tasks_created_count }} tasks ({{ round($percentage, 1) }}%)</p>
                                    </div>
                                </div>
                                <div class="w-24 bg-gray-200 rounded-full h-2 ml-3">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Completed Tasks Stats (hidden by default) -->
                    <div id="completed-stats" class="space-y-4 hidden">
                        <div class="text-xs text-gray-500 mb-3 uppercase tracking-wide">Task Completion Distribution</div>

                        @php
                            $totalCompleted = $householdStats->sum('tasks_completed_count') ?: 1;
                        @endphp

                        @foreach($householdStats->sortByDesc('tasks_completed_count') as $stat)
                            @php
                                $completionPercentage = ($stat->tasks_completed_count / $totalCompleted) * 100;
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center flex-1">
                                    <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-green-600 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm font-medium text-white">{{ substr($stat->user->name, 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $stat->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $stat->tasks_completed_count }} tasks ({{ round($completionPercentage, 1) }}%)</p>
                                        <p class="text-xs text-green-600">{{ round($stat->completion_rate, 1) }}% completion rate</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ $stat->points }} pts</p>
                                    <div class="w-24 bg-gray-200 rounded-full h-2 ml-3 mt-1">
                                        <div class="bg-green-600 h-2 rounded-full transition-all duration-500" style="width: {{ $completionPercentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Fairness Indicator -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Task Distribution Balance</h4>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-gray-600">Distribution Score</span>
                            @php
                                $creationValues = $householdStats->pluck('tasks_created_count');
                                $creationMean = $creationValues->avg();
                                if ($creationValues->count() > 1 && $creationMean > 0) {
                                    $variance = $creationValues->map(fn($x) => pow($x - $creationMean, 2))->avg();
                                    $creationStdDev = sqrt($variance);
                                    $fairnessScore = max(0, 100 - (($creationStdDev / $creationMean) * 100));
                                } else {
                                    $fairnessScore = 100;
                                }
                                $fairnessLabel = $fairnessScore >= 80 ? 'Excellent' : ($fairnessScore >= 60 ? 'Good' : 'Needs Work');
                                $fairnessColor = $fairnessScore >= 80 ? 'text-green-600' : ($fairnessScore >= 60 ? 'text-yellow-600' : 'text-red-600');
                            @endphp
                            <span class="text-sm font-medium {{ $fairnessColor }}">{{ $fairnessLabel }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="h-3 rounded-full bg-gradient-to-r from-green-400 to-green-600 transition-all duration-500" style="width: {{ $fairnessScore }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Priority Distribution Chart -->
    <div class="glass-card rounded-xl shadow-sm mb-8">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Task Priority Distribution</h3>
            @php
                $priorityCounts = [
                    'urgent' => $allTasks->where('priority', 'urgent')->where('completed_at', null)->count(),
                    'high' => $allTasks->where('priority', 'high')->where('completed_at', null)->count(),
                    'medium' => $allTasks->where('priority', 'medium')->where('completed_at', null)->count(),
                    'low' => $allTasks->where('priority', 'low')->where('completed_at', null)->count(),
                ];
                $totalTasks = array_sum($priorityCounts) ?: 1;
            @endphp
            <div class="grid grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="relative">
                        <div class="flex mb-2 items-center justify-between">
                            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-red-600 bg-red-100">
                                Urgent
                            </span>
                            <span class="text-xs font-semibold text-gray-600">{{ $priorityCounts['urgent'] }}</span>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-red-100">
                            <div style="width: {{ ($priorityCounts['urgent'] / $totalTasks) * 100 }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-red-500 transition-all duration-500"></div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <div class="relative">
                        <div class="flex mb-2 items-center justify-between">
                            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-orange-600 bg-orange-100">
                                High
                            </span>
                            <span class="text-xs font-semibold text-gray-600">{{ $priorityCounts['high'] }}</span>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-orange-100">
                            <div style="width: {{ ($priorityCounts['high'] / $totalTasks) * 100 }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-orange-500 transition-all duration-500"></div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <div class="relative">
                        <div class="flex mb-2 items-center justify-between">
                            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-yellow-600 bg-yellow-100">
                                Medium
                            </span>
                            <span class="text-xs font-semibold text-gray-600">{{ $priorityCounts['medium'] }}</span>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-yellow-100">
                            <div style="width: {{ ($priorityCounts['medium'] / $totalTasks) * 100 }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-yellow-500 transition-all duration-500"></div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <div class="relative">
                        <div class="flex mb-2 items-center justify-between">
                            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-600 bg-green-100">
                                Low
                            </span>
                            <span class="text-xs font-semibold text-gray-600">{{ $priorityCounts['low'] }}</span>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-green-100">
                            <div style="width: {{ ($priorityCounts['low'] / $totalTasks) * 100 }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500 transition-all duration-500"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Current Tasks -->
    <div class="glass-card rounded-xl shadow-sm">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">My Current Tasks</h3>
                <a href="{{ route('tasks.create') }}" class="btn-primary text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Task
                </a>
            </div>

            @if($myTasks->count() > 0)
                <div class="space-y-4">
                    @foreach($myTasks as $task)
                    <div class="flex items-center p-4 bg-white rounded-lg border-2 border-gray-100 hover:border-indigo-200 transition-all duration-200 task-item">
                        <div class="priority-line {{
                            $task->priority === 'urgent' ? 'bg-red-500' :
                            ($task->priority === 'high' ? 'bg-orange-500' :
                            ($task->priority === 'medium' ? 'bg-yellow-500' : 'bg-green-500'))
                        }}"></div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900">{{ $task->title }}</h4>
                            <p class="text-xs text-gray-500">
                                {{ $task->category->icon }} {{ $task->category->name }}
                                @if($task->due_date)
                                    • Due:
                                    @if($task->isOverdue())
                                        <span class="text-red-600 font-medium">{{ $task->due_date->format('M j') }} (Overdue)</span>
                                    @elseif($task->due_date->isToday())
                                        <span class="text-orange-600 font-medium">Today</span>
                                    @elseif($task->due_date->isTomorrow())
                                        <span class="text-yellow-600 font-medium">Tomorrow</span>
                                    @else
                                        {{ $task->due_date->format('M j') }}
                                    @endif
                                @endif
                            </p>
                            @if($task->description)
                                <p class="text-xs text-gray-600 mt-1">{{ Str::limit($task->description, 60) }}</p>
                            @endif
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-800' :
                                   ($task->priority === 'high' ? 'bg-orange-100 text-orange-800' :
                                   ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                            <form method="POST" action="{{ route('tasks.complete', $task) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('tasks.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm transition-colors">
                        View all tasks →
                    </a>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks assigned</h3>
                    <p class="mt-1 text-sm text-gray-500">All caught up! Create a new task to get started.</p>
                    <div class="mt-6">
                        <a href="{{ route('tasks.create') }}" class="btn-primary text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create Your First Task
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Activity -->
    @if($recentActivity->count() > 0)
    <div class="glass-card rounded-xl shadow-sm mt-8">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Recent Activity</h3>
            <div class="space-y-4">
                @foreach($recentActivity->take(5) as $activity)
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-gradient-to-r from-indigo-400 to-indigo-600 rounded-full flex items-center justify-center">
                            <span class="text-xs font-medium text-white">{{ substr($activity->user->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm text-gray-900">
                            <span class="font-medium">{{ $activity->user->name }}</span>
                            <span class="text-gray-600">{{ $activity->description }}</span>
                        </p>
                        <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                    @if($activity->task && !$activity->task->trashed())
                        <div class="flex-shrink-0">
                            <a href="{{ route('tasks.show', $activity->task) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                View task
                            </a>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<!-- JavaScript for interactivity -->
<script>
    let currentDate = new Date();

    function updateCalendarHeader() {
        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"];
        document.getElementById('currentMonth').textContent =
            `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
    }

    function previousMonth() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        updateCalendarHeader();
        // In a real implementation, you would reload the calendar data here
        window.location.reload();
    }

    function nextMonth() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        updateCalendarHeader();
        // In a real implementation, you would reload the calendar data here
        window.location.reload();
    }

    function switchStatsView(view) {
        const createdStats = document.getElementById('created-stats');
        const completedStats = document.getElementById('completed-stats');
        const createdBtn = document.getElementById('created-btn');
        const completedBtn = document.getElementById('completed-btn');

        // Reset button styles
        createdBtn.classList.remove('bg-indigo-50', 'text-indigo-700');
        createdBtn.classList.add('text-gray-500', 'hover:text-gray-700');
        completedBtn.classList.remove('bg-indigo-50', 'text-indigo-700');
        completedBtn.classList.add('text-gray-500', 'hover:text-gray-700');

        if (view === 'created') {
            createdStats.classList.remove('hidden');
            completedStats.classList.add('hidden');
            createdBtn.classList.remove('text-gray-500', 'hover:text-gray-700');
            createdBtn.classList.add('bg-indigo-50', 'text-indigo-700');
        } else {
            createdStats.classList.add('hidden');
            completedStats.classList.remove('hidden');
            completedBtn.classList.remove('text-gray-500', 'hover:text-gray-700');
            completedBtn.classList.add('bg-indigo-50', 'text-indigo-700');
        }
    }

    // Initialize calendar on load
    document.addEventListener('DOMContentLoaded', function() {
        updateCalendarHeader();
    });
</script>
@endsection
