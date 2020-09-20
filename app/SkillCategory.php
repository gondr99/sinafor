<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SkillCategory extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'filename', 'description', 'belongs'];

    //get managing skill user
    public function manages(){
        return $this->belongsToMany('App\User','user_manages', 'skill_category_id');
    }

    public function level2(){
        return $this->belongsTo('App\LevelTwo', 'belongs');
    }

    public function registeredBy(){
        return $this->belongsToMany('App\User','user_skills', 'skill_category_id');
    }
}
