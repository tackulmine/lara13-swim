<?php

namespace App\Models;

use App\Traits\MyLaraCedTrait;

class UserChampionship extends BaseModel
{
    use MyLaraCedTrait;

    public $incrementing = false;

    protected $casts = [
        'user_id' => 'integer',
        'championship_event_id ' => 'integer',
        'master_championship_gaya_id  ' => 'integer',
        'point' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'user_id',
        'championship_event_id',
        'master_championship_gaya_id',
        'point',
        'point_text',
        'rank',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function championshipEvent()
    {
        return $this->belongsTo(ChampionshipEvent::class, 'championship_event_id');
    }

    public function masterChampionshipGaya()
    {
        return $this->belongsTo(MasterChampionshipGaya::class, 'master_championship_gaya_id');
    }
}
