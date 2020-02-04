<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Tymon\JWTAuth\Contracts\JWTSubject;

use App\Enums\Field;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'names',
        'surnames',
        'email',
        'doc_num',
        'password',
        'role_id',
        'activated'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'role_id',
        'created_at',
        'updated_at'
    ];

    /**
    * Get the identifier that will be stored in the subject claim of the JWT.
    *
    * @return mixed
    */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function boot()
    {
        parent::boot();

        // before creating
        self::creating(function ($model) {
            // assigning id of modifier user
            $model->modified_by_id = auth()->id();
        });
    }

    // role access
    public function isSuperAdmin()
    {
        return $this->role->id == Field::ID_ROLE_SUPERADMIN;
    }

    public function isStaff()
    {
        return $this->role->id == Field::ID_ROLE_ADMIN || $this->role->id == Field::ID_ROLE_SUPERADMIN;
    }

    // relationships
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
