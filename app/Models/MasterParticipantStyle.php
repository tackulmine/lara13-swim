<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MasterParticipantStyle extends Pivot
{
    protected $table = 'master_participant_style';

    public $incrementing = false;

    public $timestamps = false;

    public static $snakeAttributes = false;

    protected $casts = [
        'master_participant_id' => 'integer',
        'master_match_type_id' => 'integer',
        'is_no_point' => 'boolean',
        'point' => 'integer',
    ];

    protected $fillable = [
        'is_no_point',
        'point',
        'point_text',
    ];

    public function masterMatchType()
    {
        return $this->belongsTo(MasterMatchType::class);
    }

    public function masterParticipant()
    {
        return $this->belongsTo(MasterParticipant::class);
    }
}
