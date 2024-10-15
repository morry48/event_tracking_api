<?php

namespace App\Features\Shared\RolePermission\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Permission extends Model
{
    protected $table = 'permissions';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['name'];

    /**
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = Str::uuid();
            }
        });
    }

    /**
     * associated role
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission', 'permission_id', 'role_id');
    }
}
