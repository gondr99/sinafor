<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','phone', 'info'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function checkAdmin(){
        return $this->roles()->where('name', '=', 'admin')->first() !== null;
    }

    public function checkManager(){
        return $this->roles()->where('name', '=', 'Skill Manager')->first() !== null;
    }

    public function roles()
    {
        return $this->belongsToMany('App\UserCategory','user_roles', 'user_id');
    }

    public function manages()
    {
        return $this->belongsToMany('App\SkillCategory','user_manages', 'user_id');
    }

    public function registered()
    {
        return $this->belongsToMany('App\SkillCategory', 'user_skills', 'user_id');
    }
}
