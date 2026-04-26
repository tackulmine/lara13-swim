<?php

namespace App\Models;

use App\Traits\MyLaraCedTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterCity extends BaseModel
{
    use MyLaraCedTrait, SoftDeletes;

    protected $casts = [
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'code',
        'province_code',
        'name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function masterProvince()
    {
        return $this->belongsTo(MasterProvince::class, 'province_code', 'code');
    }
}
