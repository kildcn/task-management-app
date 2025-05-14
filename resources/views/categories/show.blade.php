@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div class="flex items-center">
            @if($category->icon)
                <span class="text-3xl mr-3">{{ $category->icon }}</span>
            @endif
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
                <p class="text-gray-600">{{ $category->tasks_count }} tasks in this category</p>
            </div>
            <div class="ml-4 w-6 h-6 rounded-full" style="background-color: {{ $category->color }}"></div>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('tasks.create', ['category' => $category->id]) }}"
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Task
            </a>
            <a href="{{ route('categories.edit', $category) }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Category
            </a>
        </div>
    </div>

    <!-- Tasks List -->
    @if($tasks->count() > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($tasks as $task)
                <li>
                    <div class="px-4 py-4 flex items-center justify-between hover:bg-gray-50">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($task->isCompleted())
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center">
                                    <h3 class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('tasks.show', $task) }}" class="hover:text-indigo-600">
                                            {{ $task->title }}
                                        </a>
                                    </h3>
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($task->priority === 'urgent') bg-red-100 text-red-800
                                        @elseif($task->priority === 'high') bg-orange-100 text-orange-800
                                        @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800
                                        @endif">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </div>
                                <div class="mt-1 flex items-center text-sm text-gray-500">
                                    <span>Assigned to: {{ $task->assignee->name ?? 'Unassigned' }}</span>
                                    @if($task->due_date)
                                        <span class="mx-2">•</span>
                                        <span class="@if($task->isOverdue()) text-red-600 font-medium @endif">
                                            Due: {{ $task->due_date->format('M j, Y') }}
                                        </span>
                                    @endif
                                    @if($task->isCompleted())
                                        <span class="mx-2">•</span>
                                        <span>Completed {{ $task->completed_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                                @if($task->description)
                                    <p class="mt-1 text-sm text-gray-600">{{ Str::limit($task->description, 100) }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if(!$task->isCompleted())
                                <form method="POST" action="{{ route('tasks.complete', $task) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-green-600 hover:text-green-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('tasks.reopen', $task) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-orange-600 hover:text-orange-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('tasks.edit', $task) }}" class="text-indigo-600 hover:text-indigo-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>

        <!-- Pagination -->
        @if($tasks->hasPages())
            <div class="mt-6">
                {{ $tasks->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks in this category</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating your first task.</p>
            <div class="mt-6">
                <a href="{{ route('tasks.create', ['category' => $category->id]) }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Task
                </a>
            </div>
        </div>
    @endif

    <!-- Back Button -->
    <div class="mt-8">
        <a href="{{ route('categories.index') }}"
           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Categories
        </a>
    </div>
</div>
@endsection
