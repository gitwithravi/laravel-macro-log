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
     * Store a newly created meal entry.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'raw_input' => ['required', 'string', 'max:1000'],
            'logged_date' => ['nullable', 'date'],
        ]);

        $user = auth()->user();

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

            // Create meal entry
            $mealEntry = $user->mealEntries()->create([
                'logged_date' => $validated['logged_date'] ?? now()->toDateString(),
                'logged_time' => now()->toTimeString(),
                'raw_input' => $validated['raw_input'],
                'meal_name' => $nutritionData['meal_name'],
                'calories' => $nutritionData['calories'],
                'protein' => $nutritionData['protein'],
                'carbs' => $nutritionData['carbs'],
                'fat' => $nutritionData['fat'],
            ]);

            return back()->with('success', 'Meal logged successfully!');
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
     * Parse meal using OpenAI API.
     */
    private function parseMealWithOpenAI(string $apiKey, string $mealInput): array
    {
        $client = \OpenAI::client($apiKey);

        $systemPrompt = "You are a nutrition expert specializing in Indian and international cuisine. Parse meal descriptions and extract accurate nutritional information. Return ONLY a valid JSON object with: meal_name (string, cleaned up description), calories (integer), protein (float with 2 decimals), carbs (float with 2 decimals), fat (float with 2 decimals). No explanations, no markdown formatting - just raw JSON.";

        $userPrompt = "Parse this meal and calculate total nutrition:\n\"{$mealInput}\"\n\nReturn JSON format:\n{\n  \"meal_name\": \"Clean, readable description\",\n  \"calories\": <total calories as integer>,\n  \"protein\": <grams as float>,\n  \"carbs\": <grams as float>,\n  \"fat\": <grams as float>\n}";

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
}
