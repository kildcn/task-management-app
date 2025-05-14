@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Join a Household
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Select an existing household to join.
            </p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('household.join.post') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm">
                <div>
                    <label for="household_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Available Households
                    </label>
                    <select
                        id="household_id"
                        name="household_id"
                        required
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    >
                        <option value="">Select a household</option>
                        @foreach($households as $household)
                            <option value="{{ $household->id }}" {{ old('household_id') == $household->id ? 'selected' : '' }}>
                                {{ $household->name }}
                                @if($household->description)
                                    - {{ Str::limit($household->description, 30) }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('household_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            @if($households->isEmpty())
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                No households available
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>There are no households available to join. You can create your own household instead.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div>
                <button
                    type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    {{ $households->isEmpty() ? 'disabled' : '' }}
                >
                    Join Household
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Don't see the household you're looking for?
                    <a href="{{ route('household.create') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Create a new one
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
