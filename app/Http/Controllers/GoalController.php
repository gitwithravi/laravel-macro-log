<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $goals = auth()->user()->goals()->latest()->get();

        return Inertia::render('Goals/Index', [
            'goals' => $goals,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'current_weight' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'target_weight' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'daily_goal_calories' => ['required', 'integer', 'min:0'],
            'daily_goal_protein' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'daily_goal_carb' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'daily_goal_fat' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'goal_target_date' => ['nullable', 'date', 'after:today'],
            'is_active' => ['boolean'],
        ]);

        // If this goal is set to active, deactivate all other goals
        if ($request->is_active) {
            auth()->user()->goals()->update(['is_active' => false]);
        }

        auth()->user()->goals()->create($validated);

        return redirect()->route('goals.index')->with('success', 'Goal created successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Goal $goal)
    {
        // Ensure the goal belongs to the authenticated user
        if ($goal->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'current_weight' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'target_weight' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'daily_goal_calories' => ['required', 'integer', 'min:0'],
            'daily_goal_protein' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'daily_goal_carb' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'daily_goal_fat' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'goal_target_date' => ['nullable', 'date', 'after:today'],
            'is_active' => ['boolean'],
        ]);

        // If this goal is set to active, deactivate all other goals
        if ($request->is_active && !$goal->is_active) {
            auth()->user()->goals()->where('id', '!=', $goal->id)->update(['is_active' => false]);
        }

        $goal->update($validated);

        return redirect()->route('goals.index')->with('success', 'Goal updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Goal $goal)
    {
        // Ensure the goal belongs to the authenticated user
        if ($goal->user_id !== auth()->id()) {
            abort(403);
        }

        $goal->delete();

        return redirect()->route('goals.index')->with('success', 'Goal deleted successfully!');
    }

    /**
     * Toggle the active status of a goal.
     */
    public function toggleActive(Goal $goal)
    {
        // Ensure the goal belongs to the authenticated user
        if ($goal->user_id !== auth()->id()) {
            abort(403);
        }

        // If activating this goal, deactivate all others
        if (!$goal->is_active) {
            auth()->user()->goals()->update(['is_active' => false]);
            $goal->update(['is_active' => true]);
        } else {
            $goal->update(['is_active' => false]);
        }

        return back()->with('success', 'Goal status updated!');
    }
}
