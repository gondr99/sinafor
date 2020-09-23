<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserExpert extends Model
{
    public $timestamps = false;
    protected $fillable = ['user_id', 'skill_category_id'];
}
