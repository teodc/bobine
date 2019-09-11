<?php

namespace App\Jobs\Comment;

use App\Models\Comment;
use App\Services\Cacher;
use Illuminate\Support\Facades\Log;

class UpdateCommentJob
{
    /**
     * @var string
     */
    private $body;

    /**
     * @var Comment
     */
    private $comment;

    /**
     * @param Comment $comment
     * @param string $body
     * @return void
     */
    public function __construct(Comment $comment, string $body)
    {
        $this->comment = $comment;
        $this->body = $body;
    }

    /**
     * @return Comment
     */
    public function handle(): Comment
    {
        $this->comment->body = $this->body;

        Cacher::store($this->comment);

        //event(new CommentUpdated($this->comment));

        Log::info('Comment updated', ['comment' => $this->comment]);

        return $this->comment;
    }
}
