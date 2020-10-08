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

        $state = $req->input('state');
        $date = $req->input('date');   // useless until now...(2020.-10.-07)

        $skill = SkillCategory::find($id);
        if($skill){
            UserSkill::create(['user_id'=>$user->id, 'skill_category_id' => $skill->id, 'state_id'=>$state]);
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

    public function learningPage(Request $req, $skillId)
    {
        $user = auth()->user();
        $skill = $user->registered()->where('skill_categories.id', '=', $skillId)->first();
        if(!$skill){  // user who not registed this skill
            return redirect('/')->with('flash_message', __('messages.not_auth'));
        }

        $examList = $skill->examList()->get()->map(function($exam) use ($user) {

            $exam->subjectList = $exam->subjects()->where('user_id', '=', $user->id)->get();
            $exam->pass = false;
            foreach($exam->subjectList as $subject){
                if($subject->pass === 1){
                    $exam->pass = true;
                    break;
                }
            }
            return $exam;
        });

        return view('/user/learn', ['skill' => $skill, 'examList' => $examList]);
    }

    public function getLearningData(Request $req, $skillId){

    }
}
