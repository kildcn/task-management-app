@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Categories</h1>
                <p class="text-gray-600 mt-1">Organize your tasks with custom categories</p>
            </div>
            <a href="{{ route('categories.create') }}"
               class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-200 font-medium shadow-sm hover:shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Category
            </a>
        </div>
    </div>

    <!-- Categories Grid -->
    @if($categories->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($categories as $category)
            <div class="bg-white rounded-xl border border-gray-200 hover:border-gray-300 hover:shadow-lg transition-all duration-200 group">
                <div class="p-6">
                    <!-- Category Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            @if($category->icon)
                                <span class="text-2xl mr-3">{{ $category->icon }}</span>
                            @else
                                <div class="w-10 h-10 rounded-lg mr-3 flex items-center justify-center"
                                     style="background-color: {{ $category->color }}20;">
                                    <div class="w-5 h-5 rounded" style="background-color: {{ $category->color }}"></div>
                                </div>
                            @endif
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $category->tasks_count }} tasks</p>
                            </div>
                        </div>
                        <div class="w-4 h-4 rounded-full" style="background-color: {{ $category->color }}"></div>
                    </div>

                    <!-- Progress Bar -->
                    @php
                        $completedTasks = $category->tasks->where('completed_at', '!=', null)->count();
                        $totalTasks = $category->tasks_count;
                        $completionPercentage = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                    @endphp
                    <div class="mb-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Progress</span>
                            <span>{{ $completedTasks }}/{{ $totalTasks }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-300"
                                 style="width: {{ $completionPercentage }}%; background-color: {{ $category->color }}"></div>
                        </div>
                    </div>

                    <!-- Category Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <a href="{{ route('categories.show', $category) }}"
                           class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                            View Tasks
                        </a>
                        <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('categories.edit', $category) }}"
                               class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                               title="Edit category">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('tasks.create', ['category' => $category->id]) }}"
                               class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                               title="Add task to category">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Category Summary -->
        <div class="mt-8 bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Category Overview</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2h0l3.5-2.5"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $categories->count() }}</p>
                            <p class="text-sm text-gray-600">Total Categories</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $categories->sum('tasks_count') }}</p>
                            <p class="text-sm text-gray-600">Total Tasks</p>
                        </div>
                    </div>
                </div>

                @php
                    $mostActiveCategoryName = $categories->sortByDesc('tasks_count')->first()?->name ?? 'None';
                    $mostActiveCount = $categories->sortByDesc('tasks_count')->first()?->tasks_count ?? 0;
                @endphp
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-bold text-gray-900">{{ $mostActiveCategoryName }}</p>
                            <p class="text-sm text-gray-600">Most Active ({{ $mostActiveCount }} tasks)</p>
                        </div>
                    </div>
                </div>

                @php
                    $avgTasksPerCategory = $categories->count() > 0 ? round($categories->sum('tasks_count') / $categories->count(), 1) : 0;
                @endphp
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $avgTasksPerCategory }}</p>
                            <p class="text-sm text-gray-600">Avg per Category</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <div class="max-w-md mx-auto">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2h0l3.5-2.5"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No categories yet</h3>
                <p class="mt-2 text-gray-500">Get started by creating your first category to organize your tasks.</p>
                <div class="mt-6">
                    <a href="{{ route('categories.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Your First Category
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
