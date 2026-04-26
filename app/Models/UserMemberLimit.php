<?php

namespace App\Models;

use App\Traits\MyLaraCedTrait;

class UserMemberLimit extends BaseModel
{
    use MyLaraCedTrait;

    protected $table = 'user_member_limits';

    protected $casts = [
        'user_id' => 'integer',
        'master_gaya_id' => 'integer',
        'periode_week' => 'integer',
        'periode_month' => 'integer',
        'periode_year' => 'integer',
        'point' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'user_id',
        'master_gaya_id',
        'periode_week',
        'periode_month',
        'periode_year',
        'point',
        'point_text',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function gaya()
    {
        return $this->belongsTo(MasterGaya::class, 'master_gaya_id');
    }

    public function getPeriodeToDateAttribute()
    {
        if (empty($this->periode_month) || empty($this->periode_year)) {
            return '-';
        }

        $monthFormat = str_pad($this->periode_month, 2, '0', STR_PAD_LEFT);
        $date = '01-'.$monthFormat.'-'.$this->periode_year;

        return Carbon::createFromFormat('d-m-Y', $date)->format('M-Y');
    }

    public function getPeriodeToTimestampAttribute()
    {
        if (empty($this->periode_month) || empty($this->periode_year)) {
            return '-';
        }

        $monthFormat = str_pad($this->periode_month, 2, '0', STR_PAD_LEFT);
        $date = '01-'.$monthFormat.'-'.$this->periode_year;

        // return Carbon::createFromFormat('d-m-Y', $date)->timestamp;
        return Carbon::createFromFormat('d-m-Y', $date)->toDateString();
    }
}
