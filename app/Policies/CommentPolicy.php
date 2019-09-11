<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy extends AbstractPolicy
{
    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function delete(User $user, Comment $comment): bool
    {
        // A comment can only be deleted by its author
        return $comment->hasBeenWrittenBy($user);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function index(User $user): bool
    {
        return true;
    }

    /**
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function show(User $user, Comment $comment): bool
    {
        return true;
    }

    /**
     * @param User $user
     * @param Comment $comment
     * @return bool
     */
    public function update(User $user, Comment $comment): bool
    {
        // A comment can only be updated by its author
        return $comment->hasBeenWrittenBy($user);
    }
}
