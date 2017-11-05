<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contact_id', 'value'
    ];

    /**
     * Relationships
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
