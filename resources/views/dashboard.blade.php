<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom CSS for better visual design */
        .glass-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .calendar-day {
            transition: all 0.2s ease;
        }

        .calendar-day:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .task-indicator {
            height: 3px;
            margin-bottom: 2px;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-200/50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-2xl font-bold gradient-text">Task Manager</span>
                </div>
                <div class="flex items-center space-x-8">
                    <a href="#" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors">Dashboard</a>
                    <a href="#" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors">Tasks</a>
                    <a href="#" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors">Categories</a>
                    <a href="#" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors">Statistics</a>
                    <div class="flex items-center space-x-3">
                        <span class="text-gray-700 font-medium">John Doe</span>
                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm">
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Welcome back, John!</h1>
            <p class="text-gray-600 mt-2">Here's what's happening with your tasks today.</p>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="glass-card rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Tasks Created</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">24</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-green-600 text-sm font-medium">+12%</span>
                    <span class="text-gray-500 text-sm ml-1">from last month</span>
                </div>
            </div>

            <div class="glass-card rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Tasks Assigned</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">8</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-red-600 text-sm font-medium">-5%</span>
                    <span class="text-gray-500 text-sm ml-1">from last month</span>
                </div>
            </div>

            <div class="glass-card rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Overdue</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">3</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L12.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-orange-600 text-sm font-medium">Needs attention</span>
                </div>
            </div>

            <div class="glass-card rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Completion Rate</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">87%</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-green-600 text-sm font-medium">+8%</span>
                    <span class="text-gray-500 text-sm ml-1">from last month</span>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Enhanced Calendar -->
            <div class="lg:col-span-2">
                <div class="glass-card rounded-xl shadow-sm">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-semibold text-gray-900">Task Calendar</h3>
                            <div class="flex items-center space-x-4">
                                <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors" onclick="previousMonth()">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <h4 class="text-lg font-semibold text-gray-900 min-w-[140px] text-center" id="currentMonth">
                                    May 2025
                                </h4>
                                <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors" onclick="nextMonth()">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Calendar Grid -->
                        <div class="grid grid-cols-7 gap-1">
                            <!-- Day headers -->
                            <div class="text-center text-xs font-semibold text-gray-500 py-3">Sun</div>
                            <div class="text-center text-xs font-semibold text-gray-500 py-3">Mon</div>
                            <div class="text-center text-xs font-semibold text-gray-500 py-3">Tue</div>
                            <div class="text-center text-xs font-semibold text-gray-500 py-3">Wed</div>
                            <div class="text-center text-xs font-semibold text-gray-500 py-3">Thu</div>
                            <div class="text-center text-xs font-semibold text-gray-500 py-3">Fri</div>
                            <div class="text-center text-xs font-semibold text-gray-500 py-3">Sat</div>

                            <!-- Calendar days -->
                            <div class="calendar-day bg-white rounded-lg p-2 h-20 cursor-pointer border-2 border-gray-100 hover:border-indigo-200">
                                <div class="text-sm font-medium text-gray-400">30</div>
                            </div>
                            <div class="calendar-day bg-white rounded-lg p-2 h-20 cursor-pointer border-2 border-gray-100 hover:border-indigo-200">
                                <div class="text-sm font-medium text-gray-900">1</div>
                                <div class="task-indicator bg-blue-500 rounded"></div>
                                <div class="task-indicator bg-green-500 rounded"></div>
                            </div>
                            <div class="calendar-day bg-white rounded-lg p-2 h-20 cursor-pointer border-2 border-gray-100 hover:border-indigo-200">
                                <div class="text-sm font-medium text-gray-900">2</div>
                            </div>
                            <div class="calendar-day bg-white rounded-lg p-2 h-20 cursor-pointer border-2 border-gray-100 hover:border-indigo-200">
                                <div class="text-sm font-medium text-gray-900">3</div>
                                <div class="task-indicator bg-red-500 rounded"></div>
                            </div>
                            <div class="calendar-day bg-white rounded-lg p-2 h-20 cursor-pointer border-2 border-gray-100 hover:border-indigo-200">
                                <div class="text-sm font-medium text-gray-900">4</div>
                            </div>
                            <div class="calendar-day bg-white rounded-lg p-2 h-20 cursor-pointer border-2 border-gray-100 hover:border-indigo-200">
                                <div class="text-sm font-medium text-gray-900">5</div>
                                <div class="task-indicator bg-yellow-500 rounded"></div>
                                <div class="task-indicator bg-purple-500 rounded"></div>
                            </div>
                            <div class="calendar-day bg-white rounded-lg p-2 h-20 cursor-pointer border-2 border-gray-100 hover:border-indigo-200">
                                <div class="text-sm font-medium text-gray-900">6</div>
                            </div>

                            <!-- More calendar days... -->
                            <div class="calendar-day bg-white rounded-lg p-2 h-20 cursor-pointer border-2 border-gray-100 hover:border-indigo-200">
                                <div class="text-sm font-medium text-gray-900">7</div>
                            </div>
                            <div class="calendar-day bg-white rounded-lg p-2 h-20 cursor-pointer border-2 border-gray-100 hover:border-indigo-200">
                                <div class="text-sm font-medium text-gray-900">8</div>
                                <div class="task-indicator bg-blue-500 rounded"></div>
                            </div>
                            <div class="calendar-day bg-white rounded-lg p-2 h-20 cursor-pointer border-2 border-gray-100 hover:border-indigo-200">
                                <div class="text-sm font-medium text-gray-900">9</div>
                            </div>
                            <div class="calendar-day bg-white rounded-lg p-2 h-20 cursor-pointer border-2 border-gray-100 hover:border-indigo-200">
                                <div class="text-sm font-medium text-gray-900">10</div>
                                <div class="task-indicator bg-green-500 rounded"></div>
                                <div class="task-indicator bg-orange-500 rounded"></div>
                            </div>
                            <div class="calendar-day bg-white rounded-lg p-2 h-20 cursor-pointer border-2 border-gray-100 hover:border-indigo-200">
                                <div class="text-sm font-medium text-gray-900">11</div>
                            </div>
                            <div class="calendar-day bg-white rounded-lg p-2 h-20 cursor-pointer border-2 border-gray-100 hover:border-indigo-200">
                                <div class="text-sm font-medium text-gray-900">12</div>
                            </div>
                            <div class="calendar-day bg-white rounded-lg p-2 h-20 cursor-pointer border-2 border-gray-100 hover:border-indigo-200">
                                <div class="text-sm font-medium text-gray-900">13</div>
                                <div class="task-indicator bg-red-500 rounded"></div>
                            </div>

                            <!-- Current day -->
                            <div class="calendar-day bg-indigo-50 rounded-lg p-2 h-20 cursor-pointer border-2 border-indigo-300">
                                <div class="text-sm font-bold text-indigo-600">14</div>
                                <div class="task-indicator bg-yellow-500 rounded"></div>
                                <div class="task-indicator bg-purple-500 rounded"></div>
                                <div class="text-xs text-indigo-600 font-medium mt-1">Today</div>
                            </div>

                            <!-- Continue with more days... -->
                            <div class="calendar-day bg-white rounded-lg p-2 h-20 cursor-pointer border-2 border-gray-100 hover:border-indigo-200">
                                <div class="text-sm font-medium text-gray-900">15</div>
                            </div>
                            <div class="calendar-day bg-white rounded-lg p-2 h-20 cursor-pointer border-2 border-gray-100 hover:border-indigo-200">
                                <div class="text-sm font-medium text-gray-900">16</div>
                                <div class="task-indicator bg-blue-500 rounded"></div>
                            </div>
                        </div>

                        <!-- Priority Legend -->
                        <div class="mt-6 flex flex-wrap gap-4">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-red-500 rounded mr-2"></div>
                                <span class="text-xs text-gray-600">Urgent</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-orange-500 rounded mr-2"></div>
                                <span class="text-xs text-gray-600">High</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-yellow-500 rounded mr-2"></div>
                                <span class="text-xs text-gray-600">Medium</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded mr-2"></div>
                                <span class="text-xs text-gray-600">Low</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Household Stats -->
            <div class="lg:col-span-1">
                <div class="glass-card rounded-xl shadow-sm">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Household Stats</h3>

                        <!-- Stats Toggle -->
                        <div class="flex rounded-lg border border-gray-200 mb-6 overflow-hidden">
                            <button class="flex-1 py-3 px-4 text-sm font-medium bg-indigo-50 text-indigo-700 border-r border-gray-200 transition-colors"
                                    onclick="switchStatsView('created')" id="created-btn">
                                Created
                            </button>
                            <button class="flex-1 py-3 px-4 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors"
                                    onclick="switchStatsView('completed')" id="completed-btn">
                                Completed
                            </button>
                        </div>

                        <!-- Created Tasks Stats -->
                        <div id="created-stats" class="space-y-4">
                            <div class="text-xs text-gray-500 mb-3 uppercase tracking-wide">Task Creation Distribution</div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center flex-1">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm font-medium text-white">J</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">John</p>
                                        <p class="text-xs text-gray-500">15 tasks (62.5%)</p>
                                    </div>
                                </div>
                                <div class="w-24 bg-gray-200 rounded-full h-2 ml-3">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: 62.5%"></div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center flex-1">
                                    <div class="w-10 h-10 bg-gradient-to-r from-purple-400 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm font-medium text-white">S</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Sarah</p>
                                        <p class="text-xs text-gray-500">9 tasks (37.5%)</p>
                                    </div>
                                </div>
                                <div class="w-24 bg-gray-200 rounded-full h-2 ml-3">
                                    <div class="bg-purple-600 h-2 rounded-full transition-all duration-500" style="width: 37.5%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Completed Tasks Stats (hidden by default) -->
                        <div id="completed-stats" class="space-y-4 hidden">
                            <div class="text-xs text-gray-500 mb-3 uppercase tracking-wide">Task Completion Distribution</div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center flex-1">
                                    <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-green-600 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm font-medium text-white">S</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Sarah</p>
                                        <p class="text-xs text-gray-500">12 tasks (57.1%)</p>
                                        <p class="text-xs text-green-600">95% completion rate</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">145 pts</p>
                                    <div class="w-24 bg-gray-200 rounded-full h-2 ml-3 mt-1">
                                        <div class="bg-green-600 h-2 rounded-full transition-all duration-500" style="width: 57.1%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center flex-1">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm font-medium text-white">J</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">John</p>
                                        <p class="text-xs text-gray-500">9 tasks (42.9%)</p>
                                        <p class="text-xs text-green-600">82% completion rate</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">118 pts</p>
                                    <div class="w-24 bg-gray-200 rounded-full h-2 ml-3 mt-1">
                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: 42.9%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fairness Indicator -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Task Distribution Balance</h4>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs text-gray-600">Distribution Score</span>
                                <span class="text-sm font-medium text-green-600">Excellent</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="h-3 rounded-full bg-gradient-to-r from-green-400 to-green-600 transition-all duration-500" style="width: 88%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Priority Distribution Chart -->
        <div class="glass-card rounded-xl shadow-sm mb-8">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Task Priority Distribution</h3>
                <div class="grid grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="relative">
                            <div class="flex mb-2 items-center justify-between">
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-red-600 bg-red-100">
                                    Urgent
                                </span>
                                <span class="text-xs font-semibold text-gray-600">2</span>
                            </div>
                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-red-100">
                                <div style="width: 15%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-red-500 transition-all duration-500"></div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="relative">
                            <div class="flex mb-2 items-center justify-between">
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-orange-600 bg-orange-100">
                                    High
                                </span>
                                <span class="text-xs font-semibold text-gray-600">5</span>
                            </div>
                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-orange-100">
                                <div style="width: 38%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-orange-500 transition-all duration-500"></div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="relative">
                            <div class="flex mb-2 items-center justify-between">
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-yellow-600 bg-yellow-100">
                                    Medium
                                </span>
                                <span class="text-xs font-semibold text-gray-600">4</span>
                            </div>
                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-yellow-100">
                                <div style="width: 31%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-yellow-500 transition-all duration-500"></div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="relative">
                            <div class="flex mb-2 items-center justify-between">
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-600 bg-green-100">
                                    Low
                                </span>
                                <span class="text-xs font-semibold text-gray-600">2</span>
                            </div>
                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-green-100">
                                <div style="width: 16%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500 transition-all duration-500"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Current Tasks -->
        <div class="glass-card rounded-xl shadow-sm">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-900">My Current Tasks</h3>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors shadow-sm">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Task
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-white rounded-lg border-2 border-gray-100 hover:border-indigo-200 transition-all duration-200">
                        <div class="w-1 h-14 bg-red-500 rounded-full mr-4"></div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900">Fix kitchen faucet leak</h4>
                            <p class="text-xs text-gray-500">Kitchen • Due: Today</p>
                            <p class="text-xs text-red-600 font-medium mt-1">Overdue</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Urgent
                            </span>
                            <button class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-white rounded-lg border-2 border-gray-100 hover:border-indigo-200 transition-all duration-200">
                        <div class="w-1 h-14 bg-orange-500 rounded-full mr-4"></div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900">Weekly grocery shopping</h4>
                            <p class="text-xs text-gray-500">Shopping • Due: Tomorrow</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                High
                            </span>
                            <button class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-white rounded-lg border-2 border-gray-100 hover:border-indigo-200 transition-all duration-200">
                        <div class="w-1 h-14 bg-yellow-500 rounded-full mr-4"></div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900">Vacuum living room</h4>
                            <p class="text-xs text-gray-500">Cleaning • Due: This week</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Medium
                            </span>
                            <button class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-white rounded-lg border-2 border-gray-100 hover:border-indigo-200 transition-all duration-200">
                        <div class="w-1 h-14 bg-green-500 rounded-full mr-4"></div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900">Water plants</h4>
                            <p class="text-xs text-gray-500">Garden • Due: Next week</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Low
                            </span>
                            <button class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm transition-colors">
                        View all tasks →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for interactivity -->
    <script>
        let currentDate = new Date();

        function updateCalendarHeader() {
            const monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"];
            document.getElementById('currentMonth').textContent =
                `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
        }

        function previousMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            updateCalendarHeader();
            // Add calendar regeneration logic here
        }

        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            updateCalendarHeader();
            // Add calendar regeneration logic here
        }

        function switchStatsView(view) {
            const createdStats = document.getElementById('created-stats');
            const completedStats = document.getElementById('completed-stats');
            const createdBtn = document.getElementById('created-btn');
            const completedBtn = document.getElementById('completed-btn');

            // Reset button styles
            createdBtn.classList.remove('bg-indigo-50', 'text-indigo-700');
            createdBtn.classList.add('text-gray-500', 'hover:text-gray-700');
            completedBtn.classList.remove('bg-indigo-50', 'text-indigo-700');
            completedBtn.classList.add('text-gray-500', 'hover:text-gray-700');

            if (view === 'created') {
                createdStats.classList.remove('hidden');
                completedStats.classList.add('hidden');
                createdBtn.classList.remove('text-gray-500', 'hover:text-gray-700');
                createdBtn.classList.add('bg-indigo-50', 'text-indigo-700');
            } else {
                createdStats.classList.add('hidden');
                completedStats.classList.remove('hidden');
                completedBtn.classList.remove('text-gray-500', 'hover:text-gray-700');
                completedBtn.classList.add('bg-indigo-50', 'text-indigo-700');
            }
        }

        // Initialize calendar on load
        document.addEventListener('DOMContentLoaded', function() {
            updateCalendarHeader();
        });
    </script>
</body>
</html>
