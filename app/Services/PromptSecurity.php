<?php

namespace App\Services;

class PromptSecurity
{
    /**
     * Sanitize user input for safe inclusion in prompts.
     * Removes potential prompt injection patterns.
     */
    public static function sanitize(string $input): string
    {
        // Remove or escape dangerous patterns
        $patterns = [
            // Common injection phrases
            '/ignore.*?(previous|above|all).*?instructions?/i',
            '/disregard.*?instructions?/i',
            '/forget.*?instructions?/i',
            '/override.*?instructions?/i',
            '/new\s+instructions?:/i',
            '/system\s*:\s*/i',
            '/assistant\s*:\s*/i',

            // Role switching attempts
            '/you\s+are\s+now/i',
            '/act\s+as\s+/i',
            '/pretend\s+to\s+be/i',
            '/roleplay\s+as/i',

            // Command injection patterns
            '/\]\]>/',
            '/<!\[CDATA\[/',
            '/\${.*?}/',
            '/`.*?`/',
        ];

        foreach ($patterns as $pattern) {
            $input = preg_replace($pattern, '', $input);
        }

        // Escape special characters that might break prompt structure
        $input = str_replace(['\\', '"', "\n", "\r", "\t"], [' ', "'", ' ', ' ', ' '], $input);

        // Remove multiple spaces
        $input = preg_replace('/\s+/', ' ', $input);

        // Truncate to reasonable length to prevent token stuffing
        if (strlen($input) > 1000) {
            $input = substr($input, 0, 1000) . '...';
        }

        return trim($input);
    }

    /**
     * Wrap user input in clear boundaries for the AI model.
     */
    public static function wrapUserInput(string $input): string
    {
        $sanitized = self::sanitize($input);
        return "===USER INPUT START===\n{$sanitized}\n===USER INPUT END===";
    }

    /**
     * Validate numeric inputs to prevent injection via numbers.
     */
    public static function sanitizeNumeric($value, float $min = 0, float $max = 10000): float
    {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException('Value must be numeric');
        }

        $value = (float) $value;

        if ($value < $min || $value > $max) {
            throw new \InvalidArgumentException("Value must be between {$min} and {$max}");
        }

        return round($value, 2);
    }

    /**
     * Create a secure prompt with user input properly isolated.
     */
    public static function createSecurePrompt(string $systemPrompt, string $userInput, array $parameters = []): array
    {
        // Sanitize all parameters
        $safeParams = [];
        foreach ($parameters as $key => $value) {
            if (is_string($value)) {
                $safeParams[$key] = self::sanitize($value);
            } elseif (is_numeric($value)) {
                $safeParams[$key] = self::sanitizeNumeric($value);
            } else {
                $safeParams[$key] = $value;
            }
        }

        // Build user prompt with clear boundaries
        $userPrompt = self::wrapUserInput($userInput);

        // Add parameters if provided
        if (!empty($safeParams)) {
            $userPrompt .= "\n\nParameters:\n";
            foreach ($safeParams as $key => $value) {
                $userPrompt .= "- {$key}: {$value}\n";
            }
        }

        return [
            'system' => $systemPrompt,
            'user' => $userPrompt
        ];
    }
}