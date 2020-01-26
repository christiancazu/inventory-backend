<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are not assignable.
     *
     * @var array
     */
    protected $guarded = [
        'name'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description'
    ];

    /**
     * relationships
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
