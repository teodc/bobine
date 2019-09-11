<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;

class PersistResourceJob extends AbstractJob implements ShouldQueue
{
    /**
     * The number of seconds before the job should be made available.
     *
     * @var int|null
     */
    public $delay = 300;

    /**
     * The resource to persist.
     *
     * @var Model
     */
    private $model;

    /**
     * @param Model $model
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->model->save();
    }
}
