<?php

namespace Tests\Feature\Api\Auth;

use App\Http\Requests\Auth\IssueTokenRequest;
use App\Models\User;
use Tests\Feature\Api\ApiTestCase;

class TokensTest extends ApiTestCase
{
    /**
     * @test
     * @group api_auth_tokens
     * @return void
     */
    public function it_issues_a_token_to_a_new_user(): void
    {
        $data = [
            IssueTokenRequest::USER_NAME => 'haruko',
        ];

        $this->json('POST', '/auth/tokens', $data)
             ->assertStatus(201)
             ->assertJsonFragment([
                 'user_created' => true,
             ]);
    }

    /**
     * @test
     * @group api_auth_tokens
     * @return void
     */
    public function it_issues_a_token_to_an_existing_user(): void
    {
        $user = factory(User::class)->state('enabled')->create([
            'name'  => 'haruko',
            'token' => 'qwerty123',
        ]);

        $data = [
            IssueTokenRequest::USER_NAME => $user->name,
        ];

        $this->json('POST', '/auth/tokens', $data)
             ->assertStatus(200)
             ->assertJsonFragment([
                 'user_created' => false,
             ]);
    }
}
