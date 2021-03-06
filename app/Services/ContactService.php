<?php

namespace App\Services;

use App\Contact;
use ActiveCampaign;

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
     * 
     * @param  App\Contact $contact
     * @param  array $data
     * @return App\Contact
     */
    public function update($contact, $data)
    {
        // At max there's 5 of these. Unless there's a reason to
        // in the future, I think this is just fine for now.
        // Just delete and re-save.
        foreach ($contact->customFields as $customField) {
            $customField->delete();
        }

        $contact->customFields()->createMany($this->buildCustomFieldArray($data['customFields']));

        $contact->update($data);
        $contact->load('customFields'); // Ensures the model relationships have been updated

        return $contact;
    }

    /**
     * Return an array ready to be inserted into the createMany
     * method for a contacts customFields
     * 
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
