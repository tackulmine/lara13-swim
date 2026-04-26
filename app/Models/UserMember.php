<?php

namespace App\Models;

class UserMember extends BaseModel
{
    protected $primaryKey = 'user_id';

    protected $table = 'user_member';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'user_id' => 'integer',
        'master_member_type_id' => 'integer',
        'master_member_class_id' => 'integer',
    ];

    protected $fillable = [
        'user_id',
        'master_member_type_id',
        'master_member_class_id',
        'nis',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function type()
    {
        return $this->belongsTo(MasterMemberType::class, 'master_member_type_id');
    }

    public function class()
    {
        return $this->belongsTo(MasterMemberClass::class, 'master_member_class_id');
    }
}
