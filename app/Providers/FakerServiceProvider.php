<?php

namespace App\Providers;

use App\Faker\CustomProvider;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\ServiceProvider;

class FakerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Generator::class, function () {
            $faker = Factory::create();
            $faker->addProvider(new CustomProvider($faker));
            return $faker;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
