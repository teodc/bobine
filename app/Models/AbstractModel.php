<?php

namespace App\Models;

use App\Support\HasUuidPrimaryKeyTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class AbstractModel extends Model
{
    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 50;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // If the model uses a UUID as primary key,
        // ensure the key value is set before storing it in the database.
        if (in_array(HasUuidPrimaryKeyTrait::class, class_uses(self::class)))
        {
            static::creating(function ($model)
            {
                $model->{$this->getKeyName()} = $model->{$this->getKeyName()} ?: Str::uuid()->toString();
            });
        }
    }
}
