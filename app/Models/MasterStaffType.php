<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;

class MasterStaffType extends BaseModel
{
    use Sluggable;

    protected $table = 'master_staff_types';

    public $timestamps = false;

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
