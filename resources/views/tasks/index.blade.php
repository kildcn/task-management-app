@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tasks</h1>
                <p class="text-gray-600 mt-1">Manage and track your household tasks</p>
            </div>
            <a href="{{ route('tasks.create') }}"
               class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create Task
            </a>
        </div>
    </div>

    <!-- Filters & Stats Bar -->
    <div class="mb-6 bg-white rounded-lg border border-gray-200 p-4">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
            <div class="flex items-center">
                <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                <span class="text-gray-600">Total: {{ $tasks->total() }}</span>
            </div>
            <div class="flex items-center">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                <span class="text-gray-600">Completed: {{ $tasks->where('completed_at', '!=', null)->count() }}</span>
            </div>
            <div class="flex items-center">
                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                <span class="text-gray-600">Active: {{ $tasks->where('completed_at', null)->count() }}</span>
            </div>
            <div class="flex items-center">
                <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                <span class="text-gray-600">Overdue: {{ $tasks->where('due_date', '<', now())->where('completed_at', null)->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Tasks List - Compact Version -->
    @if($tasks->count() > 0)
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="divide-y divide-gray-100">
                @foreach($tasks as $task)
                <div class="p-4 hover:bg-gray-50 transition-colors {{ $task->isCompleted() ? 'opacity-75' : '' }}">
                    <div class="flex items-center justify-between">
                        <!-- Left: Task Info -->
                        <div class="flex items-center flex-1 min-w-0">
                            <!-- Priority Indicator -->
                            <div class="w-1 h-10 rounded-full mr-3 flex-shrink-0 {{
                                $task->priority === 'urgent' ? 'bg-red-500' :
                                ($task->priority === 'high' ? 'bg-orange-500' :
                                ($task->priority === 'medium' ? 'bg-yellow-500' : 'bg-green-500'))
                            }}"></div>

                            <!-- Task Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-1">
                                    <h3 class="text-lg font-medium text-gray-900 truncate">
                                        <a href="{{ route('tasks.show', $task) }}"
                                           class="hover:text-indigo-600 transition-colors">
                                            {{ $task->title }}
                                        </a>
                                    </h3>

                                    <!-- Compact Badges -->
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                                              style="background-color: {{ $task->category->color }}20; color: {{ $task->category->color }}">
                                            {{ $task->category->icon }} {{ $task->category->name }}
                                        </span>

                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{
                                            $task->priority === 'urgent' ? 'bg-red-100 text-red-700' :
                                            ($task->priority === 'high' ? 'bg-orange-100 text-orange-700' :
                                            ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700'))
                                        }}">
                                            {{ ucfirst($task->priority) }}
                                        </span>

                                        @if($task->isCompleted())
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">
                                                ✓ Done
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Task Meta Information -->
                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $task->assignee->name ?? 'Unassigned' }}
                                    </span>

                                    @if($task->due_date)
                                        <span class="flex items-center {{ $task->isOverdue() ? 'text-red-600 font-medium' : '' }}">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            @if($task->isOverdue())
                                                Overdue: {{ $task->due_date->format('M j') }}
                                            @elseif($task->due_date->isToday())
                                                Today
                                            @elseif($task->due_date->isTomorrow())
                                                Tomorrow
                                            @else
                                                {{ $task->due_date->format('M j') }}
                                            @endif
                                        </span>
                                    @endif

                                    @if($task->estimated_duration)
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $task->estimated_duration }}min
                                        </span>
                                    @endif

                                    @if($task->isCompleted())
                                        <span class="text-green-600">
                                            Completed {{ $task->completed_at->diffForHumans() }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Description Preview -->
                                @if($task->description)
                                    <p class="text-sm text-gray-600 mt-1 line-clamp-1">{{ Str::limit($task->description, 120) }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Right: Actions -->
                        <div class="flex items-center space-x-1 ml-4 flex-shrink-0">
                            @if(!$task->isCompleted())
                                <form method="POST" action="{{ route('tasks.complete', $task) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors"
                                            title="Mark as complete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('tasks.reopen', $task) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="p-2 text-orange-600 hover:bg-orange-100 rounded-lg transition-colors"
                                            title="Reopen task">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('tasks.edit', $task) }}"
                               class="p-2 text-indigo-600 hover:bg-indigo-100 rounded-lg transition-colors"
                               title="Edit task">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>

                            <a href="{{ route('tasks.show', $task) }}"
                               class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                               title="View details">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        @if($tasks->hasPages())
            <div class="mt-6">
                {{ $tasks->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No tasks yet</h3>
            <p class="mt-2 text-gray-500">Get started by creating your first task.</p>
            <div class="mt-6">
                <a href="{{ route('tasks.create') }}"
                   class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Your First Task
                </a>
            </div>
        </div>
    @endif
</div>

<style>
/* Add line-clamp utility for text truncation */
.line-clamp-1 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 1;
}

/* Ensure long URLs or text don't break layout */
.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>
@endsection
