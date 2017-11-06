<?php

namespace App\Services;

use App\Contact;

class ContactService
{
    /**
     * Create a new Contact
     * @param  App\User $user
     * @param  array $data
     * @return App\Contact
     */
    public function create($user, $data)
    {
        $contact = $user->contacts()->create($data);
        $contact->customFields()->createMany($this->buildCustomFieldArray($data['customFields']));

        return $contact;
    }

    /**
     * Update an existing Contact and it's CustomFields
     * @param  App\Contact $contact
     * @param  array $data
     * @return App\Contact
     */
    public function update($contact, $data)
    {
        // At max there's 5 of these. Seems easier to just delete
        // and resave rather than messing with ids and validating
        // all that.
        foreach ($contact->customFields as $customField) {
            $customField->delete();
        }

        $contact->customFields()->createMany($this->buildCustomFieldArray($data['customFields']));

        $contact->update($data);
        $contact->load('customFields'); // Ensures the model relationships have been updated

        return $contact;
    }

    /**
     * Creates or updates a Contact in Active Campaign db, synced by email
     * @param  $array $data
     * @return acResponse
     */
    public function sendActiveCampaignSync($data)
    {
        $ac = $this->initActiveCampaign();

        $contact = [
            'email'         => $data['email'],
            'first_name'    => $data['name'],
            'last_name'     => $data['surname'],
            'phone'         => $data['phone'],
        ];

        return $ac->api("contact/sync", $contact);
    }

    /**
     * Sends a request to Active Campaign to delete a contact
     * @param  int $ACId
     * @return acReponse
     */
    public function sendActiveCampaignDelete($ACId)
    {
        $ac = $this->initActiveCampaign();
        return $ac->api("contact/delete?id={$ACId}");
    }

    protected function initActiveCampaign()
    {
        return new \ActiveCampaign(
            env('ACTIVE_CAMPAIGN_URL'),
            env('ACTIVE_CAMPAIGN_KEY')
        );
    }

    /**
     * Return an array ready to be inserted into the createMany
     * method for a contacts customFields
     * @param  array $customFields
     * @return array
     */
    protected function buildCustomFieldArray($customFields)
    {
        $customFieldArray = [];
        foreach ($customFields as $customFieldValue) {
            if ($customFieldValue) { // Possible for customFieldValues to come in as NULL
                $customFieldArray[] = ['value' => $customFieldValue];
            }
        }

        return $customFieldArray;
    }
}
