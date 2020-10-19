<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['user_id', 'expert_id'];

    public function messages(){
        return $this->hasMany('App\Message', 'room_id');
    }
}
