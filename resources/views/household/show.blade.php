@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ $household->name }}</h1>
        @if($household->description)
            <p class="text-gray-600 mt-2">{{ $household->description }}</p>
        @endif
        <div class="mt-2 flex items-center text-sm text-gray-500">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ $household->timezone }}</span>
            <span class="mx-2">•</span>
            <span>Created {{ $household->created_at->format('M j, Y') }}</span>
        </div>
    </div>

    <!-- Household Join Key Section -->
    @if(Auth::user()->role === 'admin')
    <div class="bg-indigo-50 rounded-xl border border-indigo-200 p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-indigo-900">Household Join Key</h2>
                <p class="text-sm text-indigo-700 mt-1">Share this key with others to let them join your household</p>
            </div>
            <form action="{{ route('household.regenerate-key') }}" method="POST" class="ml-4">
                @csrf
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                    Regenerate Key
                </button>
            </form>
        </div>

        <div class="mt-4 bg-white rounded-lg p-4 flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-indigo-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
                <span class="text-lg font-mono font-semibold tracking-wider text-gray-900">{{ $household->join_key }}</span>
            </div>
            <button type="button"
                   onclick="navigator.clipboard.writeText('{{ $household->join_key }}').then(() => alert('Copied to clipboard!'))"
                   class="px-3 py-1 border border-gray-300 rounded-lg text-gray-700 text-sm hover:bg-gray-50 transition-colors">
                Copy
            </button>
        </div>

        <p class="text-xs text-indigo-600 mt-2">Note: Regenerating the key will invalidate the previous one.</p>
    </div>
    @endif

    <!-- Household Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
</svg>

                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $members->count() }}</p>
                    <p class="text-sm text-gray-600">Members</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $household->tasks()->whereNull('completed_at')->count() }}</p>
                    <p class="text-sm text-gray-600">Active Tasks</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $household->tasks()->whereNotNull('completed_at')->count() }}</p>
                    <p class="text-sm text-gray-600">Completed Tasks</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats->sum('points') }}</p>
                    <p class="text-sm text-gray-600">Total Points</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Members List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Household Members</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($members as $member)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
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
                                        @if($member->created_at->isToday())
                                            <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                New
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                @php $memberStats = $stats->where('user_id', $member->id)->first() @endphp
                                @if($memberStats)
                                    <p class="text-lg font-bold text-gray-900">{{ $memberStats->points }} pts</p>
                                    <p class="text-sm text-gray-600">{{ $memberStats->tasks_completed_count }} completed</p>
                                    @if($memberStats->current_streak_days > 0)
                                        <div class="flex items-center justify-end mt-1">
                                            <svg class="w-4 h-4 text-orange-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-xs text-orange-600 font-medium">{{ $memberStats->current_streak_days }}d</span>
                                        </div>
                                    @endif
                                @else
                                    <p class="text-sm text-gray-400">No activity yet</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Leaderboard & Quick Actions -->
        <div class="space-y-6">
            <!-- Points Leaderboard -->
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Points Leaderboard</h2>
                </div>
                <div class="p-6">
                    @if($stats->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($stats->sortByDesc('points')->take(5) as $index => $stat)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 flex items-center justify-center mr-3">
                                        @if($index === 0)
                                            <span class="text-2xl">🥇</span>
                                        @elseif($index === 1)
                                            <span class="text-2xl">🥈</span>
                                        @elseif($index === 2)
                                            <span class="text-2xl">🥉</span>
                                        @else
                                            <span class="text-sm font-medium text-gray-500">#{{ $index + 1 }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $stat->user->name }}</p>
                                        <p class="text-xs text-gray-600">
                                            {{ $stat->tasks_completed_count }} tasks completed
                                            @if($stat->current_streak_days > 0)
                                                • {{ $stat->current_streak_days }} day streak
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-gray-900">{{ $stat->points }}</p>
                                    <p class="text-xs text-gray-500">points</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No activity yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Start creating and completing tasks to earn points!</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Quick Actions</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">

                        @if(Auth::user()->role === 'admin')
                    <a href="{{ route('household.members') }}" class="flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors border border-gray-200">
                        <svg class="w-5 h-5 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                          Manage Members
                    </a>
                        @else
                    <a href="{{ route('household.leave') }}" class="flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors border border-gray-200">
                        <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Leave Household
                    </a>
                    @endif
                        <a href="{{ route('tasks.create') }}"
                           class="flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors border border-gray-200">
                            <svg class="w-5 h-5 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create New Task
                        </a>

                        <a href="{{ route('tasks.index') }}"
                           class="flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors border border-gray-200">
                            <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            View All Tasks
                        </a>

                        <a href="{{ route('calendar.index') }}"
                           class="flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors border border-gray-200">
                            <svg class="w-5 h-5 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Calendar View
                        </a>

                        <a href="{{ route('categories.index') }}"
                           class="flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors border border-gray-200">
                            <svg class="w-5 h-5 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2h0l3.5-2.5"></path>
                            </svg>
                            Manage Categories
                        </a>

                        <a href="{{ route('stats.index') }}"
                           class="flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors border border-gray-200">
                            <svg class="w-5 h-5 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            View Analytics
                        </a>

                        <a href="{{ route('dashboard') }}"
                           class="flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors border border-gray-200">
                            <svg class="w-5 h-5 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2H3z"></path>
                            </svg>
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>

            <!-- Household Info -->
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Household Info</h2>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Household Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $household->name }}</dd>
                        </div>
                        @if($household->description)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $household->description }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Timezone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $household->timezone }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $household->created_at->format('M j, Y \a\t g:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Categories</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $household->categories()->count() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
