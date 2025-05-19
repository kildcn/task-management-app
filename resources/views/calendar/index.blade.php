@extends('layouts.app')

@section('head')
<style>
    .calendar-day {
        aspect-ratio: 1;
        height: auto;
        min-height: 100px;
        transition: all 0.2s ease;
    }

    .calendar-day:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .calendar-day.other-month {
        opacity: 0.4;
    }

    .calendar-day.today {
        background-color: rgba(79, 70, 229, 0.1);
        border-color: rgba(79, 70, 229, 0.5);
    }

    .task-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 3px;
    }

    .calendar-week {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
    }

    .day-header {
        text-align: center;
        font-weight: 600;
        color: #4F46E5;
        padding: 8px 0;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .calendar-day {
            min-height: 60px;
        }

        .day-number {
            font-size: 0.875rem;
        }

        .task-dot {
            width: 6px;
            height: 6px;
        }
    }

    /* Task Modal */
    .task-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 50;
        justify-content: center;
        align-items: center;
    }

    .task-modal-content {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        width: 90%;
        max-width: 500px;
        max-height: 80vh;
        overflow-y: auto;
    }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Calendar</h1>
                <p class="text-gray-600 mt-1">View and manage your household tasks by date</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('calendar.index', ['date' => $prevMonth->format('Y-m-d')]) }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    {{ $prevMonth->format('M Y') }}
                </a>

                <span class="inline-flex items-center px-4 py-2 border border-indigo-500 rounded-md text-sm font-medium text-indigo-700 bg-indigo-50">
                    {{ $date->format('F Y') }}
                </span>

                <a href="{{ route('calendar.index', ['date' => $nextMonth->format('Y-m-d')]) }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    {{ $nextMonth->format('M Y') }}
                    <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Calendar -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <!-- Calendar Header - Days of the week -->
        <div class="calendar-week border-b border-gray-200 bg-gray-50">
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="day-header py-3">{{ $day }}</div>
            @endforeach
        </div>

        <!-- Calendar Body -->
        <div class="p-4">
            @php
                // Create a carbon instance for the first day shown on the calendar
                // This might be in the previous month as we show a complete grid
                $calendarStart = $firstDay->copy()->startOfWeek();

                // Create a carbon instance for the last day shown on the calendar
                $calendarEnd = $lastDay->copy()->endOfWeek();

                // Determine today's date
                $today = Carbon\Carbon::today();
            @endphp

            @while ($calendarStart <= $calendarEnd)
                @if($calendarStart->dayOfWeek === 0)
                    <div class="calendar-week mb-4">
                @endif

                @php
                    $isCurrentMonth = $calendarStart->month === $date->month;
                    $isToday = $calendarStart->isSameDay($today);
                    $dateKey = $calendarStart->format('Y-m-d');
                    $hasTasks = isset($tasksByDate[$dateKey]);
                    $dayTasks = $hasTasks ? $tasksByDate[$dateKey] : [];

                    // Sort tasks by priority order
                    if ($hasTasks) {
                        usort($dayTasks, function($a, $b) {
                            $priorityOrder = ['urgent' => 1, 'high' => 2, 'medium' => 3, 'low' => 4];
                            return $priorityOrder[$a->priority] <=> $priorityOrder[$b->priority];
                        });
                    }
                @endphp

                <div class="calendar-day p-2 border rounded-lg {{ $isCurrentMonth ? 'bg-white/60' : 'bg-gray-50 other-month' }} {{ $isToday ? 'today' : '' }}"
                     data-date="{{ $dateKey }}" onclick="showDayTasks('{{ $dateKey }}')">
                    <!-- Day number -->
                    <div class="day-number flex justify-between items-center mb-1">
                        <span class="text-sm font-medium {{ $isToday ? 'text-indigo-700' : 'text-gray-700' }}">
                            {{ $calendarStart->format('j') }}
                        </span>
                        @if($isToday)
                            <span class="text-xs bg-indigo-500 text-white px-1.5 py-0.5 rounded-full">Today</span>
                        @endif
                    </div>

                    <!-- Task indicators -->
                    @if($hasTasks)
                        <div class="task-list">
                            @foreach(array_slice($dayTasks, 0, 3) as $task)
                                <div class="flex items-center mb-1 text-xs">
                                    <div class="task-dot" style="background-color: {{ $task->category->color }}"></div>
                                    <span class="truncate {{ $task->isCompleted() ? 'line-through text-gray-400' : '' }}">
                                        {{ Str::limit($task->title, 18) }}
                                    </span>
                                </div>
                            @endforeach

                            @if(count($dayTasks) > 3)
                                <div class="text-xs text-gray-500 pl-3">
                                    +{{ count($dayTasks) - 3 }} more
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                @php $calendarStart->addDay(); @endphp

                @if($calendarStart->dayOfWeek === 0)
                    </div>
                @endif
            @endwhile
        </div>
    </div>

    <!-- Task Preview -->
    <div class="mt-8">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Upcoming Tasks</h2>

            @php
                $upcomingTasks = App\Models\Task::where('household_id', Auth::user()->household_id)
                    ->where('due_date', '>=', now())
                    ->where('completed_at', null)
                    ->orderBy('due_date')
                    ->take(5)
                    ->get();
            @endphp

            @if($upcomingTasks->count() > 0)
                <div class="space-y-4">
                    @foreach($upcomingTasks as $task)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center">
                                <div class="w-2 h-8 rounded-full mr-3 {{
                                    $task->priority === 'urgent' ? 'bg-red-500' :
                                    ($task->priority === 'high' ? 'bg-orange-500' :
                                    ($task->priority === 'medium' ? 'bg-yellow-500' : 'bg-green-500'))
                                }}"></div>
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $task->title }}</h3>
                                    <p class="text-sm text-gray-600">
                                        Due {{ $task->due_date->format('D, M j') }}
                                        @if($task->assignee)
                                            • Assigned to {{ $task->assignee->name }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('tasks.show', $task) }}" class="text-indigo-600 hover:text-indigo-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No upcoming tasks scheduled.</p>
            @endif

            <div class="mt-6">
                <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create New Task
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Task Modal for Day View -->
<div id="taskModal" class="task-modal">
    <div class="task-modal-content p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 id="modalDate" class="text-xl font-semibold text-gray-900">Tasks for June 15, 2025</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div id="modalTasks" class="space-y-4">
            <!-- Tasks will be populated here -->
            <div class="text-center py-12">
                <div class="spinner w-8 h-8 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin mx-auto"></div>
                <p class="text-gray-500 mt-2">Loading tasks...</p>
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <button onclick="closeModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                Close
            </button>

            <a href="#" id="createTaskForDate" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm">
                Add Task
            </a>
        </div>
    </div>
