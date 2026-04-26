<?php

namespace App\Models;

use App\Traits\MyLaraCedTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class MasterParticipant extends BaseModel
{
    use HasFactory, HasRelationships, MyLaraCedTrait, Sluggable, SoftDeletes;

    protected $table = 'master_participants';

    protected $casts = [
        'master_school_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'birth_date' => 'date',
        'birth_year' => 'integer',
    ];

    protected $fillable = [
        'master_school_id',
        'slug',
        'name',
        'gender',
        'address',
        'location',
        'birth_date',
        'birth_year',
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

    public function masterSchool()
    {
        return $this->belongsTo(MasterSchool::class);
    }

    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function eventSessionParticipants()
    {
        return $this->hasMany(EventSessionParticipant::class);
    }

    // MasterParticipant → many to many → EventSessionParticipant
    public function eventSessionParticipantDetails()
    {
        return $this->belongsToMany(
            EventSessionParticipant::class,
            'event_session_participant_detail', // Nama tabel pivot
            'master_participant_id',           // FK di pivot untuk model ini
            'event_session_participant_id'     // FK di pivot untuk model tujuan
        )->withPivot('ordering');             // Mengambil kolom tambahan
    }

    public function styles()
    {
        return $this->belongsToMany(MasterMatchType::class, 'master_participant_style', 'master_participant_id', 'master_match_type_id')
            ->withPivot('is_no_point', 'point', 'point_text');
    }

    // MasterParticipant → has many → EventSessionParticipant → belongs to → EventSession → belongs to → EventStage
    public function eventStages()
    {
        return $this->hasManyDeep(
            EventStage::class,
            [EventSessionParticipant::class, EventSession::class],
            ['master_participant_id', 'id', 'id'], // Foreign keys
            ['id', 'event_session_id', 'event_stage_id'] // Local keys
        );
    }

    public function getNameDetailAttribute()
    {
        return "{$this->name} ({$this->gender_initial}/{$this->birth_year_text})";
    }

    public function getNameDetailWithSchoolAttribute()
    {
        return "{$this->name} ({$this->gender_initial}/{$this->birth_year_text})".' ('.($this->masterSchool->name ?? '').')';
    }

    public function getGenderTextAttribute()
    {
        return empty($this->gender) ? '-' : ($this->gender == 'mix' ? 'Mix' : ($this->gender == 'female' ? 'Perempuan' : 'Laki-laki'));
    }

    public function getGenderInitialAttribute()
    {
        return empty($this->gender) ? '-' : ($this->gender == 'mix' ? 'M' : ($this->gender == 'female' ? 'P' : 'L'));
    }

    public function getBirthYearTextAttribute()
    {
        return $this->birth_year ?? '-';
    }
}
