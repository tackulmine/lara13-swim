<?php

namespace App\Models;

use App\Traits\MyLaraCedTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterSchool extends BaseModel
{
    use MyLaraCedTrait, Sluggable, SoftDeletes;

    protected $table = 'master_schools';

    protected $casts = [
        'created_by' => 'integer',
        'updated_by' => 'integer',
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

    public function masterParticipants()
    {
        return $this->hasMany(MasterParticipant::class);
    }

    public function userEducations()
    {
        return $this->hasMany(UserEducation::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }
}
