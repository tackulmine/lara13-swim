<?php

namespace App\Models;

use App\Traits\MyLaraCedTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExternalSwimmingAthlete extends BaseModel
{
    use MyLaraCedTrait, Sluggable, SoftDeletes;

    protected $casts = [
        'dob' => 'date',
        'external_swimming_club_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'slug',
        'name',
        'nisnas',
        'pob',
        'dob',
        'gender',
        'city_code',
        'external_swimming_club_id',
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

    public function externalSwimmingClub()
    {
        return $this->belongsTo(ExternalSwimmingClub::class);
    }
}
