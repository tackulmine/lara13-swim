<?php

namespace App\Models;

use App\Traits\MyLaraCedTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterGaya extends BaseModel
{
    use MyLaraCedTrait, Sluggable, SoftDeletes;

    protected $table = 'master_gaya';

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

    public function userMemberLimits()
    {
        return $this->hasMany(UserMemberLimit::class);
    }

    public function userMemberGayaLimits()
    {
        return $this->hasMany(UserMemberGayaLimit::class);
    }
}
