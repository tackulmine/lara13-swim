<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;

class MasterMemberType extends BaseModel
{
    use Sluggable;

    protected $table = 'master_member_types';

    public $timestamps = false;

    public const ATHLETE_ID = 1;

    public const NON_ATHLETE_ID = 2;

    protected $casts = [];

    protected $fillable = [
        'slug',
        'name',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }
}
