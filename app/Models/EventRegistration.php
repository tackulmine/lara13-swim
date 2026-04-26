<?php

namespace App\Models;

use App\Traits\MyLaraCedTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventRegistration extends BaseModel
{
    use MyLaraCedTrait, SoftDeletes;

    protected $table = 'event_registrations';

    protected $casts = [
        'event_id' => 'integer',
        'master_participant_id' => 'integer',
        'master_match_category_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'event_id',
        'master_participant_id',
        'master_match_category_id',
        'school_certificate',
        'birth_certificate',
        'photo',
        'coach_name',
        'coach_phone',
        'created_by',
        'updated_by',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // public function masterMatchType()
    // {
    //     return $this->belongsTo(MasterMatchType::class);
    // }

    public function types()
    {
        return $this->belongsToMany(MasterMatchType::class, 'event_registration_style', 'event_registration_id', 'master_match_type_id')
            ->withPivot('is_no_point', 'point', 'point_text');
    }

    public function masterMatchCategory()
    {
        return $this->belongsTo(MasterMatchCategory::class);
    }

    public function masterParticipant()
    {
        return $this->belongsTo(MasterParticipant::class);
    }

    public function eventRegistrationNumbers()
    {
        return $this->hasMany(EventRegistrationNumber::class, 'event_registration_id');
    }

    public function getSchoolCertificateUrlAttribute()
    {
        $file = 'event-registrations/'.$this->school_certificate;
        if (empty($file)) {
            // return '//picsum.photos/800/600?random='.$this->id;
            return '//placehold.co/800x600?text=No+School\nCert';
        }
        $storage = Storage::disk('shared');

        $filePath = config('filesystems.disks.shared.root').'/'.$file;

        return $storage->url($file).(is_file($filePath) ? '?'.filemtime($filePath) : '');
    }

    public function getBirthCertificateUrlAttribute()
    {
        $file = $this->birth_certificate;
        if (empty($file)) {
            // return '//picsum.photos/600/800?random='.$this->id;
            return '//placehold.co/600x800?text=No+Birth\nCert';
        }
        $storage = Storage::disk('shared');

        $filePath = config('filesystems.disks.shared.root').'/'.$file;

        return $storage->url($file).(is_file($filePath) ? '?'.filemtime($filePath) : '');
    }

    public function getPhotoUrlAttribute()
    {
        $photo = $this->photo;
        if (empty($photo)) {
            // return '//picsum.photos/300/400?random='.$this->id;
            return '//placehold.co/300x400?text=No+Photo';
        }
        $storage = Storage::disk('shared');

        $filePath = config('filesystems.disks.shared.root').'/'.$photo;

        return $storage->url($photo).(is_file($filePath) ? '?'.filemtime($filePath) : '');
    }

    public function getSmallPhotoUrlAttribute()
    {
        $photo = $this->photo;
        if (empty($photo)) {
            // return '//picsum.photos/300/400?random='.$this->id;
            return '//placehold.co/150x200?text=No+Photo';
        }
        $photoInfo = pathinfo($photo);
        $photoName = $photoInfo['filename'];
        $photoExt = $photoInfo['extension'];

        $smallPhoto = 'event-registrations/'.$photoName.'_200.'.$photoExt;

        $storage = Storage::disk('shared');

        $filePath = config('filesystems.disks.shared.root').'/'.$smallPhoto;

        return $storage->url($smallPhoto).(is_file($filePath) ? '?'.filemtime($filePath) : '');
    }

    public function getPreviewPhotoAttribute()
    {
        return '<img src="'.$this->small_photo_url.'" alt="'.optional($this->masterParticipant)->name.'" height="150">';
    }

    public function getPreviewTinyPhotoAttribute()
    {
        return '<img src="'.$this->small_photo_url.'" alt="'.optional($this->masterParticipant)->name.'" height="50">';
    }

    public function getPreviewFancyPhotoAttribute()
    {
        return '<a href="'.$this->photo_url.'" class="d-inline-block" data-fancybox data-caption="'.optional($this->masterParticipant)->name.'" data-toggle="tooltip" title="click to zoom">'.$this->preview_photo.'</a>';
    }

    public function getPreviewTinyFancyPhotoAttribute()
    {
        return '<a href="'.$this->photo_url.'" class="d-inline-block" data-fancybox data-caption="'.optional($this->masterParticipant)->name.'" data-toggle="tooltip" title="click to zoom">'.$this->preview_tiny_photo.'</a>';
    }

    public function getSmallBirthCertificateUrlAttribute()
    {
        $photo = $this->birth_certificate;
        if (empty($photo)) {
            // return '//picsum.photos/600/800?random='.$this->id;
            return '//placehold.co/150x200?text=No+Birth\nCert';
        }
        $photoInfo = pathinfo($photo);
        $photoName = $photoInfo['filename'];
        $photoExt = $photoInfo['extension'];

        $smallPhoto = 'event-registrations/'.$photoName.'_200.'.$photoExt;

        $storage = Storage::disk('shared');

        $filePath = config('filesystems.disks.shared.root').'/'.$smallPhoto;

        return $storage->url($smallPhoto).(is_file($filePath) ? '?'.filemtime($filePath) : '');
    }

    public function getPreviewBirthCertificateAttribute()
    {
        return '<img src="'.$this->small_birth_certificate_url.'" alt="'.optional($this->masterParticipant)->name.'" height="150">';
    }

    public function getPreviewTinyBirthCertificateAttribute()
    {
        return '<img src="'.$this->small_birth_certificate_url.'" alt="'.optional($this->masterParticipant)->name.'" height="50">';
    }

    public function getPreviewFancyBirthCertificateAttribute()
    {
        return '<a href="'.$this->birth_certificate_url.'" class="d-inline-block" data-fancybox data-caption="'.optional($this->masterParticipant)->name.'" data-toggle="tooltip" title="click to zoom">'.$this->preview_birth_certificate.'</a>';
    }

    public function getPreviewTinyFancyBirthCertificateAttribute()
    {
        return '<a href="'.$this->birth_certificate_url.'" class="d-inline-block" data-fancybox data-caption="'.optional($this->masterParticipant)->name.'" data-toggle="tooltip" title="click to zoom">'.$this->preview_tiny_birth_certificate.'</a>';
    }

    public function getTotalTagihan($type = 'normal')
    {
        // $normalPrice = 50000; //SAC
        $normalPrice = $this->event->reg_style_per_price ?? 0; // SAC 50000

        // $maxTotalPrice = 275000;
        $maxTotalPrice = $this->event->reg_style_max_price ?? 0; // SAC 300000

        $relayPrice = $this->event->reg_relay_per_price ?? 0; // SAC 150000

        $totalTagihan = 0;

        if ($this->types) {
            $typesCount = $this->types->count();

            // ### NORMAL but RANGE PRICE
            if ($typesCount > 0) {
                $totalTagihan = $typesCount * $normalPrice;

                $maxStyle = $this->event->reg_style_max_price_count ?? 0; // SAC 3
                if ($maxTotalPrice > 0 && $maxStyle > 0 && $typesCount >= $maxStyle) {
                    $totalTagihan = $maxTotalPrice;
                }
            }

            if ($type != 'normal') {
                // ### WITH RELAY and FIXED PRICE
                $this->types->each(function ($type) use ($normalPrice, $relayPrice) {
                    $type->tagihan = Str::contains($this->masterMatchCategory->name, 'RELAY')
                        ? $relayPrice
                        : $normalPrice;
                });
                $totalTagihan = $this->types->sum('tagihan');
            }
        }

        return $totalTagihan;
    }
}
