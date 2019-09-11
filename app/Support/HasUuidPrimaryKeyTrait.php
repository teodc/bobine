<?php

namespace App\Support;

use Illuminate\Support\Str;

trait HasUuidPrimaryKeyTrait
{
    /**
     * Set the UUID primary key of the model if not already set.
     *
     * @return void
     */
    public function setUuidPrimaryKey(): void
    {
        $this->attributes[$this->getKeyName()] = $this->attributes[$this->getKeyName()] ?? Str::uuid()->toString();
    }
}
