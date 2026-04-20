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
                'components' => $nutrition['components'] ?? null,
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
     * Update the component breakdown for a frequent meal and recompute totals.
     */
    public function updateComponents(Request $request, FrequentMeal $frequentMeal)
    {
        if ($frequentMeal->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'components' => ['required', 'array', 'min:1', 'max:30'],
            'components.*.name' => ['required', 'string', 'max:100'],
            'components.*.grams' => ['required', 'numeric', 'min:0', 'max:5000'],
            'components.*.calories' => ['required', 'numeric', 'min:0', 'max:10000'],
            'components.*.protein' => ['required', 'numeric', 'min:0', 'max:1000'],
            'components.*.carbs' => ['required', 'numeric', 'min:0', 'max:1000'],
            'components.*.fat' => ['required', 'numeric', 'min:0', 'max:1000'],
        ]);

        $components = array_values(array_map(fn (array $c) => [
            'name' => (string) $c['name'],
            'grams' => round((float) $c['grams'], 1),
            'calories' => (int) round((float) $c['calories']),
            'protein' => round((float) $c['protein'], 2),
            'carbs' => round((float) $c['carbs'], 2),
            'fat' => round((float) $c['fat'], 2),
        ], $validated['components']));

        $frequentMeal->update([
            'components' => $components,
            'calories' => (int) round(array_sum(array_column($components, 'calories'))),
            'protein' => round(array_sum(array_column($components, 'protein')), 2),
            'carbs' => round(array_sum(array_column($components, 'carbs')), 2),
            'fat' => round(array_sum(array_column($components, 'fat')), 2),
        ]);

        return back()->with('success', 'Breakdown updated!');
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

        return back()->with('success', 'Frequent meal deleted successfully!');
    }

    /**
     * Parse meal description using OpenAI API to extract nutritional information.
     */
    private function parseMealWithOpenAI(string $apiKey, string $mealInput): array
    {
        $client = \OpenAI::client($apiKey);

        // Use PromptSecurity service to prevent injection attacks
        $promptSecurity = new PromptSecurity();

        $systemPrompt = "You are a nutrition expert specializing in Indian and international cuisine. "
            . "Estimate macros by breaking the meal into its individual components and reasoning about portions explicitly — do NOT guess a single total.\n\n"
            . "For every meal:\n"
            . "1. Split it into each distinct food item (e.g. '2 rotis + dal + rice' = three components).\n"
            . "2. For each component, resolve the portion to an explicit weight in grams. "
            . "**If the user provided an explicit quantity (grams, count, tsp/tbsp/cup, ml), use their value verbatim — do NOT substitute a default.** "
            . "Only fall back to typical per-unit weights when the user is vague (e.g. 'a plate of biryani'). Reference weights:\n"
            . "   - Breads: 1 roti/chapati ≈ 40g, 1 slice bread ≈ 30g, 1 paratha ≈ 60g\n"
            . "   - South Indian: 1 idli ≈ 35g, 1 medium dosa ≈ 80g, 1 vada ≈ 50g\n"
            . "   - Snacks: 1 samosa ≈ 60g, 1 pakora ≈ 25g\n"
            . "   - Cooked staples: 1 cup rice ≈ 180g, 1 cup dal ≈ 200g, 1 bowl curry ≈ 200g\n"
            . "   - Proteins: 1 egg ≈ 50g, 1 chicken breast ≈ 170g\n"
            . "   - Vegetables: 1 medium onion ≈ 110g, 1 medium tomato ≈ 120g, 1 medium potato ≈ 170g, 1 medium carrot ≈ 60g, 1 clove garlic ≈ 3g, 1 inch ginger ≈ 6g\n"
            . "   - Fats/oils by volume: 1 tsp oil/ghee ≈ 4.5g, 1 tbsp oil/ghee ≈ 14g, 1 tbsp butter ≈ 14g\n"
            . "   - Liquids: 1 cup ≈ 240ml, 1 tsp ≈ 5ml, 1 tbsp ≈ 15ml\n"
            . "3. Use realistic per-100g values grounded in standard references (IFCT for Indian foods, USDA for others). If the user did NOT specify the cooking fat, assume ~5–10g oil/ghee per Indian gravy serving; if they DID specify it, use their amount and do not add extra.\n"
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

        $components = array_values(array_map(fn (array $c) => [
            'name' => (string) ($c['name'] ?? ''),
            'grams' => round((float) ($c['grams'] ?? 0), 1),
            'calories' => (int) round((float) ($c['calories'] ?? 0)),
            'protein' => round((float) ($c['protein'] ?? 0), 2),
            'carbs' => round((float) ($c['carbs'] ?? 0), 2),
            'fat' => round((float) ($c['fat'] ?? 0), 2),
        ], $parsed['components']));

        return [
            'meal_name' => $parsed['meal_name'],
            'calories' => (int) round((float) $totals['calories']),
            'protein' => round((float) $totals['protein'], 2),
            'carbs' => round((float) $totals['carbs'], 2),
            'fat' => round((float) $totals['fat'], 2),
            'components' => $components,
        ];
    }
}
