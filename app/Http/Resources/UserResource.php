<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'email'          => $this->email,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
            'comments_count' => (int) $this->comments_count ?? 0,
        ];
    }
}
