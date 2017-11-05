<?php

namespace App\Http\Resources;

use App\Http\Resources\CustomFieldCollection;
use Illuminate\Http\Resources\Json\Resource;

class Contact extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'surname'       => $this->surname,
            'email'         => $this->email,
            'phone'         => $this->phone,
            'customFields'  => new CustomFieldCollection($this->customFields)
        ];
    }
}
