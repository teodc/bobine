<?php

namespace App\Http\Requests\Comment;

use App\Http\Requests\FormRequest;
use App\Models\Comment;

class CreateCommentRequest extends FormRequest
{
    public const BODY = 'body';

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Comment::class);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            self::BODY => ['required', 'string', 'max:250'],
        ];
    }
}
