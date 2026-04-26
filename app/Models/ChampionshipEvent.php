<?php

namespace App\Models;

use App\Traits\MyLaraCedTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChampionshipEvent extends BaseModel
{
    use MyLaraCedTrait, SoftDeletes;

    protected $casts = [
        'master_championship_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    protected $fillable = [
        'master_championship_id',
        'address',
        'location',
        'description',
        'start_date',
        'end_date',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function masterChampionship()
    {
        return $this->belongsTo(MasterChampionship::class);
    }

    public function userChampionships()
    {
        return $this->hasMany(UserChampionship::class);
    }
}
