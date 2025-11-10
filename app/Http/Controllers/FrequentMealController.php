<?php

namespace App\Http\Controllers;

use App\Models\FrequentMeal;
use App\Services\PromptSecurity;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FrequentMealController extends Controller
{
    /**
     * Display a listing of the user's frequent meals.
     */
    public function index(Request $request)
    {
        $frequentMeals = $request->user()
            ->frequentMeals()
            ->orderBy('meal_name')
            ->get();

        $count = $frequentMeals->count();
        $maxLimit = 100;

        return Inertia::render('FrequentMeals', [
            'frequentMeals' => $frequentMeals,
            'count' => $count,
            'maxLimit' => $maxLimit,
        ]);
    }

    /**
     * Get frequent meals as JSON (for API use).
     */
    public function list(Request $request)
    {
        $frequentMeals = $request->user()
            ->frequentMeals()
            ->orderBy('meal_name')
            ->get();

        return response()->json($frequentMeals);
    }

    /**
     * Store a newly created frequent meal in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'raw_input' => 'required|string|max:1000',
        ]);

        // Check if user has reached the limit of 100 frequent meals
        $count = $request->user()->frequentMeals()->count();
        if ($count >= 100) {
            return back()->withErrors([
                'raw_input' => 'You have reached the maximum limit of 100 frequent meals. Please delete some meals before adding new ones.'
            ])->withInput();
        }

        // Check if user has OpenAI API key configured
        if (empty($request->user()->open_api_key)) {
            return back()->withErrors([
                'raw_input' => 'Please configure your OpenAI API key in your profile settings to use this feature.'
            ])->withInput();
        }

        try {
            // Parse meal using OpenAI
            $nutrition = $this->parseMealWithOpenAI(
                $request->user()->open_api_key,
                $validated['raw_input']
            );

            // Check if a frequent meal with this name already exists for the user
            $existingMeal = $request->user()
                ->frequentMeals()
                ->where('meal_name', $nutrition['meal_name'])
                ->first();

            if ($existingMeal) {
                return back()->withErrors([
                    'raw_input' => 'A frequent meal with this name already exists. Please use a different description or edit the existing meal.'
                ])->withInput();
            }

            // Create the frequent meal
            $frequentMeal = $request->user()->frequentMeals()->create([
                'meal_name' => $nutrition['meal_name'],
                'raw_input' => $validated['raw_input'],
                'calories' => $nutrition['calories'],
                'protein' => $nutrition['protein'],
                'carbs' => $nutrition['carbs'],
                'fat' => $nutrition['fat'],
            ]);

            return back()->with('success', 'Frequent meal added successfully!');

        } catch (\Exception $e) {
            return back()->withErrors([
                'raw_input' => 'Failed to analyze meal: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Update the specified frequent meal in storage.
     */
    public function update(Request $request, FrequentMeal $frequentMeal)
    {
        // Authorization check
        if ($frequentMeal->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'meal_name' => 'required|string|max:255',
            'calories' => 'required|integer|min:0|max:10000',
            'protein' => 'required|numeric|min:0|max:1000',
            'carbs' => 'required|numeric|min:0|max:1000',
            'fat' => 'required|numeric|min:0|max:1000',
        ]);

        // Check if the new meal name conflicts with another frequent meal
        $existingMeal = $request->user()
            ->frequentMeals()
            ->where('meal_name', $validated['meal_name'])
            ->where('id', '!=', $frequentMeal->id)
            ->first();

        if ($existingMeal) {
            return response()->json([
                'error' => 'A frequent meal with this name already exists.',
                'errors' => [
                    'meal_name' => ['A frequent meal with this name already exists.']
                ]
            ], 422);
        }

        // Update the frequent meal
        $frequentMeal->update([
            'meal_name' => $validated['meal_name'],
            'calories' => $validated['calories'],
            'protein' => round($validated['protein'], 2),
            'carbs' => round($validated['carbs'], 2),
            'fat' => round($validated['fat'], 2),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Frequent meal updated successfully!',
            'meal' => $frequentMeal->fresh()
        ]);
    }

    /**
     * Remove the specified frequent meal from storage.
     */
    public function destroy(Request $request, FrequentMeal $frequentMeal)
    {
        // Authorization check
        if ($frequentMeal->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $frequentMeal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Frequent meal deleted successfully!'
        ]);
    }

    /**
     * Parse meal description using OpenAI API to extract nutritional information.
     */
    private function parseMealWithOpenAI(string $apiKey, string $mealInput): array
    {
        $client = \OpenAI::client($apiKey);

        // Use PromptSecurity service to prevent injection attacks
        $promptSecurity = new PromptSecurity();

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
}