</div>

<script>
    function showDayTasks(date) {
        const modal = document.getElementById('taskModal');
        const modalDate = document.getElementById('modalDate');
        const modalTasks = document.getElementById('modalTasks');
        const createTaskLink = document.getElementById('createTaskForDate');

        // Show modal
        modal.style.display = 'flex';

        // Format date for display
        const dateObj = new Date(date);
        const formattedDate = dateObj.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        modalDate.textContent = `Tasks for ${formattedDate}`;

        // Set link to create task for this date
        createTaskLink.href = `/tasks/create?due_date=${date}`;

        // Fetch tasks for this date
        fetch(`/calendar/day/${date}`)
            .then(response => response.json())
            .then(data => {
                if (data.tasks.length === 0) {
                    modalTasks.innerHTML = `
                        <div class="text-center py-8">
                            <p class="text-gray-500">No tasks scheduled for this day.</p>
                        </div>
                    `;
                    return;
                }

                let tasksHtml = '';

                data.tasks.forEach(task => {
                    // Determine priority class for color
                    let priorityClass = '';
                    switch(task.priority) {
                        case 'urgent':
                            priorityClass = 'bg-red-500';
                            break;
                        case 'high':
                            priorityClass = 'bg-orange-500';
                            break;
                        case 'medium':
                            priorityClass = 'bg-yellow-500';
                            break;
                        default:
                            priorityClass = 'bg-green-500';
                    }

                    // Create task item
                    tasksHtml += `
                        <div class="p-4 bg-gray-50 rounded-lg ${task.completed ? 'opacity-70' : ''}">
                            <div class="flex items-center">
                                <div class="w-2 h-8 rounded-full mr-3 ${priorityClass}"></div>
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 ${task.completed ? 'line-through' : ''}">
                                        ${task.title}
                                    </h4>
                                    <div class="flex flex-wrap items-center text-xs text-gray-500 mt-1">
                                        <span class="mr-3">Assigned to: ${task.assignee}</span>
                                        <span class="mr-3">Created by: ${task.creator}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                                              style="background-color: ${task.category.color}20; color: ${task.category.color}">
                                            ${task.category.icon || ''} ${task.category.name}
                                        </span>
                                    </div>
                                    ${task.description ? `<p class="text-sm text-gray-600 mt-2">${task.description}</p>` : ''}
                                </div>
                            </div>
                            ${task.completed ?
                                `<div class="mt-2 text-xs text-green-600">
                                    Completed ${task.completed_at} by ${task.completed_by}
                                </div>` : ''}
                            <div class="mt-3 flex justify-end">
                                <a href="${task.url}" class="text-sm text-indigo-600 hover:text-indigo-800">
                                    View Details →
                                </a>
                            </div>
                        </div>
                    `;
                });

                modalTasks.innerHTML = tasksHtml;
            })
            .catch(error => {
                console.error('Error fetching tasks:', error);
                modalTasks.innerHTML = `
                    <div class="text-center py-8">
                        <p class="text-red-500">Error loading tasks. Please try again.</p>
                    </div>
                `;
            });
    }

    function closeModal() {
        const modal = document.getElementById('taskModal');
        modal.style.display = 'none';
    }

    // Close modal when clicking outside of it
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('taskModal');
        if (event.target === modal) {
            closeModal();
        }
    });
</script>
@endsection
