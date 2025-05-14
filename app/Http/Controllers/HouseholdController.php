<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Models\TaskStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HouseholdController extends Controller
{
    public function create()
    {
        return view('household.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'timezone' => 'required|string',
        ]);

        // Create the household
        $household = Household::create([
            'name' => $request->name,
            'description' => $request->description,
            'timezone' => $request->timezone,
        ]);

        // Update the user to be the admin of this household
        $user = Auth::user();
        $user->update([
            'household_id' => $household->id,
            'role' => 'admin',
        ]);

        // Create initial task stats
        TaskStat::create([
            'user_id' => $user->id,
            'household_id' => $household->id,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Household created successfully!');
    }

    public function joinForm()
    {
        $households = Household::all();
        return view('household.join', compact('households'));
    }

    public function join(Request $request)
    {
        $request->validate([
            'household_id' => 'required|exists:households,id',
        ]);

        $user = Auth::user();
        $household = Household::findOrFail($request->household_id);

        $user->update([
            'household_id' => $household->id,
            'role' => 'member',
        ]);

        // Create initial task stats
        TaskStat::create([
            'user_id' => $user->id,
            'household_id' => $household->id,
        ]);

        return redirect()->route('dashboard')
            ->with('success', "Welcome to {$household->name}!");
    }

    public function show()
    {
        $user = Auth::user();
        $household = $user->household;
        $members = $household->users;
        $stats = TaskStat::where('household_id', $household->id)
            ->with('user')
            ->orderBy('points', 'desc')
            ->get();

        return view('household.show', compact('household', 'members', 'stats'));
    }
}
