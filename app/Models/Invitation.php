<?php

namespace App\Models;

class Invitation extends BaseModel
{
    protected $fillable = [
        'email',
        'invitation_token',
        'registered_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
    ];

    public function generateInvitationToken()
    {
        $this->invitation_token = substr(md5(rand(0, 9).$this->email.time()), 0, 32);
    }

    public function getLink()
    {
        return urldecode(route('register').'?invitation_token='.$this->invitation_token);
    }
}
