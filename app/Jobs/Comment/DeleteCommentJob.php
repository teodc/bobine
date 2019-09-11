<?php

namespace App\Jobs\Comment;

use App\Models\Comment;
use App\Services\Cacher;
use Illuminate\Support\Facades\Log;

class DeleteCommentJob
{
    /**
     * @var Comment
     */
    private $comment;

    /**
     * @param Comment $comment
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        Cacher::delete($this->comment);

        //event(new CommentDeleted($this->comment));

        Log::info('Comment deleted', ['comment' => $this->comment]);
    }
}
