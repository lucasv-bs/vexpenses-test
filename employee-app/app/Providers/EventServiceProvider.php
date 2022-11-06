<?php

namespace App\Providers;

use App\Jobs\EmployeeFileUploaded;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        \App::bindMethod(EmployeeFileUploaded::class . '@handle', fn($job) => $job->handle());
    }
}
