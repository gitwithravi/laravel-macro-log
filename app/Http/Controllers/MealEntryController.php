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

        // Get "today" in the user's timezone
        $today = $user->getUserToday();
        $dayBoundaries = $user->getDayBoundariesInUtc($today);

        // Get today's meal entries using UTC boundaries
        $todayMeals = $user->mealEntries()
            ->whereBetween('logged_at', [$dayBoundaries['start'], $dayBoundaries['end']])
            ->orderBy('logged_at', 'desc')
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
            'userTimezone' => $user->getUserTimezone(),
        ]);
    }

    /**
     * Display meal history with date range filtering.
     */
    public function history(Request $request): Response
    {
        $user = auth()->user();
        $userTimezone = $user->getUserTimezone();

        // Validate request parameters
        $validated = $request->validate([
            'filter_type' => ['nullable', 'in:7,30,90,custom'],
            'start_date' => ['nullable', 'required_if:filter_type,custom', 'date', 'before_or_equal:today'],
            'end_date' => ['nullable', 'required_if:filter_type,custom', 'date', 'before_or_equal:today', 'after_or_equal:start_date'],
        ]);

        $filterType = $validated['filter_type'] ?? '7';

        // Calculate date range based on filter type
        if ($filterType === 'custom' && isset($validated['start_date']) && isset($validated['end_date'])) {
            // Parse dates in user's timezone
            $startDate = \Carbon\Carbon::parse($validated['start_date'], $userTimezone)->startOfDay();
            $endDate = \Carbon\Carbon::parse($validated['end_date'], $userTimezone)->endOfDay();

            // Enforce maximum range of 365 days
            if ($startDate->diffInDays($endDate) > 365) {
                return back()->withErrors(['date_range' => 'Maximum date range is 365 days.']);
            }
        } else {
            // Use preset filter
            $days = (int) $filterType;
            $endDate = $user->getUserNow()->endOfDay();
            $startDate = $user->getUserNow()->subDays($days - 1)->startOfDay(); // Include today
        }

        // Convert to UTC for database query
        $utcStart = $startDate->utc();
        $utcEnd = $endDate->utc();

        // Get all meal entries for the date range
        $meals = $user->mealEntries()
            ->whereBetween('logged_at', [$utcStart, $utcEnd])
            ->orderBy('logged_at', 'desc')
            ->get();

        // Group meals by date (in user's timezone) and calculate daily totals
        $historyData = [];
        foreach ($meals as $meal) {
            // Get date in user's timezone
            $date = $meal->getLoggedDateInUserTimezone();

            if (!isset($historyData[$date])) {
                $historyData[$date] = [
                    'meals' => [],
                    'totals' => [
                        'calories' => 0,
                        'protein' => 0,
                        'carbs' => 0,
                        'fat' => 0,
                    ],
                    'date' => $date,
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

        // Calculate summary statistics based on days with actual data
        $daysWithData = count($historyData);
        $summaryData = [
            'daysWithData' => $daysWithData,
            'averageCalories' => 0,
            'averageProtein' => 0,
            'averageCarbs' => 0,
            'averageFat' => 0,
        ];

        if ($daysWithData > 0) {
            $totalCalories = 0;
            $totalProtein = 0;
            $totalCarbs = 0;
            $totalFat = 0;

            foreach ($historyData as $dayData) {
                $totalCalories += $dayData['totals']['calories'];
                $totalProtein += $dayData['totals']['protein'];
                $totalCarbs += $dayData['totals']['carbs'];
                $totalFat += $dayData['totals']['fat'];
            }

            $summaryData['averageCalories'] = (int) round($totalCalories / $daysWithData);
            $summaryData['averageProtein'] = round($totalProtein / $daysWithData, 2);
            $summaryData['averageCarbs'] = round($totalCarbs / $daysWithData, 2);
            $summaryData['averageFat'] = round($totalFat / $daysWithData, 2);
        }

        // Get active goal
        $activeGoal = $user->goals()->where('is_active', true)->first();

        return Inertia::render('History', [
            'historyData' => $historyData,
            'activeGoal' => $activeGoal,
            'startDate' => $startDate->setTimezone($userTimezone)->toDateString(),
            'endDate' => $endDate->setTimezone($userTimezone)->toDateString(),
            'filterType' => $filterType,
            'summaryData' => $summaryData,
            'userTimezone' => $userTimezone,
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
                'components' => $this->scaleComponents($frequentMeal->components, $multiplier),
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
            // If logged_date is provided, parse it in user's timezone, otherwise use current time in user's timezone
            if (isset($validated['logged_date'])) {
                // Parse the date in user's timezone and set time to current time in that timezone
                $loggedAt = \Carbon\Carbon::parse($validated['logged_date'], $user->getUserTimezone())
                    ->setTimeFrom($user->getUserNow());
            } else {
                // Use current time in user's timezone
                $loggedAt = $user->getUserNow();
            }

            $mealEntry = $user->mealEntries()->create([
                'logged_at' => $loggedAt->utc(), // Store in UTC
                'raw_input' => $rawInput,
                'meal_name' => $nutritionData['meal_name'],
                'calories' => $nutritionData['calories'],
                'protein' => $nutritionData['protein'],
                'carbs' => $nutritionData['carbs'],
                'fat' => $nutritionData['fat'],
                'components' => $nutritionData['components'] ?? null,
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

        $systemPrompt = "You are a nutrition expert specializing in Indian and international cuisine. "
            . "Estimate macros by breaking the meal into its individual components and reasoning about portions explicitly — do NOT guess a single total.\n\n"
            . "For every meal:\n"
            . "1. Split it into each distinct food item (e.g. '2 rotis + dal + rice' = three components).\n"
            . "2. For each component, resolve the portion to an explicit weight in grams. If the user gives a count (e.g. '2 rotis'), use a typical per-unit weight for that cuisine (1 roti ≈ 40g, 1 idli ≈ 35g, 1 chapati ≈ 40g, 1 medium dosa ≈ 80g, 1 samosa ≈ 60g, 1 slice bread ≈ 30g, 1 cup cooked rice ≈ 180g, 1 cup cooked dal ≈ 200g, 1 egg ≈ 50g). If the user gives a plate/bowl/serving, use standard restaurant portions.\n"
            . "3. Use realistic per-100g values grounded in standard references (IFCT for Indian foods, USDA for others). Account for cooking oil/ghee in Indian gravies (~5–10g fat per serving).\n"
            . "4. Compute each component's macros from grams × per-100g values, then sum across components for the totals.\n"
            . "5. Round protein/carbs/fat to 1 decimal; calories to integer. Totals must equal the sum of components (within rounding).\n\n"
            . "Return only the structured JSON object — no prose.";

        // Sanitize and wrap user input to prevent prompt injection
        $sanitizedInput = $promptSecurity->sanitize($mealInput);
        $userPrompt = "Parse the meal description provided between the markers. Break it into components with explicit gram weights, compute macros per component, then sum to totals.\n\n"
            . $promptSecurity->wrapUserInput($sanitizedInput);

        $response = $client->chat()->create([
            'model' => 'gpt-4.1',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt],
            ],
            'temperature' => 0.2,
            'max_tokens' => 900,
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => [
                    'name' => 'meal_nutrition',
                    'strict' => true,
                    'schema' => [
                        'type' => 'object',
                        'additionalProperties' => false,
                        'required' => ['meal_name', 'components', 'totals'],
                        'properties' => [
                            'meal_name' => ['type' => 'string'],
                            'components' => [
                                'type' => 'array',
                                'minItems' => 1,
                                'items' => [
                                    'type' => 'object',
                                    'additionalProperties' => false,
                                    'required' => ['name', 'grams', 'calories', 'protein', 'carbs', 'fat'],
                                    'properties' => [
                                        'name' => ['type' => 'string'],
                                        'grams' => ['type' => 'number'],
                                        'calories' => ['type' => 'number'],
                                        'protein' => ['type' => 'number'],
                                        'carbs' => ['type' => 'number'],
                                        'fat' => ['type' => 'number'],
                                    ],
                                ],
                            ],
                            'totals' => [
                                'type' => 'object',
                                'additionalProperties' => false,
                                'required' => ['calories', 'protein', 'carbs', 'fat'],
                                'properties' => [
                                    'calories' => ['type' => 'number'],
                                    'protein' => ['type' => 'number'],
                                    'carbs' => ['type' => 'number'],
                                    'fat' => ['type' => 'number'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        $content = $response['choices'][0]['message']['content'] ?? null;

        if (!$content) {
            throw new \Exception('Empty response from OpenAI');
        }

        $parsed = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON response from OpenAI: ' . json_last_error_msg());
        }

        if (!isset($parsed['meal_name'], $parsed['totals'], $parsed['components'])) {
            throw new \Exception('Malformed OpenAI response: missing meal_name, totals, or components');
        }

        $totals = $parsed['totals'];
        foreach (['calories', 'protein', 'carbs', 'fat'] as $field) {
            if (!isset($totals[$field])) {
                throw new \Exception("Missing totals.{$field} in OpenAI response");
            }
        }

        return [
            'meal_name' => $parsed['meal_name'],
            'calories' => (int) round((float) $totals['calories']),
            'protein' => round((float) $totals['protein'], 2),
            'carbs' => round((float) $totals['carbs'], 2),
            'fat' => round((float) $totals['fat'], 2),
            'components' => $this->normalizeComponents($parsed['components']),
        ];
    }

    private function scaleComponents(?array $components, float $multiplier): ?array
    {
        if (empty($components)) {
            return null;
        }

        return array_values(array_map(fn (array $c) => [
            'name' => (string) ($c['name'] ?? ''),
            'grams' => round(((float) ($c['grams'] ?? 0)) * $multiplier, 1),
            'calories' => (int) round(((float) ($c['calories'] ?? 0)) * $multiplier),
            'protein' => round(((float) ($c['protein'] ?? 0)) * $multiplier, 2),
            'carbs' => round(((float) ($c['carbs'] ?? 0)) * $multiplier, 2),
            'fat' => round(((float) ($c['fat'] ?? 0)) * $multiplier, 2),
        ], $components));
    }

    private function normalizeComponents(array $components): array
    {
        return array_values(array_map(fn (array $c) => [
            'name' => (string) ($c['name'] ?? ''),
            'grams' => round((float) ($c['grams'] ?? 0), 1),
            'calories' => (int) round((float) ($c['calories'] ?? 0)),
            'protein' => round((float) ($c['protein'] ?? 0), 2),
            'carbs' => round((float) ($c['carbs'] ?? 0), 2),
            'fat' => round((float) ($c['fat'] ?? 0), 2),
        ], $components));
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
            'model' => 'gpt-4.1',
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
