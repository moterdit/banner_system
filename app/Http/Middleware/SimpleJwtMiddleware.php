<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;

class SimpleJwtMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['error' => 'Token not provided.'], 401);
        }

        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET', 'your-secret-key'), 'HS256'));
            Log::info('Decoded JWT token', ['decoded' => (array) $decoded]);

            // Извлечение данных пользователя из 'data'
            $userData = (array)$decoded->data;

            // Преобразование данных пользователя в объект и добавление их в запрос
            $user = (object) $userData;
            $request->attributes->set('user', $user);

            Log::info('User extracted from JWT token', ['user' => $request->attributes->get('user')]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token is invalid: ' . $e->getMessage()], 401);
        }

        return $next($request);
    }
}
