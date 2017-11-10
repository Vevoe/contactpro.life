<?php

namespace App\Services;

use Log;
use ActiveCampaign;

class ActiveCampaignService
{
    /**
     * Create a new instance of the Active Campaign
     * library.
     * 
     * @return \ActiveCampaign
     */
    protected function initActiveCampaign()
    {
        return new ActiveCampaign(
            env('ACTIVE_CAMPAIGN_URL'),
            env('ACTIVE_CAMPAIGN_KEY')
        );
    }

    /**
     * Send an Event
     *
     * @param  string $eventType
     * @param  string $eventData
     * @param  string/null $email
     */
    public function sendEvent($eventType, $eventData, $email = null)
    {
        $ac = $this->initActiveCampaign();

        $ac->track_actid = env('ACTIVE_CAMPAIGN_ACTID');
        $ac->track_key = env('ACTIVE_CAMPAIGN_TRACKING_KEY');

        // Optionally add an email
        if ($email) {
            $ac->track_email = $email;
        }

        $postData = [
            'event'     => $eventType,
            'eventdata' => $eventData,
        ];

        return $ac->api("tracking/log", $postData);
    }

    /**
     * Create a new List
     * 
     * @param  \App\User $user
     * @return object
     */
    public function createList($user)
    {
        $ac = $this->initActiveCampaign();
        
        // Just fake it for now.
        $addList = [
            'name'              => 'user_' . $user->id,
            'sender_name'       => 'Acme Co',
            'sender_addr1'      => '123 Tune Dr.',
            'sender_zip'        => '12345',
            'sender_city'       => 'Looney',
            'sender_country'    => 'US',
            'sender_url'        => 'www.acme.co',
            'sender_reminder'   => 'You subscribed on our web site',
        ];

        return $ac->api('list/add', $addList);
    }

    /**
     * Create a new Contact
     * @param  \App\Contact $contact
     * @param  int $listId
     * @return object
     */
    public function createContact($contact, $listId)
    {
        $ac = $this->initActiveCampaign();

        $addContact = [
            'email'         => $contact->email,
            'first_name'    => $contact->name,
            'last_name'     => $contact->surname,
            'phone'         => $contact->phone,
            'p[{$listId}]'  => $listId,
        ];

        // Build a custom field for each Custom Field
        foreach ($contact->customFields as $key => $value) {
            $index = ++$key;
            $addContact["field[%CUSTOM_{$index}%,0]"] = $value->value;
        }

        return $ac->api('contact/add', $addContact);
    }

    /**
     * Update a Contact
     * @param  \App\Contact $contact
     * @param  int $listId
     * @return object
     */
    public function updateContact($contact, $listId)
    {
        $ac = $this->initActiveCampaign();

        $addContact = [
            'id'            => $contact->active_campaign_id,
            'email'         => $contact->email,
            'first_name'    => $contact->name,
            'last_name'     => $contact->surname,
            'phone'         => $contact->phone,
            'p[{$listId}]'  => $listId,
        ];

        // Build an array of 5 custom fields setting the value to an empty string.
        // Then fill them with whatever custom values the contact has. This
        // ensures that the AC database is in sync wiht ours.
        $i = 1;
        while ($i <= 5) {
            $addContact["field[%CUSTOM_{$i}%,0]"] = '';
            $i++;
        }

        foreach ($contact->customFields as $key => $value) {
            $index = ++$key;
            $addContact["field[%CUSTOM_{$index}%,0]"] = $value->value;
        }

        return $ac->api('contact/edit', $addContact);
    }

    /**
     * Delete a Contact
     * 
     * @param  int $acContactId
     * @return object
     */
    public function deleteContact($acContactId)
    {
        $ac = $this->initActiveCampaign();
        return $ac->api("contact/delete?id={$acContactId}");
    }
}
