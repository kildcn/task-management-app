@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Category</h1>
        <p class="text-gray-600">Update category details</p>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('categories.update', $category) }}" method="POST" class="px-4 py-5 sm:p-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Category Name</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $category->name) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           required>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color -->
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                    <div class="mt-1 flex items-center space-x-3">
                        <input type="color"
                               name="color"
                               id="color"
                               value="{{ old('color', $category->color) }}"
                               class="h-10 w-20 border border-gray-300 rounded cursor-pointer">
                        <span class="text-sm text-gray-500">Choose a color to identify this category</span>
                    </div>
                    @error('color')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Icon -->
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700">Icon (Optional)</label>
                    <input type="text"
                           name="icon"
                           id="icon"
                           value="{{ old('icon', $category->icon) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="e.g., 🍽️, 🧹, 🔧">
                    <p class="mt-1 text-xs text-gray-500">You can use an emoji or text as an icon</p>
                    @error('icon')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex items-center justify-between">
                <div>
                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('Are you sure you want to delete this category? This action cannot be undone.')"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                            Delete Category
                        </button>
                    </form>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('categories.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Update Category
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
