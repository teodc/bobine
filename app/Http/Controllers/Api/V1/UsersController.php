<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\AbstractController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersController extends AbstractController
{
    /**
     * @param User $user
     * @return JsonResource
     */
    public function show(User $user): JsonResource
    {
        $this->authorize('show', $user);

        return new UserResource($user);
    }
}
