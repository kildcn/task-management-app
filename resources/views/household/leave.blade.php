@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="glass-card rounded-xl p-8 shadow-lg">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Leave Household</h2>
            <p class="mt-2 text-gray-600">Are you sure you want to leave {{ Auth::user()->household->name }}?</p>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L12.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <span class="text-sm text-yellow-700">
                    Leaving will remove all your stats and task associations. This action cannot be undone.
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('household.leave.post') }}">
            @csrf
            <div class="flex justify-between items-center">
                <a href="{{ route('household.show') }}" class="btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-primary bg-red-600 hover:bg-red-700">
                    Leave Household
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
