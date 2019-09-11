<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Auth\IssueTokenRequest;
use App\Jobs\Auth\IssueTokenJob;
use Illuminate\Http\JsonResponse;

class TokensController
{
    /**
     * Issue an auth token.
     *
     * @param IssueTokenRequest $request
     * @return JsonResponse
     */
    public function store(IssueTokenRequest $request): JsonResponse
    {
        $userName = $request->input(IssueTokenRequest::USER_NAME);

        [$issuedToken, $userCreated] = dispatch_now(new IssueTokenJob($userName));

        $responseStatus = $userCreated ? 201 : 200;

        return response()->json(
            [
                'data' => [
                    'token'        => $issuedToken,
                    'user_created' => $userCreated,
                ],
            ],
            $responseStatus
        );
    }
}
