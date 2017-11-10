<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialProvider extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'provider', 'provider_id', 'list_id'
    ];
    /**
     * Relationships
     * 
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
