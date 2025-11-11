<?php

namespace App\Http\Controllers;

use App\Models\MealEntry;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MealEntryController extends Controller
{
    /**
     * Display dashboard with today's meals and summary.
     */
    public function dashboard(): Response
    {
        $user = auth()->user();
        $today = now()->toDateString();

        // Get today's meal entries
        $todayMeals = $user->mealEntries()
            ->whereDate('logged_date', $today)
            ->orderBy('logged_time', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate today's totals
        $todayTotals = [
            'calories' => $todayMeals->sum('calories'),
            'protein' => $todayMeals->sum('protein'),
            'carbs' => $todayMeals->sum('carbs'),
            'fat' => $todayMeals->sum('fat'),
        ];

        // Get active goal
        $activeGoal = $user->goals()->where('is_active', true)->first();

        return Inertia::render('Dashboard', [
            'todayMeals' => $todayMeals,
            'todayTotals' => $todayTotals,
            'activeGoal' => $activeGoal,
            'todayDate' => $today,
        ]);
    }

    /**
     * Display meal history with date range filtering.
     */
    public function history(Request $request): Response
    {
        $user = auth()->user();

        // Validate request parameters
        $validated = $request->validate([
            'filter_type' => ['nullable', 'in:7,30,90,custom'],
            'start_date' => ['nullable', 'required_if:filter_type,custom', 'date', 'before_or_equal:today'],
            'end_date' => ['nullable', 'required_if:filter_type,custom', 'date', 'before_or_equal:today', 'after_or_equal:start_date'],
        ]);

        $filterType = $validated['filter_type'] ?? '7';

        // Calculate date range based on filter type
        if ($filterType === 'custom' && isset($validated['start_date']) && isset($validated['end_date'])) {
            $startDate = \Carbon\Carbon::parse($validated['start_date']);
            $endDate = \Carbon\Carbon::parse($validated['end_date']);

            // Enforce maximum range of 365 days
            if ($startDate->diffInDays($endDate) > 365) {
                return back()->withErrors(['date_range' => 'Maximum date range is 365 days.']);
            }
        } else {
            // Use preset filter
            $days = (int) $filterType;
            $endDate = now();
            $startDate = now()->subDays($days - 1); // Include today
        }

        // Get all meal entries for the date range
        $meals = $user->mealEntries()
            ->whereBetween('logged_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('logged_date', 'desc')
            ->orderBy('logged_time', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Group meals by date and calculate daily totals
        $historyData = [];
        foreach ($meals as $meal) {
            $date = $meal->logged_date->toDateString();

            if (!isset($historyData[$date])) {
                $historyData[$date] = [
                    'meals' => [],
                    'totals' => [
                        'calories' => 0,
                        'protein' => 0,
                        'carbs' => 0,
                        'fat' => 0,
                    ],
                    'date' => $meal->logged_date,
                ];
            }

            $historyData[$date]['meals'][] = $meal;
            $historyData[$date]['totals']['calories'] += $meal->calories;
            $historyData[$date]['totals']['protein'] += $meal->protein;
            $historyData[$date]['totals']['carbs'] += $meal->carbs;
            $historyData[$date]['totals']['fat'] += $meal->fat;
        }

        // Round totals to 2 decimal places
        foreach ($historyData as $date => &$data) {
            $data['totals']['protein'] = round($data['totals']['protein'], 2);
            $data['totals']['carbs'] = round($data['totals']['carbs'], 2);
            $data['totals']['fat'] = round($data['totals']['fat'], 2);
        }

        // Get active goal
        $activeGoal = $user->goals()->where('is_active', true)->first();

        return Inertia::render('History', [
            'historyData' => $historyData,
            'activeGoal' => $activeGoal,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
            'filterType' => $filterType,
        ]);
    }

    /**
     * Store a newly created meal entry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'raw_input' => ['nullable', 'string', 'max:1000'],
            'logged_date' => ['nullable', 'date'],
            'frequent_meal_id' => ['nullable', 'integer', 'exists:frequent_meals,id'],
            'portion_multiplier' => ['nullable', 'numeric', 'min:0.1', 'max:10'],
        ]);

        $user = auth()->user();

        // Check if logging from frequent meal
        if (!empty($validated['frequent_meal_id'])) {
            // Load the frequent meal and verify ownership
            $frequentMeal = \App\Models\FrequentMeal::findOrFail($validated['frequent_meal_id']);

            if ($frequentMeal->user_id !== $user->id) {
                return response()->json([
                    'error' => 'Unauthorized action.'
                ], 403);
            }

            // Get portion multiplier (default 1.0)
            $multiplier = $validated['portion_multiplier'] ?? 1.0;

            // Calculate scaled nutrition values
            $nutritionData = [
                'meal_name' => $frequentMeal->meal_name,
                'calories' => (int) round($frequentMeal->calories * $multiplier),
                'protein' => round($frequentMeal->protein * $multiplier, 2),
                'carbs' => round($frequentMeal->carbs * $multiplier, 2),
                'fat' => round($frequentMeal->fat * $multiplier, 2),
            ];

            // Use frequent meal name as raw input if not provided
            $rawInput = $validated['raw_input'] ?? $frequentMeal->meal_name;
        } else {
            // Using raw input with OpenAI parsing
            if (empty($validated['raw_input'])) {
                return response()->json([
                    'error' => 'Either raw_input or frequent_meal_id is required.'
                ], 400);
            }

            // Check if user has OpenAI API key configured
            if (empty($user->open_api_key)) {
                return response()->json([
                    'error' => 'OpenAI API key not configured. Please add your API key in profile settings.'
                ], 400);
            }

            try {
                // Parse meal with OpenAI
                $nutritionData = $this->parseMealWithOpenAI(
                    $user->open_api_key,
                    $validated['raw_input']
                );

                $rawInput = $validated['raw_input'];
            } catch (\OpenAI\Exceptions\ErrorException $e) {
                return response()->json([
                    'error' => 'OpenAI API error: ' . $e->getMessage()
                ], 500);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to parse meal: ' . $e->getMessage()
                ], 500);
            }
        }

        // Create meal entry
        try {
            $mealEntry = $user->mealEntries()->create([
                'logged_date' => $validated['logged_date'] ?? now()->toDateString(),
                'logged_time' => now()->toTimeString(),
                'raw_input' => $rawInput,
                'meal_name' => $nutritionData['meal_name'],
                'calories' => $nutritionData['calories'],
                'protein' => $nutritionData['protein'],
                'carbs' => $nutritionData['carbs'],
                'fat' => $nutritionData['fat'],
            ]);

            return back()->with('success', 'Meal logged successfully!');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to save meal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified meal entry.
     */
    public function destroy(MealEntry $mealEntry)
    {
        // Ensure the meal belongs to the authenticated user
        if ($mealEntry->user_id !== auth()->id()) {
            abort(403);
        }

        $mealEntry->delete();

        return back()->with('success', 'Meal deleted successfully!');
    }

    /**
     * Get or generate insight for a meal entry.
     */
    public function getInsight(MealEntry $mealEntry)
    {
        // Ensure the meal belongs to the authenticated user
        if ($mealEntry->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if insight already exists
        $existingInsight = $mealEntry->insight;

        if ($existingInsight) {
            return response()->json([
                'insight' => $existingInsight->insight,
                'cached' => true,
            ]);
        }

        // Generate new insight using OpenAI
        $user = auth()->user();

        // Check if user has OpenAI API key configured
        if (empty($user->open_api_key)) {
            return response()->json([
                'error' => 'OpenAI API key not configured. Please add your API key in profile settings.'
            ], 400);
        }

        try {
            // Get user's active goal for context
            $activeGoal = $user->goals()->where('is_active', true)->first();

            // Generate insight
            $insightText = $this->generateMealInsight(
                $user->open_api_key,
                $mealEntry,
                $activeGoal
            );

            // Store insight in database
            $insight = $mealEntry->insight()->create([
                'insight' => $insightText,
            ]);

            return response()->json([
                'insight' => $insightText,
                'cached' => false,
            ]);
        } catch (\OpenAI\Exceptions\ErrorException $e) {
            return response()->json([
                'error' => 'OpenAI API error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to generate insight: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Parse meal using OpenAI API.
     */
    private function parseMealWithOpenAI(string $apiKey, string $mealInput): array
    {
        $client = \OpenAI::client($apiKey);

        // Use PromptSecurity service to prevent injection attacks
        $promptSecurity = new \App\Services\PromptSecurity();

        $systemPrompt = "You are a nutrition expert specializing in Indian and international cuisine. Parse meal descriptions and extract accurate nutritional information. Return ONLY a valid JSON object with: meal_name (string, cleaned up description), calories (integer), protein (float with 2 decimals), carbs (float with 2 decimals), fat (float with 2 decimals). No explanations, no markdown formatting - just raw JSON.";

        // Sanitize and wrap user input to prevent prompt injection
        $sanitizedInput = $promptSecurity->sanitize($mealInput);
        $userPrompt = "Parse the meal description provided between the markers and calculate total nutrition.\n\n"
            . $promptSecurity->wrapUserInput($sanitizedInput)
            . "\n\nReturn JSON format:\n{\n  \"meal_name\": \"Clean, readable description\",\n  \"calories\": <total calories as integer>,\n  \"protein\": <grams as float>,\n  \"carbs\": <grams as float>,\n  \"fat\": <grams as float>\n}";

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
        $requiredFields = ['meal_name', 'calories', 'protein', 'carbs', 'fat'];
        foreach ($requiredFields as $field) {
            if (!isset($nutrition[$field])) {
                throw new \Exception("Missing field in OpenAI response: {$field}");
            }
        }

        // Return standardized nutrition data
        return [
            'meal_name' => $nutrition['meal_name'],
            'calories' => (int) $nutrition['calories'],
            'protein' => round((float) $nutrition['protein'], 2),
            'carbs' => round((float) $nutrition['carbs'], 2),
            'fat' => round((float) $nutrition['fat'], 2),
        ];
    }

    /**
     * Generate meal insight using OpenAI API.
     */
    private function generateMealInsight(string $apiKey, MealEntry $mealEntry, $activeGoal = null): string
    {
        $client = \OpenAI::client($apiKey);
        $promptSecurity = new \App\Services\PromptSecurity();

        // Sanitize meal name to prevent injection via stored data
        $safeMealName = $promptSecurity->sanitize($mealEntry->meal_name);

        // Build context about the meal with sanitized data
        $mealContext = "Meal: {$safeMealName}\n";
        $mealContext .= "Calories: {$mealEntry->calories}\n";
        $mealContext .= "Protein: {$mealEntry->protein}g\n";
        $mealContext .= "Carbs: {$mealEntry->carbs}g\n";
        $mealContext .= "Fat: {$mealEntry->fat}g\n";

        // Add goal context if available
        $goalContext = '';
        if ($activeGoal) {
            $goalContext = "\n\nUser's Daily Goals:\n";
            $goalContext .= "Calories: {$activeGoal->daily_goal_calories}\n";
            $goalContext .= "Protein: {$activeGoal->daily_goal_protein}g\n";
            $goalContext .= "Carbs: {$activeGoal->daily_goal_carb}g\n";
            $goalContext .= "Fat: {$activeGoal->daily_goal_fat}g\n";
        }

        $systemPrompt = "You are a nutrition and health expert. Analyze meals and provide constructive, actionable insights. Be concise, friendly, and encouraging. Focus on whether the meal aligns with the user's goals and suggest practical improvements.";

        $userPrompt = "{$mealContext}{$goalContext}\n\nProvide a brief insight (2-3 sentences) about this meal. Include:\n1. Whether it aligns with the goals (if goals are provided)\n2. What's good about this meal\n3. One practical improvement or tip if applicable\n\nKeep it positive and actionable.";

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
            'max_tokens' => 150,
        ]);

        // Extract content from response
        $content = $response['choices'][0]['message']['content'] ?? null;

        if (!$content) {
            throw new \Exception('Empty response from OpenAI');
        }

        return trim($content);
    }
}
