<?php

namespace App\Providers;

use App\Events\TagCreatedOrUpdatedEvent;
use App\Listeners\TagChangedListener;
use App\Listeners\UserSubscriber;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TagCreatedOrUpdatedEvent::class =>
            [TagChangedListener::class]
    ];

    // Register our Listeners
    protected $subscribe = [
        UserSubscriber::class
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot() {
        parent::boot();

        //
    }
}
