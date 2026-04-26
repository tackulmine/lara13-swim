<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EventCategoryType extends Pivot
{
    protected $table = 'event_category_type';

    public $incrementing = false;

    public $timestamps = false;

    public static $snakeAttributes = false;

    protected $casts = [
        'event_id' => 'integer',
        'master_match_category_id' => 'integer',
        'master_match_type_id' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function masterMatchCategory()
    {
        return $this->belongsTo(MasterMatchCategory::class);
    }

    public function masterMatchType()
    {
        return $this->belongsTo(MasterMatchType::class);
    }
}
