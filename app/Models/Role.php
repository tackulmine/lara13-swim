<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;

// use Kyslik\ColumnSortable\Sortable;

class Role extends BaseModel
{
    // use Sortable;
    use Sluggable;

    public $timestamps = false;

    protected $fillable = [
        'slug',
        'name',
    ];

    // protected $sortable = [
    //     'name'
    // ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    // public function permissions()
    // {
    //     return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
    // }

    // public function roleHasPermissions()
    // {
    //     return $this->hasMany(PermissionRole::class);
    // }

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    // public function addPermission($permission)
    // {
    //     if (is_string($permission)) {
    //         $permission = Permission::where('name', $permission)->first();
    //     }
    //     return $this->permissions()->attach($permission);
    // }

    // public function removePermission($permission)
    // {
    //     if (is_string($permission)) {
    //         $permission = Permission::where('name', $permission)->first();
    //     }
    //     return $this->permissions()->detach($permission);
    // }
}
