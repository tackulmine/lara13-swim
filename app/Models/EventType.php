<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EventType extends Pivot
{
    protected $table = 'event_type';

    protected $primaryKey = ['event_id', 'master_match_type_id'];

    public $incrementing = false;

    public $timestamps = false;

    public static $snakeAttributes = false;

    protected $casts = [
        'event_id' => 'integer',
        'master_match_type_id' => 'integer',
        'ordering' => 'integer',
    ];

    protected $fillable = [
        'ordering',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function masterMatchType()
    {
        return $this->belongsTo(MasterMatchType::class);
    }
}
