@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Create New Task</h1>
        <p class="text-gray-600 mt-1">Add a new task to your household</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form action="{{ route('tasks.store') }}" method="POST" class="p-8">
            @csrf

            <!-- Task Title -->
            <div class="mb-8">
                <label for="title" class="block text-sm font-semibold text-gray-900 mb-3">
                    Task Title <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="title"
                       id="title"
                       value="{{ old('title') }}"
                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-lg"
                       placeholder="What needs to be done?"
                       required>
                @error('title')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-8">
                <label for="description" class="block text-sm font-semibold text-gray-900 mb-3">
                    Description
                </label>
                <textarea name="description"
                          id="description"
                          rows="4"
                          class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                          placeholder="Add more details about this task (optional)...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid for form fields -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-gray-900 mb-3">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="category_id"
                                id="category_id"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        {{ old('category_id', request('category')) == $category->id ? 'selected' : '' }}>
                                    {{ $category->icon ? $category->icon . ' ' : '' }}{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-3 top-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    @error('category_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Assignee -->
                <div>
                    <label for="assignee_id" class="block text-sm font-semibold text-gray-900 mb-3">
                        Assign To
                    </label>
                    <div class="relative">
                        <select name="assignee_id"
                                id="assignee_id"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Assign to yourself</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}"
                                        {{ old('assignee_id') == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-3 top-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    @error('assignee_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Priority and Due Date Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-semibold text-gray-900 mb-3">
                        Priority <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-3">
                        @foreach(['urgent' => ['color' => 'red', 'label' => 'Urgent'],
                                 'high' => ['color' => 'orange', 'label' => 'High'],
                                 'medium' => ['color' => 'yellow', 'label' => 'Medium'],
                                 'low' => ['color' => 'green', 'label' => 'Low']] as $priority => $config)
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio"
                                       name="priority"
                                       value="{{ $priority }}"
                                       {{ old('priority', 'medium') == $priority ? 'checked' : '' }}
                                       class="mr-3 text-{{ $config['color'] }}-500 focus:ring-{{ $config['color'] }}-500">
                                <div class="w-3 h-3 bg-{{ $config['color'] }}-500 rounded-full mr-3"></div>
                                <span class="font-medium text-gray-900">{{ $config['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('priority')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Due Date -->
                <div>
                    <label for="due_date" class="block text-sm font-semibold text-gray-900 mb-3">
                        Due Date
                    </label>
                    <input type="datetime-local"
                           name="due_date"
                           id="due_date"
                           value="{{ old('due_date') }}"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('due_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estimated Duration -->
                <div>
                    <label for="estimated_duration" class="block text-sm font-semibold text-gray-900 mb-3">
                        Estimated Duration
                    </label>
                    <div class="relative">
                        <input type="number"
                               name="estimated_duration"
                               id="estimated_duration"
                               value="{{ old('estimated_duration') }}"
                               min="1"
                               class="block w-full px-4 py-3 pr-20 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="30">
                        <div class="absolute right-3 top-3 text-gray-500">minutes</div>
                    </div>
                    @error('estimated_duration')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Recurring Task Section -->
            <div class="border border-gray-200 rounded-lg p-6 mb-8">
                <div class="flex items-center mb-4">
                    <input type="checkbox"
                           name="is_recurring"
                           id="is_recurring"
                           value="1"
                           {{ old('is_recurring') ? 'checked' : '' }}
                           class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="is_recurring" class="ml-3 text-sm font-semibold text-gray-900">
                        Make this a recurring task
                    </label>
                </div>

                <!-- Recurrence Pattern -->
                <div id="recurrence_section" class="hidden">
                    <label for="recurrence_pattern" class="block text-sm font-semibold text-gray-900 mb-3">
                        Recurrence Pattern
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @foreach(['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly'] as $pattern => $label)
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio"
                                       name="recurrence_pattern"
                                       value="{{ $pattern }}"
                                       {{ old('recurrence_pattern') == $pattern ? 'checked' : '' }}
                                       class="mr-3 text-indigo-600 focus:ring-indigo-500">
                                <span class="font-medium text-gray-900">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('recurrence_pattern')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row justify-end gap-4">
                <a href="{{ route('tasks.index') }}"
                   class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center px-8 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Task
                </button>
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

    // Initial check
    toggleRecurrenceSection();

    // Listen for changes
    isRecurringCheckbox.addEventListener('change', toggleRecurrenceSection);
});
</script>
@endsection
