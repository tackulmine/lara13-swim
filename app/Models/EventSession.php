<?php

namespace App\Models;

use App\Traits\MyLaraCedTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventSession extends BaseModel
{
    use HasFactory, MyLaraCedTrait;

    protected $table = 'event_sessions';

    protected $touches = ['eventStage'];

    protected $casts = [
        'event_stage_id' => 'integer',
        'session' => 'integer',
        'completed' => 'boolean',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'event_stage_id',
        'session',
        'completed',
        'created_by',
        'updated_by',
    ];

    public function eventStage()
    {
        return $this->belongsTo(EventStage::class);
    }

    public function eventSessionParticipants()
    {
        return $this->hasMany(EventSessionParticipant::class);
    }
}
