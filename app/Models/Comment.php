<?php

namespace App\Models;

use App\Contracts\CacheableContract;
use App\Support\CacheableTrait;
use App\Support\HasUuidPrimaryKeyTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $author_id
 * @property string $body
 *
 * @property User $author
 */
class Comment extends AbstractModel implements CacheableContract
{
    use HasUuidPrimaryKeyTrait, CacheableTrait;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'author_id',
        'body',
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
    protected $table = 'comments';

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['author'];

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
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Check if the given user is the author of the comment.
     *
     * @param User $user
     * @return bool
     */
    public function hasBeenWrittenBy(User $user): bool
    {
        return $this->author_id === $user->id;
    }
}
