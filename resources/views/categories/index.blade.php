@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Categories</h1>
            <p class="text-gray-600">Organize your tasks with categories</p>
        </div>
        <a href="{{ route('categories.create') }}"
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create Category
        </a>
    </div>

    <!-- Categories Grid -->
    @if($categories->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            @if($category->icon)
                                <span class="text-2xl mr-3">{{ $category->icon }}</span>
                            @endif
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $category->tasks_count }} tasks</p>
                            </div>
                        </div>
                        <div class="w-4 h-4 rounded-full" style="background-color: {{ $category->color }}"></div>
                    </div>

                    <div class="mt-4 flex justify-between items-center">
                        <a href="{{ route('categories.show', $category) }}"
                           class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            View Tasks
                        </a>
                        <div class="flex space-x-2">
                            <a href="{{ route('categories.edit', $category) }}"
                               class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2h0l3.5-2.5"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No categories</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating your first category.</p>
            <div class="mt-6">
                <a href="{{ route('categories.create') }}"
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Category
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
