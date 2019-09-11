<?php

namespace App\Providers;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        Carbon::setLocale(config('app.locale'));
    }

    /**
     * @return void
     */
    public function register(): void
    {
        if (app()->environment('local', 'testing'))
        {
            $this->registerJapaneseFaker();
        }
    }

    /**
     * @return void
     */
    private function registerJapaneseFaker(): void
    {
        $this->app->singleton(
            Generator::class,
            function (Application $app)
            {
                return Factory::create('ja_JP');
            }
        );
    }
}
