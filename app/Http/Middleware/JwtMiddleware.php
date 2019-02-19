<?php


namespace App\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    // php artisan make:middleware JwtMiddleware
    public function handle($request, Closure $next, $optional = null) {

        try {
            // $user = JWTAuth::parseToken()->authenticate();
            if (!$user = $this->auth->parseToken()->authenticate()) {
                return response()->json(['success' => false, 'full_messages' => ['JWT error: User not found']]);
            }
        } catch (TokenExpiredException $ex) {
            return response()->json(['success' => false, 'full_messages' => ['Expired']]);
        } catch (TokenInvalidException $e) {
            return response()->json(['success' => false, 'full_messages' => ['Invalid']]);
        } catch (JWTException $e) {
            return response()->json(['success' => false, 'full_messages' => ['Error']]);
        }

        return $next($request);
    }

    protected function respondError($message) {
        return response()->json([
                                    'errors' => [
                                        'message' => $message,
                                        'status_code' => 401
                                    ]
                                ], 401);
    }
}
