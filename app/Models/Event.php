<?php

namespace App\Models;

use App\Traits\MyLaraCedTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Event extends BaseModel
{
    use HasRelationships;
    use MyLaraCedTrait, Sluggable, SoftDeletes;

    protected $table = 'events';

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_external' => 'boolean',
        'is_reg' => 'boolean',
        'reg_end_date' => 'date',
        'reg_quota' => 'integer',
        'reg_style_min' => 'integer',
        'reg_cat_style_min' => 'array',
        'reg_style_per_price' => 'integer',
        'reg_style_max_price_count' => 'integer',
        'reg_style_max_price' => 'integer',
        'reg_relay_per_price' => 'integer',
        'start_track_number' => 'integer',
        'total_track' => 'integer',
        'is_has_mix_gender' => 'boolean',
        'is_has_copyright' => 'boolean',
        'completed' => 'boolean',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'reg_end_date',
    ];

    protected $fillable = [
        'slug',
        'name',
        'address',
        'location',
        'description',
        'photo',
        'photo_right',
        'start_date',
        'end_date',
        'is_external',
        'is_reg',
        'reg_end_date',
        'reg_quota',
        'reg_style_min',
        'reg_cat_style_min',
        'reg_style_per_price',
        'reg_style_max_price_count',
        'reg_style_max_price',
        'reg_relay_per_price',
        'start_track_number',
        'total_track',
        'is_has_mix_gender',
        'is_has_copyright',
        'copyright_text',
        'completed',
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

    public function setRegCatStyleMinAttribute($value)
    {
        $this->attributes['reg_cat_style_min'] = json_encode($value);
    }

    public function getPhotoUrlAttribute()
    {
        $photo = $this->photo;
        $storage = Storage::disk('shared');

        if (empty($photo) || $storage->missing($photo)) {
            return;
        }

        $filePath = config('filesystems.disks.shared.root').'/'.$photo;

        return asset($storage->url($photo).(is_file($filePath) ? '?'.filemtime($filePath) : ''));
    }

    public function getPreviewPhotoAttribute()
    {
        if (empty($this->photo_url)) {
            return;
        }

        return '<img src="'.$this->photo_url.'" alt="'.$this->name.'" height="100">';
    }

    public function getPreviewTinyPhotoAttribute()
    {
        if (empty($this->photo_url)) {
            return;
        }

        return '<img src="'.$this->photo_url.'" alt="'.$this->name.'" height="50">';
    }

    public function getPhotoRightUrlAttribute()
    {
        $photoRight = $this->photo_right;
        $storage = Storage::disk('shared');

        if (empty($photoRight) || $storage->missing($photoRight)) {
            return;
        }

        $filePath = config('filesystems.disks.shared.root').'/'.$photoRight;

        return asset($storage->url($photoRight).(is_file($filePath) ? '?'.filemtime($filePath) : ''));
    }

    public function getPreviewPhotoRightAttribute()
    {
        if (empty($this->photo_right_url)) {
            return $this->preview_photo;
        }

        return '<img src="'.$this->photo_right_url.'" alt="'.$this->name.'" height="100">';
    }

    public function getPreviewTinyPhotoRightAttribute()
    {
        if (empty($this->photo_right_url)) {
            return $this->preview_tiny_photo;
        }

        return '<img src="'.$this->photo_right_url.'" alt="'.$this->name.'" height="50">';
    }

    public function eventStages()
    {
        return $this->hasMany(EventStage::class);
    }

    public function eventSessions()
    {
        return $this->hasManyThrough(EventSession::class, EventStage::class);
    }

    // function eventParticipants()
    // {
    //     return $this->hasManyThrough(EventParticipant::class, EventSession::class, EventStage::class);
    // }

    public function eventParticipants()
    {
        return $this->hasManyDeepFromRelations($this->eventSessions(), (new EventSession)->eventSessionParticipants());
    }

    public function categories()
    {
        return $this->belongsToMany(MasterMatchCategory::class, 'event_category', 'event_id', 'master_match_category_id')
            ->withPivot('ordering');
    }

    public function types()
    {
        return $this->belongsToMany(MasterMatchType::class, 'event_type', 'event_id', 'master_match_type_id')
            ->withPivot('ordering');
    }

    public function categoryTypes()
    {
        return $this->belongsToMany(MasterMatchType::class, 'event_category_type', 'event_id', 'master_match_type_id')
            ->withPivot('master_match_category_id');
    }

    public function typeCategories()
    {
        return $this->belongsToMany(MasterMatchCategory::class, 'event_category_type', 'event_id', 'master_match_category_id')
            ->withPivot('master_match_type_id');
    }

    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    // Event → has many → Registration → has many → RegistrationType → has many → Type
    public function eventRegistrationTypes()
    {
        return $this->hasManyDeepFromRelations($this->eventRegistrations(), (new EventRegistration)->types());
    }

    public function eventRegistrationCategories()
    {
        return $this->hasManyDeepFromRelations($this->eventRegistrations(), (new EventRegistration)->categories());
    }

    public function typesOnCategory(int $categoryId)
    {
        return $this->categoryTypes()
            ->where('master_match_category_id', $categoryId);
    }

    public function categoriesOnType(int $typeId)
    {
        return $this->typeCategories()
            ->where('master_match_type_id', $typeId);
    }

    public function eventSpecialTypes()
    {
        return $this->belongsToMany(
            EventSpecialType::class,
            'event_special_type',
            'event_id',
            'event_special_type_id'
        );
        // {"registrations":{"master_school_id":"Nama TNI/POLRI","birth_certificate":"Upload KTA"}}
    }

    public function isHasRelayType(): bool
    {
        foreach ($this->types as $type) {
            if (Str::contains($type->name, ['ESTAFET', 'RELAY'])) {
                return true;
            }
        }

        return false;
    }

    public function getQrCodeUrlAttribute()
    {
        $photo = 'events/'.$this->slug.'.png';
        $storage = Storage::disk('shared');

        if ($storage->missing($photo)) {
            $qr = QrCode::format('png')->size(200)->margin(2)->generate(route('competition.detail', $this->slug).'/registration');

            $storage->put($photo, $qr);
        }

        $filePath = config('filesystems.disks.shared.root').'/'.$photo;

        return $storage->url($photo).(is_file($filePath) ? '?'.filemtime($filePath) : '');
    }

    public function getPreviewQrCodeAttribute()
    {
        return '<img src="'.$this->qr_code_url.'" alt="'.$this->name.'" height="150">';
    }

    public function getPreviewFancyQrCodeAttribute()
    {
        return '<a href="'.$this->qr_code_url.'" class="d-inline-block" data-fancybox data-caption="'.$this->name.'" data-toggle="tooltip" title="click to zoom">'.$this->preview_qr_code.'</a>';
    }
}
