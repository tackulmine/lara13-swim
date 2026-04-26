<?php

namespace App\Models;

class UserProfile extends BaseModel
{
    protected $primaryKey = 'user_id';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'user_id' => 'integer',
        'birth_date' => 'date',
    ];

    protected $fillable = [
        'user_id',
        'nik',
        'birth_place',
        'birth_date',
        'gender',
        'relegion',
        'last_education',
        'height',
        'weight',
        'address',
        'location',
        'marital_status',
        'nationality',
        'profession',
        'bio',
        'photo',
        'birth_certificate',
        'family_card',
        'kta_card',
        'signature',
        'phone_number',
        'second_phone_number',
        'whatsapp_number',
        'telegram_number',
        'facebook_id',
        'twitter_id',
        'instagram_id',
        'tiktok_id',
        'ayah',
        'ibu',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function setBirthPlaceAttribute($value)
    {
        $this->attributes['birth_place'] = strtoupper($value);
    }

    public function getGenderNameAttribute()
    {
        $genderData = getGenders();

        return $this->gender ? $genderData[$this->gender] : '-';
    }

    public function getBirthDateFormatAttribute()
    {
        return ! empty($this->birth_date) ? $this->birth_date->format('d/M/Y') : '-';
    }

    public function getAgeAttribute()
    {
        return ! empty($this->birth_date) ? $this->birth_date->diffInYears() : '-';
    }
}
