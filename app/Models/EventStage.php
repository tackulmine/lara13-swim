<?php

namespace App\Models;

use App\Traits\MyLaraCedTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class EventStage extends BaseModel
{
    use HasFactory, MyLaraCedTrait;

    protected $table = 'event_stages';

    protected $touches = ['event'];

    protected $casts = [
        'event_id' => 'integer',
        'master_match_type_id' => 'integer',
        'master_match_category_id' => 'integer',
        'number' => 'integer',
        'order_number' => 'integer',
        'completed' => 'boolean',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'event_id',
        'master_match_type_id',
        'master_match_category_id',
        'number',
        'order_number',
        'completed',
        'created_by',
        'updated_by',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function masterMatchType()
    {
        return $this->belongsTo(MasterMatchType::class);
    }

    public function masterMatchCategory()
    {
        return $this->belongsTo(MasterMatchCategory::class);
    }

    public function eventSessions()
    {
        return $this->hasMany(EventSession::class);
    }

    public function eventSessionParticipants()
    {
        return $this->hasManyThrough(EventSessionParticipant::class, EventSession::class);
    }

    public function getNumberFormatAttribute()
    {
        return str_pad($this->number, 3, 0, STR_PAD_LEFT);
    }

    public function getMatchTypeCategoryAttribute()
    {
        // {{ strtoupper($this->masterMatchType->name) }} {{ $this->masterMatchCategory->name }}

        $matchType = strtolower($this->masterMatchType->name);
        $genderName = '';

        if (Str::contains($matchType, ' putra')) {
            $matchType = Str::replaceLast(' putra', '', $matchType);
            $genderName = 'putra';
        } elseif (Str::contains($matchType, ' pa')) {
            $matchType = Str::replaceLast(' pa', '', $matchType);
            $genderName = 'putra';
        }
        if (Str::contains($matchType, ' putri')) {
            $matchType = Str::replaceLast(' putri', '', $matchType);
            $genderName = 'putri';
        } elseif (Str::contains($matchType, ' pi')) {
            $matchType = Str::replaceLast(' pi', '', $matchType);
            $genderName = 'putri';
        }
        if (Str::contains($matchType, ' mix')) {
            $matchType = Str::replaceLast(' mix', '', $matchType);
            $genderName = 'mix';
        }

        return strtoupper(trim($matchType).' '.$this->masterMatchCategory->name.' '.$genderName);
    }
}
