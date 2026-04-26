<?php

namespace App\Models;

class UserStaff extends BaseModel
{
    protected $table = 'user_staff';

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'user_id' => 'integer',
        'master_staff_type_id' => 'integer',
    ];

    protected $fillable = [
        'user_id',
        'master_staff_type_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function type()
    {
        return $this->belongsTo(MasterStaffType::class, 'master_staff_type_id');
    }
}
