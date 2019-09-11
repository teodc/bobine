<?php

namespace App\Jobs\Comment;

use App\Models\Comment;
use App\Services\Cacher;
use Illuminate\Support\Facades\Log;

class CreateCommentJob
{
    /**
     * @var string
     */
    private $authorId;

    /**
     * @var string
     */
    private $body;

    /**
     * @param string $authorId
     * @param string $body
     * @return void
     */
    public function __construct(string $authorId, string $body)
    {
        $this->authorId = $authorId;
        $this->body = $body;
    }

    /**
     * @return Comment
     */
    public function handle(): Comment
    {
        $attributes = [
            'author_id' => $this->authorId,
            'body'      => $this->body,
        ];

        $comment = new Comment($attributes);

        Cacher::store($comment);

        //event(new CommentCreated($comment));

        Log::info('Comment created', ['comment' => $comment]);

        return $comment;
    }
}
