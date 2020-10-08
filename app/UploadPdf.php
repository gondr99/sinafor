<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UploadPdf extends Model
{
    protected $fillable=['skill_category_id', 'filename', 'original'];
}
