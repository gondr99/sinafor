<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LevelOne extends Model
{
    protected $fillable = ['name', 'image', 'desc'];

    public function level2(){
        return $this->hasMany('App\LevelTwo', 'belongs');
    }
}
