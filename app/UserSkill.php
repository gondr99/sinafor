<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSkill extends Model
{
    protected $fillable = ['user_id', 'skill_category_id', 'state_id'];

    public function expert(){
        return $this->belongsTo('App\User', 'id', 'expert_id');
    }

}
