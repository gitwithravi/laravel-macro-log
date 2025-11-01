<?php

namespace Database\Seeders;

use App\Models\MealEntry;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class MealEntrySeeder extends Seeder
{
    /**
     * Common meal examples with their typical nutritional values.
     */
    private array $mealTemplates = [
        // Breakfast
        ['meal_name' => 'Oatmeal with Berries', 'calories' => 320, 'protein' => 12, 'carbs' => 58, 'fat' => 6],
        ['meal_name' => 'Scrambled Eggs with Toast', 'calories' => 380, 'protein' => 22, 'carbs' => 32, 'fat' => 18],
        ['meal_name' => 'Greek Yogurt with Granola', 'calories' => 290, 'protein' => 18, 'carbs' => 42, 'fat' => 7],
        ['meal_name' => 'Avocado Toast', 'calories' => 350, 'protein' => 12, 'carbs' => 38, 'fat' => 18],
        ['meal_name' => 'Protein Smoothie', 'calories' => 280, 'protein' => 25, 'carbs' => 35, 'fat' => 5],

        // Lunch
        ['meal_name' => 'Grilled Chicken Salad', 'calories' => 420, 'protein' => 38, 'carbs' => 28, 'fat' => 16],
        ['meal_name' => 'Turkey Sandwich', 'calories' => 480, 'protein' => 32, 'carbs' => 52, 'fat' => 14],
        ['meal_name' => 'Quinoa Bowl with Vegetables', 'calories' => 450, 'protein' => 16, 'carbs' => 68, 'fat' => 12],
        ['meal_name' => 'Pasta with Marinara Sauce', 'calories' => 520, 'protein' => 18, 'carbs' => 82, 'fat' => 12],
        ['meal_name' => 'Tuna Salad Wrap', 'calories' => 390, 'protein' => 28, 'carbs' => 42, 'fat' => 11],

        // Dinner
        ['meal_name' => 'Grilled Salmon with Rice', 'calories' => 580, 'protein' => 42, 'carbs' => 58, 'fat' => 18],
        ['meal_name' => 'Chicken Stir Fry', 'calories' => 520, 'protein' => 38, 'carbs' => 56, 'fat' => 14],
        ['meal_name' => 'Beef Tacos', 'calories' => 620, 'protein' => 35, 'carbs' => 58, 'fat' => 26],
        ['meal_name' => 'Vegetarian Curry', 'calories' => 480, 'protein' => 14, 'carbs' => 72, 'fat' => 16],
        ['meal_name' => 'Grilled Steak with Potatoes', 'calories' => 680, 'protein' => 48, 'carbs' => 52, 'fat' => 28],

        // Snacks
        ['meal_name' => 'Apple with Peanut Butter', 'calories' => 180, 'protein' => 6, 'carbs' => 22, 'fat' => 8],
        ['meal_name' => 'Protein Bar', 'calories' => 220, 'protein' => 20, 'carbs' => 24, 'fat' => 7],
        ['meal_name' => 'Mixed Nuts', 'calories' => 170, 'protein' => 6, 'carbs' => 8, 'fat' => 14],
        ['meal_name' => 'Banana', 'calories' => 105, 'protein' => 1.3, 'carbs' => 27, 'fat' => 0.4],
        ['meal_name' => 'Greek Yogurt', 'calories' => 150, 'protein' => 15, 'carbs' => 18, 'fat' => 4],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please seed users first.');
            return;
        }

        foreach ($users as $user) {
            $this->seedMealsForUser($user);
        }

        $this->command->info('Meal entries seeded successfully!');
    }

    /**
     * Seed meal entries for a specific user for the past 7 days.
     */
    private function seedMealsForUser(User $user): void
    {
        for ($daysAgo = 6; $daysAgo >= 0; $daysAgo--) {
            $date = Carbon::now()->subDays($daysAgo);

            // Generate 2-5 meals per day randomly
            $mealsPerDay = rand(2, 5);

            for ($i = 0; $i < $mealsPerDay; $i++) {
                $mealTemplate = $this->mealTemplates[array_rand($this->mealTemplates)];

                // Add some variation to the nutritional values (±15%)
                $caloriesVariation = rand(85, 115) / 100;
                $proteinVariation = rand(85, 115) / 100;
                $carbsVariation = rand(85, 115) / 100;
                $fatVariation = rand(85, 115) / 100;

                // Generate realistic meal times
                $mealTime = $this->generateMealTime($i, $mealsPerDay);

                // Generate raw input that mimics user input
                $rawInput = $this->generateRawInput($mealTemplate['meal_name']);

                MealEntry::create([
                    'user_id' => $user->id,
                    'logged_date' => $date->format('Y-m-d'),
                    'logged_time' => $mealTime,
                    'raw_input' => $rawInput,
                    'meal_name' => $mealTemplate['meal_name'],
                    'calories' => round($mealTemplate['calories'] * $caloriesVariation),
                    'protein' => round($mealTemplate['protein'] * $proteinVariation, 2),
                    'carbs' => round($mealTemplate['carbs'] * $carbsVariation, 2),
                    'fat' => round($mealTemplate['fat'] * $fatVariation, 2),
                ]);
            }
        }

        $this->command->info("Seeded meals for user: {$user->name}");
    }

    /**
     * Generate a realistic meal time based on the meal index.
     */
    private function generateMealTime(int $mealIndex, int $totalMeals): string
    {
        $baseTimes = [
            0 => ['07:00', '09:00'], // Breakfast
            1 => ['12:00', '14:00'], // Lunch
            2 => ['15:00', '17:00'], // Snack
            3 => ['18:00', '20:00'], // Dinner
            4 => ['20:30', '22:00'], // Late snack
        ];

        if (isset($baseTimes[$mealIndex])) {
            $timeRange = $baseTimes[$mealIndex];
            $startTime = strtotime($timeRange[0]);
            $endTime = strtotime($timeRange[1]);
            $randomTime = rand($startTime, $endTime);
            return date('H:i', $randomTime);
        }

        // Fallback for any additional meals
        return sprintf('%02d:%02d', rand(6, 22), rand(0, 59));
    }

    /**
     * Generate raw input that mimics natural user input.
     */
    private function generateRawInput(string $mealName): string
    {
        $templates = [
            "I had {meal} for {mealtype}",
            "{meal}",
            "Just ate {meal}",
            "Logged: {meal}",
            "{meal} - it was delicious!",
            "Today's {mealtype}: {meal}",
        ];

        $template = $templates[array_rand($templates)];

        $mealTypes = ['breakfast', 'lunch', 'dinner', 'snack', 'meal'];
        $mealType = $mealTypes[array_rand($mealTypes)];

        return str_replace(
            ['{meal}', '{mealtype}'],
            [$mealName, $mealType],
            $template
        );
    }
}
