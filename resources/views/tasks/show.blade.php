@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                      style="background-color: {{ $task->category->color }}20; color: {{ $task->category->color }}">
                    {{ $task->category->icon }} {{ $task->category->name }}
                </span>
                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    @if($task->priority === 'urgent') bg-red-100 text-red-800
                    @elseif($task->priority === 'high') bg-orange-100 text-orange-800
                    @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                    @else bg-green-100 text-green-800
                    @endif">
                    {{ ucfirst($task->priority) }} Priority
                </span>
                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    @for($i = 1; $i <= $task->difficulty; $i++)
                        ⭐
                    @endfor
                    {{ $task->difficulty_label }}
                </span>
                @if($task->isCompleted())
                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        ✓ Completed
                    </span>
                @endif
            </div>
            <div class="flex space-x-3">
                @if(!$task->isCompleted())
                    <form method="POST" action="{{ route('tasks.complete', $task) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Mark Complete ({{ $task->completion_points }} pts)
                        </button>
                    </form>
                @endif
                <a href="{{ route('tasks.edit', $task) }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Edit
                </a>
            </div>
        </div>
        <h1 class="mt-4 text-3xl font-bold text-gray-900">{{ $task->title }}</h1>
    </div>

    <!-- Task Details -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Task Details</h3>

            @if($task->description)
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Description</h4>
                    <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $task->description }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-700">Assigned to</h4>
                    <p class="mt-1 text-sm text-gray-900">{{ $task->assignee->name ?? 'Unassigned' }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-700">Created by</h4>
                    <p class="mt-1 text-sm text-gray-900">{{ $task->creator->name }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-700">Priority</h4>
                    <p class="mt-1 text-sm text-gray-900">{{ ucfirst($task->priority) }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-700">Difficulty</h4>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ $task->difficulty_label }}
                        @for($i = 1; $i <= $task->difficulty; $i++)
                            ⭐
                        @endfor
                    </p>
                </div>
                @if($task->due_date)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700">Due Date</h4>
                        <p class="mt-1 text-sm {{ $task->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-900' }}">
                            {{ $task->due_date->format('M j, Y \a\t g:i A') }}
                            @if($task->isOverdue())
                                <span class="text-xs">(Overdue)</span>
                            @endif
                        </p>
                    </div>
                @endif
                @if($task->estimated_duration)
                    <div>
                        <h4 class="text-sm font-medium text-gray-700">Estimated Duration</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ $task->estimated_duration }} minutes</p>
                    </div>
                @endif
            </div>

            <!-- Points Information -->
            <div class="mt-6 bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-indigo-900 mb-2">Points Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-indigo-700">Creating this task:</span>
                        <span class="font-bold text-indigo-900">{{ $task->creation_points }} points</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-indigo-700">Completing this task:</span>
                        <span class="font-bold text-indigo-900">{{ $task->completion_points }} points</span>
                    </div>
                </div>
                @if($task->isCompleted())
                    <p class="text-xs text-green-700 mt-2 font-medium">
                        ✓ {{ $task->completedBy->name }} earned {{ $task->completion_points }} points for completing this task
                    </p>
                @else
                    <p class="text-xs text-indigo-600 mt-2">Complete this task to earn {{ $task->completion_points }} points!</p>
                @endif
            </div>

            @if($task->isCompleted())
                <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-green-900 mb-2">Task Completed</h4>
                    <p class="text-sm text-green-700">
                        Completed by {{ $task->completedBy->name }} on {{ $task->completed_at->format('M j, Y \a\t g:i A') }}
                        ({{ $task->completed_at->diffForHumans() }})
                    </p>
                </div>
            @endif

            @if($task->is_recurring)
                <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-blue-900 mb-2">Recurring Task</h4>
                    <p class="text-sm text-blue-700">This task repeats {{ $task->recurrence_pattern }}.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-8">
        <a href="{{ route('tasks.index') }}"
           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Tasks
        </a>
    </div>
</div>
@endsection
