<?php

namespace App\Models;

use App\Contracts\CacheableContract;
use App\Support\CacheableTrait;
use App\Support\HasUuidPrimaryKeyTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name
 * @property string $email
 * @property string|null $password
 * @property string|null $token
 * @property bool $is_enabled
 *
 * @property Collection $comments
 */
class User extends AbstractModel implements AuthenticatableContract, AuthorizableContract, CacheableContract
{
    use Authenticatable, Authorizable, Notifiable, HasUuidPrimaryKeyTrait, CacheableTrait;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'token',
        'is_enabled',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * The relationship counts that should be eager loaded on every query.
     *
     * @var array
     */
    protected $withCount = ['comments'];

    /**
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setUuidPrimaryKey();
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'author_id')
                    ->latest();
    }

    /**
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'name';
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool) $this->is_enabled;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeDisabled(Builder $query): Builder
    {
        return $query->where('is_enabled', false);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    /**
     * @param string|null $value
     * @return void
     */
    public function setEmailAttribute(?string $value): void
    {
        $this->attributes['email'] = $value ? mb_strtolower($value) : null;
    }

    /**
     * @param string|null $value
     * @return void
     */
    public function setPasswordAttribute(?string $value): void
    {
        $this->attributes['password'] = $value ? Hash::make($value) : null;
    }

    /**
     * @param string|null $value
     * @return void
     */
    public function setTokenAttribute(?string $value): void
    {
        $this->attributes['token'] = $value ? hash('sha256', $value) : null;
    }

    /**
     * @param string $name
     * @return self|null
     */
    public static function findByName(string $name): ?self
    {
        return self::query()
                   ->where('name', $name)
                   ->first();
    }

    /**
     * @return string
     */
    public static function makeToken(): string
    {
        return Str::random(60);
    }
}
