<?php

namespace App\Http\Middleware;

use App\Exceptions\BasicAuthenticationException;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;

class AuthenticateWithBasicAuth
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
     * Authenticate a user via basic authentication.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $guard
     * @throws BasicAuthenticationException
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $guard)
    {
        $credentials = [
            'email'      => $request->getUser(),
            'password'   => $request->getPassword(),
            'is_enabled' => true,
        ];

        if (! $this->auth->guard($guard)->once($credentials))
        {
            throw new BasicAuthenticationException('Invalid credentials.', [$guard]);
        }

        return $next($request);
    }
}
