<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'email',
        'email_verified_at',
        'master_user_type_id',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function sluggable()
    // {
    //     return [
    //         'username' => [
    //             'source' => 'name',
    //         ],
    //     ];
    // }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = strtolower($value);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function isSuperuser()
    {
        if ($this->roles->contains('slug', 'superuser')) {
            return true;
        }

        return false;
    }

    public function isExternalUser()
    {
        if ($this->roles->contains('slug', 'user')) {
            return true;
        }

        return false;
    }

    public function isCoach()
    {
        if ($this->isSuperuser()) {
            return true;
        }

        if ($this->roles->contains('slug', 'coach')) {
            return true;
        }

        return false;
    }

    public function isJury()
    {
        if ($this->isSuperuser()) {
            return true;
        }

        if ($this->roles->contains('slug', 'jury')) {
            return true;
        }

        return false;
    }

    public function isMember()
    {
        if ($this->isSuperuser()) {
            return true;
        }

        if ($this->roles->contains('slug', 'member')) {
            return true;
        }

        return false;
    }

    public function hasRole($role)
    {
        if ($this->isSuperuser()) {
            return true;
        }
        if (is_string($role)) {
            return $this->roles->contains('slug', $role);
        }

        return (bool) $this->roles->intersect($role)->count();
    }

    public function checkRole($role)
    {
        if ($this->isSuperuser()) {
            return true;
        }
        if (is_string($role)) {
            return $this->roles->contains('slug', $role);
        }

        return (bool) $this->roles->whereIn('slug', $role)->count();
    }

    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->first();
        }

        return $this->roles()->attach($role);
    }

    public function revokeRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->first();
        }

        return $this->roles()->detach($role);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function type()
    {
        return $this->belongsTo(MasterUserType::class, 'master_user_type_id');
    }

    public function educations()
    {
        return $this->hasMany(UserEducation::class, 'user_id');
    }

    public function gayaLimits()
    {
        return $this->hasMany(UserMemberGayaLimit::class, 'user_id');
    }

    public function userChampionships()
    {
        return $this->hasMany(UserChampionship::class, 'user_id');
    }

    public function userStaff()
    {
        return $this->hasOne(UserStaff::class, 'user_id');
    }

    public function userMember()
    {
        return $this->hasOne(UserMember::class, 'user_id');
    }

    public function userType()
    {
        return $this->belongsTo(MasterUserType::class, 'master_user_type_id');
    }

    public function scopeStaff($query)
    {
        $query->whereHas('userType', function ($query) {
            $query->where('slug', 'staff');
        });
    }

    public function scopeMember($query)
    {
        $query->whereHas('userType', function ($query) {
            $query->where('slug', 'member');
        });
    }

    public function getPhotoUrlAttribute()
    {
        $photo = optional($this->profile)->photo;
        $storage = Storage::disk('shared');

        if (empty($photo) || $storage->missing($photo)) {
            // return '//i.pravatar.cc/300?u=' . $this->email;
            // return '//www.gravatar.com/avatar/' . md5($this->email) . '?d=identicon&f=y';
            return '//placebeard.it/300x400/notag';
            // return '';
        }

        $filePath = config('filesystems.disks.shared.root').'/'.$photo;

        return $storage->url($photo).(is_file($filePath) ? '?'.filemtime($filePath) : '');
    }

    public function getPreviewPhotoAttribute()
    {
        return '<img src="'.getAvatar($this->photo_url, $this->email, 150).'" alt="'.$this->name.'" height="150">';
    }

    public function getPreviewTinyPhotoAttribute()
    {
        return '<img src="'.getAvatar($this->photo_url, $this->email, 50).'" alt="'.$this->name.'" height="50">';
    }

    public function getPreviewFancyPhotoAttribute()
    {
        return '<a href="'.getAvatar($this->photo_url, $this->email, 150).'" class="d-inline-block" data-fancybox data-caption="'.$this->name.'" data-toggle="tooltip" title="click to zoom">'.$this->preview_photo.'</a>';
    }

    public function getPreviewTinyFancyPhotoAttribute()
    {
        return '<a href="'.getAvatar($this->photo_url, $this->email, 50).'" class="d-inline-block" data-fancybox data-caption="'.$this->name.'" data-toggle="tooltip" title="click to zoom">'.$this->preview_tiny_photo.'</a>';
    }

    public function getBirthCertificateUrlAttribute()
    {
        $photo = optional($this->profile)->birth_certificate;
        $storage = Storage::disk('shared');

        if (empty($photo) || $storage->missing($photo)) {
            // return '//i.pravatar.cc/150?u='.$this->email;
            // return '//www.gravatar.com/avatar/'.md5($this->email).'?d=identicon&f=y';
            return '';
        }

        $filePath = config('filesystems.disks.shared.root').'/'.$photo;

        return $storage->url($photo).(is_file($filePath) ? '?'.filemtime($filePath) : '');
    }

    public function getPreviewBirthCertificateAttribute()
    {
        return '<img src="'.getFileCustomSize($this->birth_certificate_url, 200).'" alt="'.$this->name.'" height="150">';
    }

    public function getPreviewFancyBirthCertificateAttribute()
    {
        return '<a href="'.$this->birth_certificate_url.'" class="d-inline-block" data-fancybox data-caption="'.$this->name.'" data-toggle="tooltip" title="click to zoom">'.$this->preview_birth_certificate.'</a>';
    }

    public function getFamilyCardUrlAttribute()
    {
        $photo = optional($this->profile)->family_card;
        $storage = Storage::disk('shared');

        if (empty($photo) || $storage->missing($photo)) {
            // return '//i.pravatar.cc/150?u='.$this->email;
            // return '//www.gravatar.com/avatar/'.md5($this->email).'?d=identicon&f=y';
            return '';
        }

        $filePath = config('filesystems.disks.shared.root').'/'.$photo;

        return $storage->url($photo).(is_file($filePath) ? '?'.filemtime($filePath) : '');
    }

    public function getPreviewFamilyCardAttribute()
    {
        return '<img src="'.getFileCustomSize($this->family_card_url, 200).'" alt="'.$this->name.'" height="150">';
    }

    public function getPreviewFancyFamilyCardAttribute()
    {
        return '<a href="'.$this->family_card_url.'" class="d-inline-block" data-fancybox data-caption="'.$this->name.'" data-toggle="tooltip" title="click to zoom">'.$this->preview_family_card.'</a>';
    }

    public function getKtaCardUrlAttribute()
    {
        $photo = optional($this->profile)->kta_card;
        $storage = Storage::disk('shared');

        if (empty($photo) || $storage->missing($photo)) {
            // return '//i.pravatar.cc/150?u='.$this->email;
            // return '//www.gravatar.com/avatar/'.md5($this->email).'?d=identicon&f=y';
            return '';
        }

        $filePath = config('filesystems.disks.shared.root').'/'.$photo;

        return $storage->url($photo).(is_file($filePath) ? '?'.filemtime($filePath) : '');
    }

    public function getPreviewKtaCardAttribute()
    {
        return '<img src="'.getFileCustomSize($this->kta_card_url, 200).'" alt="'.$this->name.'" height="150">';
    }

    public function getPreviewFancyKtaCardAttribute()
    {
        return '<a href="'.$this->kta_card_url.'" class="d-inline-block" data-fancybox data-caption="'.$this->name.'" data-toggle="tooltip" title="click to zoom">'.$this->preview_kta_card.'</a>';
    }

    public function getSignatureUrlAttribute()
    {
        $photo = optional($this->profile)->signature;
        $storage = Storage::disk('shared');

        if (empty($photo) || $storage->missing($photo)) {
            // return '//i.pravatar.cc/150?u='.$this->email;
            // return '//www.gravatar.com/avatar/'.md5($this->email).'?d=identicon&f=y';
            return '';
        }

        $filePath = config('filesystems.disks.shared.root').'/'.$photo;

        return $storage->url($photo).(is_file($filePath) ? '?'.filemtime($filePath) : '');
    }

    public function getPreviewSignatureAttribute()
    {
        return '<img src="'.getFileCustomSize($this->signature_url, 200).'" alt="'.$this->name.'" height="150">';
    }

    public function getPreviewFancySignatureAttribute()
    {
        return '<a href="'.$this->signature_url.'" class="d-inline-block" data-fancybox data-caption="'.$this->name.'" data-toggle="tooltip" title="click to zoom">'.$this->preview_signature.'</a>';
    }
}
