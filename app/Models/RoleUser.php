<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleUser extends Pivot
{
    protected $table = 'role_user';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'role_id' => 'integer',
        'user_id' => 'integer',
    ];

    protected $fillable = [
        'role_id',
        'user_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
