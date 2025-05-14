@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Create Category</h1>
        <p class="text-gray-600">Create a new task category for your household</p>
    </div>

    <!-- Enhanced Form -->
    <div class="glass-card rounded-xl shadow-sm">
        <form action="{{ route('categories.store') }}" method="POST" class="p-8">
            @csrf

            <div class="space-y-8">
                <!-- Category Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">Category Name</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name') }}"
                           class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg py-3 px-4"
                           placeholder="e.g., Kitchen, Cleaning, Maintenance"
                           required>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Color Selection -->
                    <div>
                        <label for="color" class="block text-sm font-semibold text-gray-900 mb-3">Color</label>
                        <div class="space-y-3">
                            <!-- Color Picker -->
                            <div class="flex items-center space-x-3">
                                <input type="color"
                                       name="color"
                                       id="color"
                                       value="{{ old('color', '#6B7280') }}"
                                       class="w-12 h-12 border border-gray-300 rounded-lg cursor-pointer">
                                <span class="text-sm text-gray-600">Choose a color to identify this category</span>
                            </div>

                            <!-- Preset Colors -->
                            <div class="grid grid-cols-8 gap-2">
                                @php
                                    $presetColors = [
                                        '#EF4444', '#F97316', '#F59E0B', '#EAB308',
                                        '#84CC16', '#22C55E', '#06B6D4', '#3B82F6',
                                        '#6366F1', '#8B5CF6', '#A855F7', '#EC4899',
                                        '#F43F5E', '#64748B', '#374151', '#1F2937'
                                    ];
                                @endphp
                                @foreach($presetColors as $color)
                                    <button type="button"
                                            class="w-8 h-8 rounded-lg border-2 border-gray-200 hover:border-gray-400 transition-all duration-200"
                                            style="background-color: {{ $color }}"
                                            onclick="document.getElementById('color').value = '{{ $color }}'">
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        @error('color')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Icon Selection -->
                    <div>
                        <label for="icon" class="block text-sm font-semibold text-gray-900 mb-3">Icon</label>
                        <div class="space-y-3">
                            <input type="text"
                                   name="icon"
                                   id="icon"
                                   value="{{ old('icon') }}"
                                   class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 py-3 px-4"
                                   placeholder="Enter an emoji or text">

                            <!-- Suggested Icons -->
                            <div class="grid grid-cols-8 gap-2">
                                @php
                                    $suggestedIcons = [
                                        '🍽️', '🧹', '🔧', '🏠', '🚗', '💼',
                                        '📚', '🎯', '🏃', '🛒', '💰', '🎨',
                                        '🌱', '🧺', '🔑', '📱'
                                    ];
                                @endphp
                                @foreach($suggestedIcons as $emoji)
                                    <button type="button"
                                            class="w-12 h-12 text-2xl border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-gray-300 transition-all duration-200"
                                            onclick="document.getElementById('icon').value = '{{ $emoji }}'">
                                        {{ $emoji }}
                                    </button>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500">Click an emoji above or type your own</p>
                        </div>
                        @error('icon')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Preview -->
                <div class="border-t border-gray-200 pt-8">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Preview</h3>
                    <div class="inline-flex items-center px-4 py-2 rounded-lg transition-all duration-200"
                         id="category-preview"
                         style="background-color: #6B728020;">
                        <span id="preview-icon" class="text-xl mr-2"></span>
                        <span id="preview-name" class="font-medium text-gray-900">Category Name</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-8 border-t border-gray-200 mt-8">
                <a href="{{ route('categories.index') }}"
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                        class="btn-primary text-white px-6 py-3 rounded-lg font-medium transition-colors shadow-sm">
                    Create Category
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const colorInput = document.getElementById('color');
    const iconInput = document.getElementById('icon');
    const preview = document.getElementById('category-preview');
    const previewIcon = document.getElementById('preview-icon');
    const previewName = document.getElementById('preview-name');

    function updatePreview() {
        const name = nameInput.value || 'Category Name';
        const color = colorInput.value || '#6B7280';
        const icon = iconInput.value || '';

        previewName.textContent = name;
        previewIcon.textContent = icon;
        preview.style.backgroundColor = color + '20';
        preview.style.borderColor = color + '40';
    }

    nameInput.addEventListener('input', updatePreview);
    colorInput.addEventListener('input', updatePreview);
    iconInput.addEventListener('input', updatePreview);

    // Initial preview
    updatePreview();
});
</script>
@endsection
