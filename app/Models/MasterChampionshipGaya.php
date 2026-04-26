<?php

namespace App\Models;

use App\Traits\MyLaraCedTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterChampionshipGaya extends BaseModel
{
    use MyLaraCedTrait, Sluggable, SoftDeletes;

    protected $table = 'master_championship_gaya';

    protected $casts = [
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'slug',
        'name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function userChampionships()
    {
        return $this->hasMany(UserChampionship::class);
    }
}
