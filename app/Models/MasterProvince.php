<?php

namespace App\Models;

use App\Traits\MyLaraCedTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterProvince extends BaseModel
{
    use MyLaraCedTrait, SoftDeletes;

    protected $casts = [
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'code',
        'name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function masterCities()
    {
        return $this->hasMany(MasterCity::class, 'province_code', 'code');
    }
}
