<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * @var Auth
     */
    private $auth;

    /**
     * @param Auth $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @param string $guard
     * @throws AuthenticationException
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $guard)
    {
        // Authenticate a user from the token passed in the request
        if (! $user = $this->auth->guard($guard)->user($request))
        {
            throw new AuthenticationException('Invalid credentials.', [$guard]);
        }

        // The user must be enabled
        if (! $user->isEnabled())
        {
            throw new AuthenticationException('User is not enabled.', [$guard]);
        }

        return $next($request);
    }
}
