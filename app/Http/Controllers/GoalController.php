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

    /**
     * Calculate nutrition goals using OpenAI.
     */
    public function calculateNutrition(Request $request)
    {
        $validated = $request->validate([
            'height' => ['required', 'numeric', 'min:100', 'max:250'],
            'weight' => ['required', 'numeric', 'min:20', 'max:500'],
            'target_weight' => ['required', 'numeric', 'min:20', 'max:500'],
            'target_date' => ['required', 'date', 'after:today'],
            'daily_activity' => ['required', 'string', 'max:500'],
        ]);

        $user = auth()->user();

        // Check if user has OpenAI API key configured
        if (empty($user->open_api_key)) {
            return response()->json([
                'error' => 'OpenAI API key not configured. Please add your API key in profile settings.'
            ], 400);
        }

        try {
            // Initialize OpenAI client with user's API key
            $client = \OpenAI::client($user->open_api_key);

            // Build the prompt
            $systemPrompt = "You are a professional nutritionist and fitness expert. Calculate daily macronutrient goals based on user information. Return ONLY a valid JSON object with exactly four numeric values: daily_goal_calories (integer), daily_goal_protein (float with 2 decimals), daily_goal_carb (float with 2 decimals), daily_goal_fat (float with 2 decimals). No explanations, no additional text, no markdown formatting - just the raw JSON object.";

            $userPrompt = "Calculate daily nutrition goals for:\n"
                . "- Height: {$validated['height']} cm\n"
                . "- Current Weight: {$validated['weight']} kg\n"
                . "- Target Weight: {$validated['target_weight']} kg\n"
                . "- Target Date: {$validated['target_date']}\n"
                . "- Daily Activity: {$validated['daily_activity']}\n\n"
                . "Consider safe weight loss/gain rates (0.5-1 kg per week) and balanced macronutrient distribution. "
                . "Ensure adequate protein for muscle preservation, sufficient fats for hormonal health, and appropriate carbs for energy.";

            // Call OpenAI API
            $response = $client->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt
                    ],
                    [
                        'role' => 'user',
                        'content' => $userPrompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 200,
            ]);

            // Extract content from response
            $content = $response['choices'][0]['message']['content'] ?? null;

            if (!$content) {
                throw new \Exception('Empty response from OpenAI');
            }

            // Clean the response - remove markdown code blocks if present
            $content = preg_replace('/```json\s*/i', '', $content);
            $content = preg_replace('/```\s*/', '', $content);
            $content = trim($content);

            // Parse JSON response
            $nutrition = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response from OpenAI: ' . json_last_error_msg());
            }

            // Validate response structure
            $requiredFields = ['daily_goal_calories', 'daily_goal_protein', 'daily_goal_carb', 'daily_goal_fat'];
            foreach ($requiredFields as $field) {
                if (!isset($nutrition[$field]) || !is_numeric($nutrition[$field])) {
                    throw new \Exception("Missing or invalid field in OpenAI response: {$field}");
                }
            }

            // Return calculated nutrition values
            return response()->json([
                'success' => true,
                'data' => [
                    'daily_goal_calories' => (int) $nutrition['daily_goal_calories'],
                    'daily_goal_protein' => round((float) $nutrition['daily_goal_protein'], 2),
                    'daily_goal_carb' => round((float) $nutrition['daily_goal_carb'], 2),
                    'daily_goal_fat' => round((float) $nutrition['daily_goal_fat'], 2),
                ]
            ]);

        } catch (\OpenAI\Exceptions\ErrorException $e) {
            // OpenAI API error (invalid key, rate limit, etc.)
            return response()->json([
                'error' => 'OpenAI API error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            // General error
            return response()->json([
                'error' => 'Failed to calculate nutrition goals: ' . $e->getMessage()
            ], 500);
        }
    }
}
