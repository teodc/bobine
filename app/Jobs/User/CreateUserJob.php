<?php

namespace App\Jobs\User;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class CreateUserJob
{
    /**
     * @var string|null
     */
    private $email;

    /**
     * @var bool
     */
    private $isEnabled;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $password;

    /**
     * @var string|null
     */
    private $token;

    /**
     * @param string $name
     * @param string|null $email
     * @param string|null $password
     * @param string|null $token
     * @param bool $isEnabled
     * @return void
     */
    public function __construct(
        string $name,
        ?string $email = null,
        ?string $password = null,
        ?string $token = null,
        bool $isEnabled = true
    )
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->token = $token;
        $this->isEnabled = $isEnabled;
    }

    /**
     * @return User
     */
    public function handle(): User
    {
        $attributes = [
            'name'       => $this->name,
            'email'      => $this->email,
            'password'   => $this->password,
            'token'      => $this->token ?: User::makeToken(),
            'is_enabled' => $this->isEnabled,
        ];

        $user = new User($attributes);

        $user->save();

        //event(new UserCreated($user));

        Log::info('User created', ['user' => $user]);

        return $user;
    }
}
