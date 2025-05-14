@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Tasks</h1>
            <p class="text-gray-600">Manage your household tasks</p>
        </div>
        <a href="{{ route('tasks.create') }}" class="btn-primary text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create Task
        </a>
    </div>

    <!-- Enhanced Task List -->
    @if($tasks->count() > 0)
        <div class="space-y-4">
            @foreach($tasks as $task)
            <div class="glass-card rounded-lg p-6 hover:shadow-lg transition-all duration-200 task-item">
                <div class="flex items-center justify-between">
                    <div class="flex items-center flex-1">
                        <!-- Priority Line -->
                        <div class="priority-line {{
                            $task->priority === 'urgent' ? 'bg-red-500' :
                            ($task->priority === 'high' ? 'bg-orange-500' :
                            ($task->priority === 'medium' ? 'bg-yellow-500' : 'bg-green-500'))
                        }}"></div>

                        <!-- Task Icon -->
                        <div class="flex-shrink-0 mr-4">
                            @if($task->isCompleted())
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Task Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 mr-3">
                                    <a href="{{ route('tasks.show', $task) }}" class="hover:text-indigo-600 transition-colors">
                                        {{ $task->title }}
                                    </a>
                                </h3>

                                <!-- Category Badge -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mr-2"
                                      style="background-color: {{ $task->category->color }}20; color: {{ $task->category->color }}">
                                    {{ $task->category->icon }} {{ $task->category->name }}
                                </span>

                                <!-- Priority Badge -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($task->priority === 'urgent') bg-red-100 text-red-800
                                    @elseif($task->priority === 'high') bg-orange-100 text-orange-800
                                    @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </div>

                            <!-- Task Meta -->
                            <div class="flex items-center text-sm text-gray-500 mb-2">
                                <span class="flex items-center mr-4">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $task->assignee->name ?? 'Unassigned' }}
                                </span>

                                @if($task->due_date)
                                    <span class="flex items-center mr-4 {{ $task->isOverdue() ? 'text-red-600 font-medium' : '' }}">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Due: {{ $task->due_date->format('M j, Y') }}
                                        @if($task->isOverdue()) (Overdue) @endif
                                    </span>
                                @endif

                                @if($task->isCompleted())
                                    <span class="flex items-center text-green-600">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Completed {{ $task->completed_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>

                            @if($task->description)
                                <p class="text-gray-600 text-sm">{{ Str::limit($task->description, 150) }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-3">
                        @if(!$task->isCompleted())
                            <form method="POST" action="{{ route('tasks.complete', $task) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('tasks.reopen', $task) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="p-2 text-orange-600 hover:bg-orange-50 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('tasks.edit', $task) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($tasks->hasPages())
            <div class="mt-8">
                {{ $tasks->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="glass-card rounded-xl p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No tasks yet</h3>
            <p class="mt-2 text-gray-500">Get started by creating your first task.</p>
            <div class="mt-6">
                <a href="{{ route('tasks.create') }}" class="btn-primary text-white px-6 py-3 rounded-lg font-medium transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Your First Task
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
