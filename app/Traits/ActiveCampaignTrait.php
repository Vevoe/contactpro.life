<?php

namespace App\Traits;

use ActiveCampaign;

trait ActiveCampaignTrait
{
    /**
     * Create a new instance of the Active Campaign
     * library.
     * 
     * @return [type] [description]
     */
    protected function initActiveCampaign()
    {
        return new ActiveCampaign(
            env('ACTIVE_CAMPAIGN_URL'),
            env('ACTIVE_CAMPAIGN_URL')
        );
    }


    public function createACContact($data)
    {
        $ac = $this->initActiveCampaign();

        $contact = [
            'email'         => $data['email'],
            'first_name'    => $data['name'],
            'last_name'     => $data['surname'],
            'phone'         => $data['phone'],å
        ];

    }


    /**
     * Create a new List in Active Campaign.
     * 
     * @return Response
     */
    public function createACUserList()
    {
        $ac = $this->initActiveCampaign($listName);

        $params = [
            'name'  => $listName
        ];
    }   
}
