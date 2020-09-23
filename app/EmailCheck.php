<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailCheck extends Model
{
    protected $fillable =['user_id', 'hash'];

    public function check($hash){
        return $this->hash === $hash;
    }
}
