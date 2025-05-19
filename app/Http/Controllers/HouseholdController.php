<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Models\TaskStat;
use App\Models\User;  // Added missing import
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

    /**
     * Show confirmation form for leaving household
     */
    public function leaveConfirm()
    {
        return view('household.leave');
    }

    /**
 * Process the user leaving a household
 */
public function leave(Request $request)
{
    $user = Auth::user();

    // Prevent the last admin from leaving
    if ($user->role === 'admin' && $user->household->users()->where('role', 'admin')->count() <= 1) {
        return back()->with('error', 'You are the only admin. Please promote another member to admin before leaving.');
    }

    // Clear the user's household association
    $user->update([
        'household_id' => null,
        'role' => 'none',  // Set to 'none' or another default value instead of null
    ]);

    // Delete their task stats
    TaskStat::where('user_id', $user->id)->delete();

    return redirect()->route('household.create')
        ->with('success', 'You have left the household successfully.');
}

    /**
     * Display household members management page
     */
    public function members()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return redirect()->route('household.show')
                ->with('error', 'Only admins can manage household members.');
        }

        $household = $user->household;
        $members = $household->users;

        return view('household.members', compact('household', 'members'));
    }

    /**
 * Remove a member from the household (admin only)
 */
public function removeMember(Request $request, User $member)
{
    $user = Auth::user();

    // Check if user is admin and the member belongs to their household
    if ($user->role !== 'admin' || $member->household_id !== $user->household_id) {
        return back()->with('error', 'You do not have permission to remove this member.');
    }

    // Prevent removing yourself
    if ($member->id === $user->id) {
        return back()->with('error', 'You cannot remove yourself. Use the leave household option instead.');
    }

    // Clear the member's household association
    $member->update([
        'household_id' => null,
        'role' => 'none',  // Set to 'none' or another default value instead of null
    ]);

    // Delete their task stats
    TaskStat::where('user_id', $member->id)->delete();

    return back()->with('success', "{$member->name} has been removed from the household.");
}

    /**
     * Toggle admin role for a member (admin only)
     */
    public function toggleAdminRole(Request $request, User $member)
    {
        $user = Auth::user();

        // Check if user is admin and the member belongs to their household
        if ($user->role !== 'admin' || $member->household_id !== $user->household_id) {
            return back()->with('error', 'You do not have permission to manage this member.');
        }

        // Toggle the role
        $newRole = $member->role === 'admin' ? 'member' : 'admin';
        $member->update(['role' => $newRole]);

        $action = $newRole === 'admin' ? 'promoted to admin' : 'changed to regular member';
        return back()->with('success', "{$member->name} has been {$action}.");
    }
}
