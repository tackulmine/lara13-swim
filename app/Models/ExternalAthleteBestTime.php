<?php

namespace App\Models;

use App\Traits\MyLaraCedTrait;

class ExternalAthleteBestTime extends BaseModel
{
    use MyLaraCedTrait;

    protected $casts = [
        'external_swimming_style_id' => 'integer',
        'external_swimming_athlete_id' => 'integer',
        'external_swimming_event_id' => 'integer',
        'year' => 'integer',
        'point' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'external_swimming_style_id',
        'external_swimming_athlete_id',
        'external_swimming_event_id',
        'year',
        'point',
        'point_text',
        'fp',
        'created_by',
        'updated_by',
    ];

    public function externalSwimmingStyle()
    {
        return $this->belongsTo(ExternalSwimmingStyle::class);
    }

    public function externalSwimmingAthlete()
    {
        return $this->belongsTo(ExternalSwimmingAthlete::class);
    }

    public function externalSwimmingEvent()
    {
        return $this->belongsTo(ExternalSwimmingEvent::class);
    }
}
