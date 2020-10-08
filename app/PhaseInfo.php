<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhaseInfo extends Model
{
    protected $fillable = ['user_id', 'expert_id', 'skill_category_id', 'phase', 'status', 'details'];

}
