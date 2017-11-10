<?php

namespace App\Listeners;

use App\Exceptions\TrackingException;
use App\Services\ActiveCampaignService;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactEventSubscriber implements ShouldQueue
{
    /**
     * Set up our handy-dandy Active Campaign Service.
     * 
     * @param \App\Services\ActiveCampaignService $acService
     */
    public function __construct(ActiveCampaignService $acService)
    {
        $this->acService = $acService;
    }

    /**
     * Handle user login events.
     */
    public function onContactCreated($event) {
        $acResponse = $this->acService->sendEvent(
            'contact_event',
            'created',
            $event->contact->email
        );

        if (!(int)$acResponse->success) {
            throw new TrackingException($acResponse->result_message);
        }
    }

    /**
     * Handle user logout events.
     */
    public function onContactUpdated($event) {
        $acResponse = $this->acService->sendEvent(
            'contact_event',
            'updated',
            $event->contact->email
        );

        if (!(int)$acResponse->success) {
            throw new TrackingException($acResponse->result_message);
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
             'App\Events\CreatedContact',
             'App\Listeners\ContactEventSubscriber@onContactCreated'
        );

        $events->listen(
             'App\Events\UpdatedContact',
             'App\Listeners\ContactEventSubscriber@onContactUpdated'
        );
    }
}