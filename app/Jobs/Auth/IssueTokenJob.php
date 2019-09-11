<?php

namespace App\Jobs\Auth;

use App\Jobs\AbstractJob;
use App\Jobs\User\CreateUserJob;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Log;

class IssueTokenJob extends AbstractJob
{
    /**
     * @var string
     */
    private $userName;

    /**
     * @param string $userName
     * @return void
     */
    public function __construct(string $userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return array Array containing the token string and a boolean to identify if a new user has been created
     */
    public function handle(): array
    {
        // Find the existing user with the given name or create a new one
        if (! $user = User::findByName($this->userName))
        {
            $token = User::makeToken();

            dispatch_now(new CreateUserJob($this->userName, null, null, $token, true));

            return [$token, true];
        }

        // To receive a token, the user must be enabled
        if (! $user->isEnabled())
        {
            throw new AuthorizationException('User cannot receive a new token.');
        }

        // Assign the user a new token
        $user->token = $token = User::makeToken();

        $user->save();

        //event(new TokenIssued($user, $token));

        Log::info('Auth token issued', ['user' => $user, 'token' => $token]);

        return [$token, false];
    }
}
