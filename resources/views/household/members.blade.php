@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Manage Household Members</h1>
        <p class="text-gray-600 mt-1">Manage members of {{ $household->name }}</p>
    </div>

    <!-- Members List -->
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Members</h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($members as $member)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-400 to-purple-600 rounded-full flex items-center justify-center mr-4">
                            <span class="text-lg font-bold text-white">{{ substr($member->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $member->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $member->email }}</p>
                            <div class="flex items-center mt-1">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{
                                    $member->role === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'
                                }}">
                                    {{ ucfirst($member->role) }}
                                </span>
                                @if($member->id === Auth::id())
                                    <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        You
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($member->id !== Auth::id())
                    <div class="flex space-x-2">
                        <form method="POST" action="{{ route('household.members.toggle-role', $member) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-3 py-2 text-sm font-medium rounded-md {{ $member->role === 'admin' ? 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50' : 'text-blue-700 bg-blue-50 border border-blue-200 hover:bg-blue-100' }} transition-colors">
                                {{ $member->role === 'admin' ? 'Remove Admin' : 'Make Admin' }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('household.members.remove', $member) }}" onsubmit="return confirm('Are you sure you want to remove {{ $member->name }} from the household? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-2 text-sm font-medium rounded-md text-red-700 bg-red-50 border border-red-200 hover:bg-red-100 transition-colors">
                                Remove
                            </button>
                        </form>
                    </div>
                    @else
                    <a href="{{ route('household.leave') }}" class="px-3 py-2 text-sm font-medium rounded-md text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                        Leave Household
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-8">
        <a href="{{ route('household.show') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Household
        </a>
    </div>
</div>
@endsection
