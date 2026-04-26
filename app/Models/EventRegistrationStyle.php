<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EventRegistrationStyle extends Pivot
{
    protected $table = 'event_registration_style';

    public $incrementing = false;

    public $timestamps = false;

    public static $snakeAttributes = false;

    protected $casts = [
        'event_registration_id' => 'integer',
        'master_match_type_id' => 'integer',
        'is_no_point' => 'boolean',
        'point' => 'integer',
    ];

    protected $fillable = [
        'is_no_point',
        'point',
        'point_text',
    ];

    public function eventRegistration()
    {
        return $this->belongsTo(EventRegistration::class);
    }

    public function masterMatchType()
    {
        return $this->belongsTo(MasterMatchType::class);
    }
}
