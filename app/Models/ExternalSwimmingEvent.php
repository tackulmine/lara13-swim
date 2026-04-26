<?php

namespace App\Models;

use App\Traits\MyLaraCedTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExternalSwimmingEvent extends BaseModel
{
    use MyLaraCedTrait, Sluggable, SoftDeletes;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'year' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'slug',
        'name',
        'code',
        'start_date',
        'end_date',
        'year',
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
}
