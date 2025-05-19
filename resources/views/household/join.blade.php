@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Join a Household
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Enter the household join key to connect with your family or roommates.
            </p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('household.join.post') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm">
                <div>
                    <label for="join_key" class="block text-sm font-medium text-gray-700 mb-2">
                        Household Join Key
                    </label>
                    <input
                        id="join_key"
                        name="join_key"
                        type="text"
                        required
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        placeholder="Enter 8-character join key (e.g., ABC12XYZ)"
                        value="{{ old('join_key') }}"
                    >
                    @error('join_key')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <button
                    type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Join Household
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Don't have a join key?
                    <a href="{{ route('household.create') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                        Create a new household
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
