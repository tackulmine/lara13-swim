<?php

namespace App\Models;

use App\Traits\MyLaraCedTrait;

class UserEducation extends BaseModel
{
    use MyLaraCedTrait;

    protected $table = 'user_educations';

    public $incrementing = false;

    protected $casts = [
        'user_id' => 'integer',
        'master_school_id' => 'integer',
        'periode_start_year' => 'integer',
        'periode_end_year' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'user_id',
        'master_school_id',
        'periode_start_year',
        'periode_end_year',
        'major',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function school()
    {
        return $this->belongsTo(MasterSchool::class, 'master_school_id');
    }
}
