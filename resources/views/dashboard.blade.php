@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Dashboard Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600">Welcome back, {{ Auth::user()->name }}!</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- My Created Tasks -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Tasks Created</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ Auth::user()->taskStats->tasks_created_count ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Assigned Tasks -->
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
                            <dt class="text-sm font-medium text-gray-500 truncate">Tasks Assigned</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $myTasks->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overdue Tasks -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L12.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Overdue</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $overdueTasks->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completion Rate -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Completion Rate</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ Auth::user()->taskStats->completion_rate ?? 0 }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Calendar and Tasks Overview -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Task Calendar</h3>

                    <!-- Calendar Navigation -->
                    <div class="flex items-center justify-between mb-4">
                        <button class="text-gray-500 hover:text-gray-700" onclick="previousMonth()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <h4 class="text-lg font-semibold text-gray-900" id="currentMonth">
                            {{ now()->format('F Y') }}
                        </h4>
                        <button class="text-gray-500 hover:text-gray-700" onclick="nextMonth()">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Calendar Grid with proper styling -->
                    <div id="calendar-container">
                        <!-- Day headers -->
                        <div class="grid grid-cols-7 gap-1 mb-2">
                            <div class="text-center text-xs font-semibold text-gray-500 py-2">Sun</div>
                            <div class="text-center text-xs font-semibold text-gray-500 py-2">Mon</div>
                            <div class="text-center text-xs font-semibold text-gray-500 py-2">Tue</div>
                            <div class="text-center text-xs font-semibold text-gray-500 py-2">Wed</div>
                            <div class="text-center text-xs font-semibold text-gray-500 py-2">Thu</div>
                            <div class="text-center text-xs font-semibold text-gray-500 py-2">Fri</div>
                            <div class="text-center text-xs font-semibold text-gray-500 py-2">Sat</div>
                        </div>
                        <!-- Calendar days -->
                        <div class="grid grid-cols-7 gap-1" id="calendar-days">
                            <!-- Calendar content will be generated by JavaScript -->
                        </div>
                    </div>

                    <!-- Priority Legend -->
                    <div class="mt-4 flex flex-wrap gap-4">
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

        <!-- Comparative Household Stats -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Household Stats</h3>

                    <!-- Stats Toggle -->
                    <div class="flex rounded-lg border mb-4">
                        <button class="flex-1 py-2 px-3 text-sm font-medium rounded-l-lg bg-blue-50 text-blue-700 border-r"
                                onclick="switchStatsView('created')" id="created-btn">
                            Created
                        </button>
                        <button class="flex-1 py-2 px-3 text-sm font-medium rounded-r-lg text-gray-500 hover:text-gray-700"
                                onclick="switchStatsView('completed')" id="completed-btn">
                            Completed
                        </button>
                    </div>

                    <!-- Created Tasks Stats -->
                    <div id="created-stats" class="space-y-4">
                        <div class="text-xs text-gray-500 mb-2">Task Creation Distribution</div>
                        @php
                            $totalCreated = $householdStats->sum('tasks_created_count') ?: 1;
                        @endphp
                        @foreach($householdStats as $index => $stat)
                        @php
                            $createdPercentage = ($stat->tasks_created_count / $totalCreated) * 100;
                        @endphp
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-sm font-medium text-white">{{ substr($stat->user->name, 0, 1) }}</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $stat->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $stat->tasks_created_count }} tasks ({{ number_format($createdPercentage, 1) }}%)</p>
                                </div>
                            </div>
                            <div class="w-24 bg-gray-200 rounded-full h-2 ml-3">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                     style="width: {{ $createdPercentage }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Completed Tasks Stats (hidden by default) -->
                    <div id="completed-stats" class="space-y-4 hidden">
                        <div class="text-xs text-gray-500 mb-2">Task Completion Distribution</div>
                        @php
                            $totalCompleted = $householdStats->sum('tasks_completed_count') ?: 1;
                        @endphp
                        @foreach($householdStats as $index => $stat)
                        @php
                            $completedPercentage = ($stat->tasks_completed_count / $totalCompleted) * 100;
                            $completionRate = $stat->completion_rate;
                            $completionColor = $completionRate >= 80 ? 'text-green-600' : ($completionRate >= 60 ? 'text-yellow-600' : 'text-red-600');
                        @endphp
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1">
                                <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-green-600 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-sm font-medium text-white">{{ substr($stat->user->name, 0, 1) }}</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $stat->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $stat->tasks_completed_count }} tasks ({{ number_format($completedPercentage, 1) }}%)</p>
                                    <p class="text-xs {{ $completionColor }}">{{ number_format($completionRate, 1) }}% completion rate</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ $stat->points }} pts</p>
                                <div class="w-24 bg-gray-200 rounded-full h-2 ml-3 mt-1">
                                    <div class="bg-green-600 h-2 rounded-full transition-all duration-300"
                                         style="width: {{ $completedPercentage }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Fairness Indicator -->
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Task Distribution Balance</h4>
                        @php
                            $fairnessScore = $householdStats->count() > 1 ?
                                (100 - (($householdStats->max('tasks_created_count') - $householdStats->min('tasks_created_count')) / max(1, $householdStats->avg('tasks_created_count')) * 100)) : 100;
                            $fairnessColor = $fairnessScore >= 80 ? 'text-green-600' : ($fairnessScore >= 60 ? 'text-yellow-600' : 'text-red-600');
                            $fairnessLabel = $fairnessScore >= 80 ? 'Excellent' : ($fairnessScore >= 60 ? 'Good' : 'Needs Balance');
                        @endphp
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-600">Distribution Score</span>
                            <span class="text-sm font-medium {{ $fairnessColor }}">{{ $fairnessLabel }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                            <div class="h-2 rounded-full transition-all duration-300 {{ $fairnessScore >= 80 ? 'bg-green-500' : ($fairnessScore >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                 style="width: {{ max(10, $fairnessScore) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Priority Distribution Chart -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Task Priority Distribution</h3>
            <div class="grid grid-cols-4 gap-4">
                @php
                    $priorityCounts = [
                        'urgent' => $allTasks->where('priority', 'urgent')->where('completed_at', null)->count(),
                        'high' => $allTasks->where('priority', 'high')->where('completed_at', null)->count(),
                        'medium' => $allTasks->where('priority', 'medium')->where('completed_at', null)->count(),
                        'low' => $allTasks->where('priority', 'low')->where('completed_at', null)->count(),
                    ];
                    $totalActive = array_sum($priorityCounts);
                @endphp

                @foreach($priorityCounts as $priority => $count)
                <div class="text-center">
                    <div class="relative pt-1">
                        <div class="flex mb-2 items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full
                                    @if($priority === 'urgent') text-red-600 bg-red-200
                                    @elseif($priority === 'high') text-orange-600 bg-orange-200
                                    @elseif($priority === 'medium') text-yellow-600 bg-yellow-200
                                    @else text-green-600 bg-green-200
                                    @endif">
                                    {{ ucfirst($priority) }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-semibold inline-block text-gray-600">
                                    {{ $count }}
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded
                            @if($priority === 'urgent') bg-red-200
                            @elseif($priority === 'high') bg-orange-200
                            @elseif($priority === 'medium') bg-yellow-200
                            @else bg-green-200
                            @endif">
                            <div style="width:{{ $totalActive > 0 ? ($count / $totalActive) * 100 : 0 }}%"
                                 class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center transition-all duration-300
                                 @if($priority === 'urgent') bg-red-500
                                 @elseif($priority === 'high') bg-orange-500
                                 @elseif($priority === 'medium') bg-yellow-500
                                 @else bg-green-500
                                 @endif"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- My Current Tasks with Priority Visual -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">My Current Tasks</h3>
            @if($myTasks->isEmpty())
                <p class="text-gray-500">No tasks assigned to you.</p>
                <a href="{{ route('tasks.create') }}" class="mt-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    Create Task
                </a>
            @else
                <div class="space-y-3">
                    @foreach($myTasks as $task)
                    <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <!-- Priority Indicator -->
                                <div class="w-1 h-12 rounded-full mr-3
                                    @if($task->priority === 'urgent') bg-red-500
                                    @elseif($task->priority === 'high') bg-orange-500
                                    @elseif($task->priority === 'medium') bg-yellow-500
                                    @else bg-green-500
                                    @endif"></div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">{{ $task->title }}</h4>
                                    <p class="text-xs text-gray-500">{{ $task->category->name }}</p>
                                    @if($task->due_date)
                                        <p class="text-xs {{ $task->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                            Due: {{ $task->due_date->format('M j, Y') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($task->priority === 'urgent') bg-red-100 text-red-800
                                    @elseif($task->priority === 'high') bg-orange-100 text-orange-800
                                    @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('tasks.index') }}" class="mt-3 text-sm text-indigo-600 hover:text-indigo-900">
                    View all tasks →
                </a>
            @endif
        </div>
    </div>
</div>

<script>
// Calendar functionality
let currentDate = new Date();
const currentMonth = document.getElementById('currentMonth');
const calendarDays = document.getElementById('calendar-days');

// Task data from PHP
const tasks = @json($allTasks);

function generateCalendar(date) {
    const year = date.getFullYear();
    const month = date.getMonth();

    // Update month display
    currentMonth.textContent = date.toLocaleString('default', { month: 'long', year: 'numeric' });

    // Clear calendar days
    calendarDays.innerHTML = '';

    // Get first day of month and number of days
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDayOfWeek = firstDay.getDay();

    // Add empty cells before first day
    for (let i = 0; i < startingDayOfWeek; i++) {
        const emptyDay = document.createElement('div');
        emptyDay.className = 'h-20 p-1';
        calendarDays.appendChild(emptyDay);
    }

    // Add days of month
    for (let day = 1; day <= daysInMonth; day++) {
        const dayElement = document.createElement('div');
        dayElement.className = 'h-20 p-1 border border-gray-100 hover:bg-gray-50 relative cursor-pointer rounded transition-colors';

        // Add day number
        const dayNumber = document.createElement('div');
        dayNumber.className = 'text-xs font-medium text-gray-900 mb-1';
        dayNumber.textContent = day;
        dayElement.appendChild(dayNumber);

        // Find tasks for this day
        const dayTasks = tasks.filter(task => {
            if (!task.due_date) return false;
            const taskDate = new Date(task.due_date);
            return taskDate.getDate() === day &&
                   taskDate.getMonth() === month &&
                   taskDate.getFullYear() === year &&
                   !task.completed_at;
        });

        // Add task indicators
        const taskContainer = document.createElement('div');
        taskContainer.className = 'space-y-1';

        dayTasks.slice(0, 2).forEach(task => {
            const taskDot = document.createElement('div');
            let colorClass = 'bg-gray-400';

            switch(task.priority) {
                case 'urgent': colorClass = 'bg-red-500'; break;
                case 'high': colorClass = 'bg-orange-500'; break;
                case 'medium': colorClass = 'bg-yellow-500'; break;
                case 'low': colorClass = 'bg-green-500'; break;
            }

            taskDot.className = `w-full h-2 rounded-sm ${colorClass}`;
            taskDot.title = task.title;
            taskContainer.appendChild(taskDot);
        });

        if (dayTasks.length > 2) {
            const moreIndicator = document.createElement('div');
            moreIndicator.className = 'text-xs text-gray-500 text-center';
            moreIndicator.textContent = `+${dayTasks.length - 2}`;
            taskContainer.appendChild(moreIndicator);
        }

        dayElement.appendChild(taskContainer);
        calendarDays.appendChild(dayElement);
    }
}

function previousMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    generateCalendar(currentDate);
}

function nextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    generateCalendar(currentDate);
}

function switchStatsView(view) {
    const createdStats = document.getElementById('created-stats');
    const completedStats = document.getElementById('completed-stats');
    const createdBtn = document.getElementById('created-btn');
    const completedBtn = document.getElementById('completed-btn');

    // Reset button styles
    createdBtn.classList.remove('bg-blue-50', 'text-blue-700');
    createdBtn.classList.add('text-gray-500', 'hover:text-gray-700');
    completedBtn.classList.remove('bg-blue-50', 'text-blue-700');
    completedBtn.classList.add('text-gray-500', 'hover:text-gray-700');

    if (view === 'created') {
        createdStats.classList.remove('hidden');
        completedStats.classList.add('hidden');
        createdBtn.classList.remove('text-gray-500', 'hover:text-gray-700');
        createdBtn.classList.add('bg-blue-50', 'text-blue-700');
    } else {
        createdStats.classList.add('hidden');
        completedStats.classList.remove('hidden');
        completedBtn.classList.remove('text-gray-500', 'hover:text-gray-700');
        completedBtn.classList.add('bg-blue-50', 'text-blue-700');
    }
}

// Initialize calendar
generateCalendar(currentDate);
</script>
@endsection
