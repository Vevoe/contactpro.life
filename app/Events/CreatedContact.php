<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreatedContact
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The newly created Contact
     * 
     * @var \App\Contact
     */
    public $contact;

    /**
     * The listId to assign the Contact to
     * @var integer
     */
    public $listId;
    
    /**
     * Create a new event instance.
     *
     * @param  \App\Contact $contact
     * @param  integer $listId
     * @return void
     */
    public function __construct($contact, $listId)
    {
        $this->contact = $contact;
        $this->listId  = $listId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
