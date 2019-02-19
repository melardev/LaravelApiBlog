<?php

namespace App\Listeners;

use App\Events\UserPrePersistEvent;
use App\Models\Role;

class UserSubscriber
{
// php artisan make:listener UserListener

    public function subscribe($eventDispatcher) {
        // Listen to ArticlePrePersisttEvent emission
        // THis can be emitted thorugh event(new Arti..Event) helper method, or through $dipatchEvents array in any Model
        $eventDispatcher->listen(
            UserPrePersistEvent::class,
            'App\Listeners\UserSubscriber@onPrePersist'

        );
    }

    public function onPrePersist(UserPrePersistEvent $event) {
        if ($event->user->roles()->count() == 0)
            $event->user->roles()->attach(Role::where('name', Role::ROLE_USER)->first());
    }

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     */
    public function handle($event) {
        //
    }
}
