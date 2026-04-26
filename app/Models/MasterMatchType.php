<?php

namespace App\Models;

use App\Traits\MyLaraCedTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterMatchType extends BaseModel
{
    use MyLaraCedTrait, Sluggable, SoftDeletes;

    protected $table = 'master_match_types';

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

    public function eventRegistrations()
    {
        return $this->belongsToMany(EventRegistration::class, 'event_registration_style', 'master_match_type_id', 'event_registration_id')
            ->withPivot('is_no_point', 'point', 'point_text');
    }

    public function masterParticipants()
    {
        return $this->belongsToMany(MasterParticipant::class, 'master_participant_style', 'master_match_type_id', 'master_participant_id')
            ->withPivot('is_no_point', 'point', 'point_text');
    }

    public function eventCategories()
    {
        return $this->belongsToMany(MasterMatchCategory::class, 'event_category_type')
            ->using(EventCategoryType::class)
            ->withPivot(['event_id']);
    }

    public function eventRegistrationStyles()
    {
        return $this->hasMany(EventRegistrationStyle::class);
    }

    public function eventStages()
    {
        return $this->hasMany(EventStage::class);
    }

    public function eventTypes()
    {
        return $this->hasMany(EventType::class);
    }

    public function masterParticipantStyles()
    {
        return $this->hasMany(MasterParticipantStyle::class);
    }
}
