<?php

namespace Tests\Feature\Api\V1;

use App\Models\Comment;
use App\Models\User;
use Tests\Feature\Api\ApiTestCase;

class CommentsTest extends ApiTestCase
{
    /**
     * @test
     * @group api_v1_comments
     * @return void
     */
    public function it_creates_a_comment(): void
    {
        $user = $this->actingAsUser();

        $data = [
            'body' => 'This is a body.',
        ];

        $this->json('POST', '/v1/comments', $data)
             ->assertStatus(201)
             ->assertJsonFragment([
                 'author_id' => $user->id,
                 'body'      => 'This is a body.',
             ]);
    }

    /**
     * @test
     * @group api_v1_comments
     * @return void
     */
    public function it_deletes_a_comment_written_by_user(): void
    {
        $this->actingAsUser();

        $comment = factory(Comment::class)->create();

        $this->json('DELETE', '/v1/comments/'.$comment->id)
             ->assertStatus(204);
    }

    /**
     * @test
     * @group api_v1_comments
     * @return void
     */
    public function it_doesnt_delete_a_comment_written_by_another_user(): void
    {
        $this->actingAsUser();

        $anotherUser = factory(User::class)->state('enabled')->create();
        $comment = factory(Comment::class)->create(['author_id' => $anotherUser->id]);

        $this->json('DELETE', '/v1/comments/'.$comment->id)
             ->assertStatus(403);
    }

    /**
     * @test
     * @group api_v1_comments
     * @return void
     */
    public function it_lists_the_comments_sorted_by_latest(): void
    {
        $this->actingAsUser();

        factory(User::class, 2)->state('enabled')->create();
        factory(Comment::class, 10)->create();

        $this->json('GET', '/v1/comments')
             ->assertStatus(200)
             ->assertJsonCount(10, 'data');
    }

    /**
     * @test
     * @group api_v1_comments
     * @return void
     */
    public function it_shows_a_comment(): void
    {
        $this->actingAsUser();

        $author = factory(User::class)->state('enabled')->create();
        $comment = factory(Comment::class)->create(['author_id' => $author->id]);

        $this->json('GET', '/v1/comments/'.$comment->id)
             ->assertStatus(200);
    }

    /**
     * @test
     * @group api_v1_comments
     * @return void
     */
    public function it_updates_a_comment(): void
    {
        $user = $this->actingAsUser();

        $comment = factory(Comment::class)->create(['author_id' => $user->id]);

        $data = [
            'body' => 'This is a new body.',
        ];

        $response = $this->json('PUT', '/v1/comments/'.$comment->id, $data)
                         ->assertStatus(200)
                         ->assertJsonFragment([
                             'id'        => $comment->id,
                             'author_id' => $user->id,
                             'body'      => 'This is a new body.',
                         ]);
    }

    /**
     * @return User
     */
    private function actingAsUser(): User
    {
        $user = factory(User::class)->state('enabled')->create([
            'name'  => 'haruko',
            'token' => 'qwerty123',
        ]);

        $this->actingAs($user);

        return $user;
    }
}
