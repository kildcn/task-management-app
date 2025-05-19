<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Models\TaskStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

        // Create the household with auto-generated join key
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
            ->with('success', 'Household created successfully! Your join key is: ' . $household->join_key);
    }

    public function joinForm()
    {
        return view('household.join');
    }

    public function join(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'join_key' => 'required|string|min:6|max:10',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Find the household by join key
        $household = Household::where('join_key', strtoupper($request->join_key))->first();

        if (!$household) {
            return back()->withErrors(['join_key' => 'Invalid join key. Please try again.'])->withInput();
        }

        $user = Auth::user();
        $user->update([
            'household_id' => $household->id,
            'role' => 'member',  // New members are regular members by default
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

    /**
     * Regenerate the household join key
     */
    public function regenerateKey()
    {
        $user = Auth::user();

        // Only admins can regenerate keys
        if ($user->role !== 'admin') {
            return back()->with('error', 'Only household admins can regenerate the join key.');
        }

        $household = $user->household;
        $household->regenerateJoinKey();

        return back()->with('success', 'Join key regenerated successfully: ' . $household->join_key);
    }
}
