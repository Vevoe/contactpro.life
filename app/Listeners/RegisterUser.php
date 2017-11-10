<?php

namespace App\Listeners;

use Log;
use Exception;
use Illuminate\Auth\Events\Registered;
use App\Services\ActiveCampaignService;
use App\Exceptions\CreateListException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterUser implements ShouldQueue
{
    use InteractsWithQueue;

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
     * @param  object  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $acResponse = $this->acService->createList($event->user);

        if ((int)$acResponse->success) {
            $event->user->update(['list_id' => $acResponse->id]);
        } else {
            throw new CreateListException($acResponse->result_message);
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
