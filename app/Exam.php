<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    public $timestamps = false;
    protected $fillable = ['belongs', 'title', 'type', 'order'];

    public function skill(){
        return $this->belongsTo('App\SkillCategory', 'belongs');
    }

    public function subjects(){
        return $this->hasMany('App\Subject', 'exam_id');
    }
}
