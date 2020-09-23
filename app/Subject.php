<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//user's subject to exam
class Subject extends Model
{
    protected $fillable = ['exam_id', 'user_id', 'content'];
}
