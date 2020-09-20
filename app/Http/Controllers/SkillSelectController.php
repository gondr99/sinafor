<?php

namespace App\Http\Controllers;

use App\SkillCategory;
use App\UserSkill;
use Illuminate\Http\Request;

class SkillSelectController extends Controller
{
    public function index(Request $req)
    {
        return view('skill/register');
    }

    public function registerSkill(Request $req, $id)
    {
        $user = auth()->user();
        $cnt = $user->registered()->where('skill_categories.id', '=', $id)->count(); //check already registered
        if($cnt >= 1){
            return response()->json(['msg'=>__('messages.already_registered'), 'success'=>false]);
        }
        $skill = SkillCategory::find($id);
        if($skill){
            UserSkill::create(['user_id'=>$user->id, 'skill_category_id' => $skill->id]);
            return response()->json(['msg'=>__('messages.success'), 'success'=>true]);
        }
        return response()->json(['msg'=>__('messages.not_found'), 'success'=>false]);
    }

    //get user registered Skill list
    public function registerList(Request $req)
    {
        $user = auth()->user();
        $list = $user->registered()->select('status', 'created_at', 'skill_categories.*')->get();
        return response()->json($list);
    }
}
