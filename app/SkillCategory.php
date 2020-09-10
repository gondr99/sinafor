<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SkillCategory extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'filename', 'description'];

    //get managing skill user
    public function manages(){
        return $this->belongsToMany('App\User','user_manages', 'skill_category_id');
    }
}
