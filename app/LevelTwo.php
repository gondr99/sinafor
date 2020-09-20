<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LevelTwo extends Model
{
    protected $fillable = ['name', 'belongs'];

    public function level1(){
        return $this->belongsTo('App\LevelOne', 'belongs');
    }

    public function skills(){
        return $this->hasMany('App\SkillCategory', 'belongs');
    }
}
