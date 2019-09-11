<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\FormRequest;

class IssueTokenRequest extends FormRequest
{
    public const USER_NAME = 'username';
    //public const USER_PASSWORD = 'password';

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            self::USER_NAME => ['required', 'string', 'alpha_dash', 'max:150'],
            //self::USER_PASSWORD  => ['required', 'string', 'password'],
        ];
    }
}
