<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Auth\Events\Registered' => [
            'App\Listeners\RegisterUser'
        ],
        'App\Events\CreatedContact' => [
            'App\Listeners\CreateContact',
        ],
        'App\Events\UpdatedContact' => [
            'App\Listeners\UpdateContact'
        ],
        'App\Events\DeletedContact' => [
            'App\Listeners\DeleteContact'
        ]
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'App\Listeners\ContactEventSubscriber',
    ];
    
    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
