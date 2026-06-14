<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    /**
     * Fields that should NOT be sanitized (HTML allowed).
     *
     * @var array<int, string>
     */
    protected array $except = [
        // Add fields here if admin needs to submit HTML content
        // e.g. 'description', 'content'
    ];

    /**
     * Handle an incoming request.
     *
     * Strips HTML tags from all string input values to prevent XSS.
     * Fields listed in $except are left untouched.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();
        $sanitized = $this->sanitizeArray($input);
        $request->merge($sanitized);

        return $next($request);
    }

    /**
     * Recursively sanitize an array of input values.
     *
     * @param  array  $data
     * @param  string  $parentKey  Dot-notation parent key for nested exclusion checks
     * @return array
     */
    private function sanitizeArray(array $data, string $parentKey = ''): array
    {
        foreach ($data as $key => $value) {
            $fullKey = $parentKey ? "{$parentKey}.{$key}" : $key;

            if (is_array($value)) {
                $data[$key] = $this->sanitizeArray($value, $fullKey);
            } elseif (is_string($value) && !in_array($key, $this->except, true) && !in_array($fullKey, $this->except, true)) {
                $data[$key] = strip_tags($value);
            }
        }

        return $data;
    }
}
