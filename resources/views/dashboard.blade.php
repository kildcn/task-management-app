@extends('layouts.app')

@section('content')
<!-- Dashboard Header -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Good morning, {{ Auth::user()->name }}!</h1>
        <p class="text-gray-600 mt-1">{{ now()->format('l, F j, Y') }}</p>
    </div>

    <!-- Quick Actions Bar -->
    <div class="mb-8">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('tasks.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Task
            </a>
            <a href="{{ route('categories.create') }}"
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a.997.997 0 01-1.414 0l-7-7A1.997 1.997 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                New Category
            </a>
            <a href="{{ route('tasks.index') }}"
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                View All Tasks
            </a>
        </div>
    </div>

    <!-- Main Dashboard Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Tasks -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Today's Tasks -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Today's Tasks</h2>
                </div>
                <div class="p-6">
                    @php
                        $todayTasks = Auth::user()->assignedTasks()
                            ->whereNull('completed_at')
                            ->where(function($query) {
                                $query->whereDate('due_date', today())
                                      ->orWhereNull('due_date');
                            })
                            ->with(['category'])
                            ->orderBy('urgency_score', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp

                    @if($todayTasks->count() > 0)
                        <div class="space-y-4">
                            @foreach($todayTasks as $task)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center">
                                        <div class="w-2 h-8 rounded-full mr-3 {{
                                            $task->priority === 'urgent' ? 'bg-red-500' :
                                            ($task->priority === 'high' ? 'bg-orange-500' :
                                            ($task->priority === 'medium' ? 'bg-yellow-500' : 'bg-green-500'))
                                        }}"></div>
                                        <div>
                                            <h3 class="font-medium text-gray-900">{{ $task->title }}</h3>
                                            <p class="text-sm text-gray-600">
                                                {{ $task->category->icon }} {{ $task->category->name }}
                                                @if($task->due_date)
                                                    • Due {{ $task->due_date->format('g:i A') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <form method="POST" action="{{ route('tasks.complete', $task) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </form>
                                        <a href="{{ route('tasks.show', $task) }}" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">All caught up!</h3>
                            <p class="mt-1 text-sm text-gray-500">You have no tasks for today.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Overdue Tasks -->
            @if($overdueTasks->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-red-200">
                <div class="p-6 border-b border-red-200 bg-red-50">
                    <h2 class="text-xl font-semibold text-red-800">Overdue Tasks</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($overdueTasks->take(3) as $task)
                            <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-2 h-8 bg-red-500 rounded-full mr-3"></div>
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $task->title }}</h3>
                                        <p class="text-sm text-red-600">
                                            Due {{ $task->due_date->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <form method="POST" action="{{ route('tasks.complete', $task) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    <a href="{{ route('tasks.show', $task) }}" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Recent Activity</h2>
                </div>
                <div class="p-6">
                    @if($recentActivity->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentActivity->take(4) as $activity)
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm font-medium text-indigo-600">{{ substr($activity->user->name, 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-900">
                                            <span class="font-medium">{{ $activity->user->name }}</span>
                                            <span class="text-gray-600">{{ $activity->description }}</span>
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No recent activity</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Stats & Overview -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->assignedTasks()->whereNull('completed_at')->count() }}</p>
                            <p class="text-sm text-gray-600">My Tasks</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->completedTasks()->whereDate('completed_at', today())->count() }}</p>
                            <p class="text-sm text-gray-600">Completed Today</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weekly Progress -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Weekly Progress</h3>
                    @php
                        $weekStart = now()->startOfWeek();
                        $tasksThisWeek = Auth::user()->assignedTasks()->where('created_at', '>=', $weekStart)->count();
                        $completedThisWeek = Auth::user()->completedTasks()->where('completed_at', '>=', $weekStart)->count();
                        $weeklyProgress = $tasksThisWeek > 0 ? round(($completedThisWeek / $tasksThisWeek) * 100) : 0;
                    @endphp

                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">{{ $completedThisWeek }} of {{ $tasksThisWeek }} tasks</span>
                        <span class="text-sm font-medium text-gray-900">{{ $weeklyProgress }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-3 rounded-full transition-all duration-500"
                             style="width: {{ $weeklyProgress }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Categories Overview -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Categories</h3>
                </div>
                <div class="p-6">
                    @php
                        $userCategories = Auth::user()->household->categories()
                            ->withCount(['tasks' => function($query) {
                                $query->whereNull('completed_at');
                            }])
                            ->limit(4)
                            ->get();
                    @endphp

                    @if($userCategories->count() > 0)
                        <div class="space-y-3">
                            @foreach($userCategories as $category)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{ $category->color }}"></div>
                                        <span class="text-sm font-medium text-gray-900">{{ $category->icon }} {{ $category->name }}</span>
                                    </div>
                                    <span class="text-sm text-gray-600">{{ $category->tasks_count }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('categories.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                View all categories →
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-500">No categories yet</p>
                            <a href="{{ route('categories.create') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                Create your first category
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Household Quick View -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">{{ Auth::user()->household->name }}</h3>
                </div>
                <div class="p-6">
                    @php
                        $householdMembers = Auth::user()->household->users->take(4);
                        $totalTasks = Auth::user()->household->tasks()->whereNull('completed_at')->count();
                    @endphp

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Active tasks</span>
                            <span class="text-sm font-medium text-gray-900">{{ $totalTasks }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Members</span>
                            <div class="flex -space-x-2">
                                @foreach($householdMembers as $member)
                                    <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center border-2 border-white">
                                        <span class="text-xs font-medium text-gray-700">{{ substr($member->name, 0, 1) }}</span>
                                    </div>
                                @endforeach
                                @if(Auth::user()->household->users->count() > 4)
                                    <div class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center border-2 border-white">
                                        <span class="text-xs text-gray-600">+{{ Auth::user()->household->users->count() - 4 }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('household.show', ['household' => Auth::user()->household->id]) }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
    View household →
</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
