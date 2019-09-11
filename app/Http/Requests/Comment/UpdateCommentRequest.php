<?php

namespace App\Http\Requests\Comment;

use App\Http\Requests\FormRequest;

class UpdateCommentRequest extends FormRequest
{
    public const BODY = 'body';

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        $comment = $this->route('comment');

        return $this->user()->can('update', $comment);
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
