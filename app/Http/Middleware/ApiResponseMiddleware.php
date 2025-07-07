<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Если это API запрос и есть ошибки валидации
        if ($request->is('api/*') && $response->status() === 422) {
            $data = json_decode($response->getContent(), true);

            return response()->json([
                'success' => false,
                'message' => 'Ошибка валидации',
                'errors' => $data['errors'] ?? []
            ], 422);
        }

        return $response;
    }
}
