@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Task</h1>
        <p class="text-gray-600">Update task details</p>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('tasks.update', $task) }}" method="POST" class="px-4 py-5 sm:p-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 gap-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Task Title</label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title', $task->title) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           required>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                    <textarea name="description"
                              id="description"
                              rows="3"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                              placeholder="Add more details about this task...">{{ old('description', $task->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category_id"
                                id="category_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        {{ old('category_id', $task->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->icon ? $category->icon . ' ' : '' }}{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Assignee -->
                    <div>
                        <label for="assignee_id" class="block text-sm font-medium text-gray-700">Assign To</label>
                        <select name="assignee_id"
                                id="assignee_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select assignee</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}"
                                        {{ old('assignee_id', $task->assignee_id) == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assignee_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                        <select name="priority"
                                id="priority"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                            <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ old('priority', $task->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @error('priority')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date (Optional)</label>
                        <input type="datetime-local"
                               name="due_date"
                               id="due_date"
                               value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : '') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('due_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estimated Duration -->
                    <div>
                        <label for="estimated_duration" class="block text-sm font-medium text-gray-700">Estimated Duration (minutes)</label>
                        <input type="number"
                               name="estimated_duration"
                               id="estimated_duration"
                               value="{{ old('estimated_duration', $task->estimated_duration) }}"
                               min="1"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('estimated_duration')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Recurring Task -->
                <div>
                    <div class="flex items-center">
                        <input type="checkbox"
                               name="is_recurring"
                               id="is_recurring"
                               value="1"
                               {{ old('is_recurring', $task->is_recurring) ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="is_recurring" class="ml-2 block text-sm text-gray-900">
                            Make this a recurring task
                        </label>
                    </div>
                    @error('is_recurring')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Recurrence Pattern (shown only if recurring is checked) -->
                <div id="recurrence_section" class="{{ old('is_recurring', $task->is_recurring) ? '' : 'hidden' }}">
                    <label for="recurrence_pattern" class="block text-sm font-medium text-gray-700">Recurrence Pattern</label>
                    <select name="recurrence_pattern"
                            id="recurrence_pattern"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Select pattern</option>
                        <option value="daily" {{ old('recurrence_pattern', $task->recurrence_pattern) == 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ old('recurrence_pattern', $task->recurrence_pattern) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ old('recurrence_pattern', $task->recurrence_pattern) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                    @error('recurrence_pattern')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex items-center justify-between">
                <div>
                    @if(!$task->isCompleted())
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Are you sure you want to delete this task? This action cannot be undone.')"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                Delete Task
                            </button>
                        </form>
                    @endif
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('tasks.show', $task) }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Update Task
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isRecurringCheckbox = document.getElementById('is_recurring');
    const recurrenceSection = document.getElementById('recurrence_section');

    function toggleRecurrenceSection() {
        if (isRecurringCheckbox.checked) {
            recurrenceSection.classList.remove('hidden');
        } else {
            recurrenceSection.classList.add('hidden');
        }
    }

    // Listen for changes
    isRecurringCheckbox.addEventListener('change', toggleRecurrenceSection);
});
</script>
@endsection
