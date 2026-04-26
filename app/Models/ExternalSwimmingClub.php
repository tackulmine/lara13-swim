<?php

namespace App\Models;

use App\Traits\MyLaraCedTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExternalSwimmingClub extends BaseModel
{
    use MyLaraCedTrait, Sluggable, SoftDeletes;

    protected $casts = [
        'dob' => 'date',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'slug',
        'name',
        'dob',
        'city_code',
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

    public function masterCity()
    {
        return $this->belongsTo(MasterCity::class, 'city_code', 'code');
    }

    public function externalSwimmingAthletes()
    {
        return $this->hasMany(ExternalSwimmingAthlete::class);
    }
}
