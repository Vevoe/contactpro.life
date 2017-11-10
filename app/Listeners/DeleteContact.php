<?php

namespace App\Listeners;

use Log;
use Exception;
use App\Events\DeletedContact;
use App\Services\ActiveCampaignService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteContact implements ShouldQueue
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
     * @param  DeletedContact  $event
     * @return void
     */
    public function handle(DeletedContact $event)
    {
        $acResponse = $this->acService->deleteContact($event->acContactId);

        if (!(int)$acResponse->success) {
            throw new DeleteContactException($acResponse->result_message);
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
