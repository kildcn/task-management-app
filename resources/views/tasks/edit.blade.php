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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-3">Priority</label>
                        <div class="space-y-2">
                            @foreach(['urgent' => ['color' => 'red', 'label' => 'Urgent'],
                                     'high' => ['color' => 'orange', 'label' => 'High'],
                                     'medium' => ['color' => 'yellow', 'label' => 'Medium'],
                                     'low' => ['color' => 'green', 'label' => 'Low']] as $priority => $config)
                                <label class="flex items-center p-2 border border-gray-200 rounded hover:bg-gray-50 cursor-pointer">
                                    <input type="radio"
                                           name="priority"
                                           value="{{ $priority }}"
                                           {{ old('priority', $task->priority) == $priority ? 'checked' : '' }}
                                           class="mr-2 text-{{ $config['color'] }}-500 focus:ring-{{ $config['color'] }}-500">
                                    <div class="w-3 h-3 bg-{{ $config['color'] }}-500 rounded-full mr-2"></div>
                                    <span class="text-sm font-medium text-gray-900">{{ $config['label'] }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('priority')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Difficulty -->
                    <div>
                        <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-3">
                            Difficulty
                            <span class="text-xs text-gray-500 block">Affects completion points (30 × difficulty)</span>
                        </label>
                        <div class="space-y-2">
                            @foreach([1 => ['label' => 'Very Easy', 'points' => 30],
                                     2 => ['label' => 'Easy', 'points' => 60],
                                     3 => ['label' => 'Medium', 'points' => 90],
                                     4 => ['label' => 'Hard', 'points' => 120],
                                     5 => ['label' => 'Very Hard', 'points' => 150]] as $level => $config)
                                <label class="flex items-center justify-between p-2 border border-gray-200 rounded hover:bg-gray-50 cursor-pointer">
                                    <div class="flex items-center">
                                        <input type="radio"
                                               name="difficulty"
                                               value="{{ $level }}"
                                               {{ old('difficulty', $task->difficulty) == $level ? 'checked' : '' }}
                                               class="mr-2 text-indigo-600 focus:ring-indigo-500">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-3 h-3 {{ $i <= $level ? 'text-indigo-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                            @endfor
                                            <span class="ml-2 text-sm font-medium text-gray-900">{{ $config['label'] }}</span>
                                        </div>
                                    </div>
                                    <span class="text-xs font-semibold text-indigo-600">{{ $config['points'] }} pts</span>
                                </label>
                            @endforeach
                        </div>
                        @error('difficulty')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                <!-- Points Summary -->
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-indigo-900 mb-2">Points Summary</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-indigo-700">Creating this task:</span>
                            <span class="font-bold text-indigo-900">100 points</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-indigo-700">Completing this task:</span>
                            <span class="font-bold text-indigo-900" id="completion-points">{{ $task->completion_points }} points</span>
                        </div>
                    </div>
                    <p class="text-xs text-indigo-600 mt-1">Completion points = 30 × difficulty level</p>
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
    const difficultyRadios = document.querySelectorAll('input[name="difficulty"]');
    const completionPointsSpan = document.getElementById('completion-points');

    function toggleRecurrenceSection() {
        if (isRecurringCheckbox.checked) {
            recurrenceSection.classList.remove('hidden');
        } else {
            recurrenceSection.classList.add('hidden');
        }
    }

    function updateCompletionPoints() {
        const selectedDifficulty = document.querySelector('input[name="difficulty"]:checked');
        if (selectedDifficulty) {
            const points = parseInt(selectedDifficulty.value) * 30;
            completionPointsSpan.textContent = points + ' points';
        }
    }

    // Listen for changes
    isRecurringCheckbox.addEventListener('change', toggleRecurrenceSection);

    difficultyRadios.forEach(radio => {
        radio.addEventListener('change', updateCompletionPoints);
    });
});
</script>
@endsection
