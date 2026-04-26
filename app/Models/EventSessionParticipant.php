<?php

namespace App\Models;

use App\Traits\MyLaraCedTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventSessionParticipant extends BaseModel
{
    use HasFactory, MyLaraCedTrait;

    public const DIS_LEVEL_SP = 1;

    public const DIS_LEVEL_DQ = 2;

    public const DIS_LEVEL_NS = 3;

    protected $table = 'event_session_participants';

    protected $touches = ['eventSession'];

    protected $casts = [
        'event_session_id' => 'integer',
        'master_participant_id' => 'integer',
        'track' => 'integer',
        'point' => 'integer',
        'point_decimal' => 'float',
        'disqualification' => 'boolean',
        'dis_level' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'event_session_id',
        'master_participant_id',
        'track',
        'point',
        'point_text',
        'point_decimal',
        'point_text_decimal',
        'disqualification',
        'dis_level',
        'notes',
        'created_by',
        'updated_by',
    ];

    public function eventSession()
    {
        return $this->belongsTo(EventSession::class);
    }

    public function masterParticipant()
    {
        return $this->belongsTo(MasterParticipant::class);
    }

    public function participantDetails()
    {
        return $this->belongsToMany(
            MasterParticipant::class,
            'event_session_participant_detail',
            'event_session_participant_id', // FK ke model INI
            'master_participant_id'         // FK ke model TERKAIT
        )
            ->withPivot('ordering')
            ->orderBy('event_session_participant_detail.ordering');
    }

    public function getDisLevelTextAttribute()
    {
        switch ($this->dis_level) {
            case 1:
                $disLevelText = 'SP';
                break;
            case 2:
                $disLevelText = 'DQ';
                break;
            case 3:
                $disLevelText = 'NS';
                break;

            default:
                $disLevelText = '';
                break;
        }

        return $disLevelText;
    }

    public function getDisLevelTextClassAttribute()
    {
        switch ($this->dis_level) {
            case 1:
                $disLevelTextClass = 'text-primary';
                break;
            case 2:
                $disLevelTextClass = 'text-danger';
                break;
            case 3:
                $disLevelTextClass = 'text-warning';
                break;

            default:
                $disLevelTextClass = '';
                break;
        }

        return $disLevelTextClass;
    }
}
