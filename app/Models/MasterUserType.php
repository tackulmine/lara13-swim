<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;

class MasterUserType extends BaseModel
{
    use Sluggable;

    protected $table = 'master_user_types';

    public $timestamps = false;

    public const STAFF_ID = 1;

    public const MEMBER_ID = 2;

    protected $casts = [
    ];

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
