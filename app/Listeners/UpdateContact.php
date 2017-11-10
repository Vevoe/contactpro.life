<?php

namespace App\Listeners;

use App\Events\UpdatedContact;
use App\Services\ActiveCampaignService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateContact implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ActiveCampaignService $acService)
    {
        $this->acService = $acService;
    }

    /**
     * Handle the event.
     *
     * @param  UpdatedContact  $event
     * @return void
     */
    public function handle(UpdatedContact $event)
    {
        $acResponse = $this->acService->updateContact(
            $event->contact,
            $event->listId
        );

        if ((int)$acResponse->success) {
            $event->contact->update([
                'active_campaign_id' => $acResponse->subscriber_id
            ]);
        } else {
            throw new UpdateContactException($acResponse->result_message);
        }
    }

   /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Registered $event, Exception $exception)
    {
        Log::error('Log Error Here');
    }
}
