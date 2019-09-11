<?php

namespace Tests\Feature\Api\V1;

use App\Models\Comment;
use App\Models\User;
use Tests\Feature\Api\ApiTestCase;

class UsersTest extends ApiTestCase
{
    /**
     * @test
     * @group api_v1_users
     * @return void
     */
    public function it_shows_a_user(): void
    {
        // Act
        $this->actingAsUser();

        // Arrange
        $author = factory(User::class)->state('enabled')->create();
        factory(Comment::class, 10)->create(['author_id' => $author->id]);

        // Assert
        $this->json('GET', '/v1/users/'.$author->id)
             ->assertStatus(200)
             ->assertJsonFragment([
                 'id'             => $author->id,
                 'name'           => $author->name,
                 'comments_count' => 10,
             ]);
    }

    /**
     * @return void
     */
    private function actingAsUser(): void
    {
        $user = factory(User::class)->state('enabled')->create([
            'name'  => 'haruko',
            'token' => 'qwerty123',
        ]);

        $this->actingAs($user);
    }
}
