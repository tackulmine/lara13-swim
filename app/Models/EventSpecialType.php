<?php

namespace App\Models;

class EventSpecialType extends BaseModel
{
    protected $table = 'event_special_types';

    protected $casts = [
        'custom_fields' => 'json',
    ];

    protected $fillable = [
        'name',
        'display_name',
        'keyword',
        'custom_fields',
    ];
}
