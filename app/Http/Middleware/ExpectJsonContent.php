<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class ExpectJsonContent
{
    /**
     * @var array
     */
    private $exemptedUris = [
        //
    ];

    /**
     * @var array
     */
    private $methodsToCheck = [
        'POST',
        'PUT',
        'PATCH',
    ];

    /**
     * @param Request $request
     * @param Closure $next
     * @throws UnsupportedMediaTypeHttpException
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->isJson() && ! $this->isMethodExempted($request) && ! $this->isUriExempted($request))
        {
            throw new UnsupportedMediaTypeHttpException('JSON media type expected.');
        }

        return $next($request);
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function isMethodExempted(Request $request): bool
    {
        return ! in_array($request->method(), $this->methodsToCheck);
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function isUriExempted(Request $request): bool
    {
        foreach ($this->exemptedUris as $exemptedUri)
        {
            if ($request->is($exemptedUri !== '/' ? trim($exemptedUri, '/') : $exemptedUri))
            {
                return true;
            }
        }

        return false;
    }
}
