<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class AuthenticateWithJWT extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param bool $optional
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $optional = null)
    {
        $this->auth->setRequest($request);

        try {
            if (!$user = $this->auth->parseToken()->authenticate()) {
                return $this->respondError('Auth: User not found');
            }
        } catch (TokenExpiredException $e) {
            return $this->respondError('Auth: Token has expired');
        } catch (TokenInvalidException $e) {
            return $this->respondError('Auth: Token is invalid');
        } catch (JWTException $e) {
            if ($optional === null) {
                return $this->respondError('Auth: Token is absent');
            }
        }

        return $next($request);
    }

    /**
     * Respond with json error message.
     *
     * @param string $message
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondError(string $message, int $status = 401): JsonResponse
    {
        return response()->json([
            'error' => [
                'message' => $message,
            ]
        ], $status);
    }
}
