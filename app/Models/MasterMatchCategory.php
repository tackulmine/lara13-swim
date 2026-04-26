<?php

namespace App\Models;

use App\Traits\MyLaraCedTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterMatchCategory extends BaseModel
{
    use MyLaraCedTrait, Sluggable, SoftDeletes;

    protected $table = 'master_match_categories';

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

    public function eventStages()
    {
        return $this->hasMany(EventStage::class);
    }

    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_category', 'master_match_category_id', 'event_id')
            ->withPivot('ordering');
    }

    public function masterMatchTypes()
    {
        return $this->belongsToMany(MasterMatchType::class, 'event_category_type', 'master_match_category_id', 'master_match_type_id')
            ->withPivot('event_id');
    }
}
