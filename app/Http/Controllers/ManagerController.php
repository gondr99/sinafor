<?php

namespace App\Http\Controllers;

use App\Exam;
use App\SkillCategory;
use App\UserSkill;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function index(Request $req)
    {
        return view('user/manager');
    }

    //get all skill list by this manager
    public function manageSkillList(Request $req)
    {
        $user = auth()->user();
        $list = $user->manages()->get()->map(function($skill){
            $skill->expertList = $skill->expertedBy()->get();
            $skill->registerUser = $skill->registeredBy()->select(['users.*', 'status'])->get();
            return $skill;
        });

        return response()->json($list);
    }

    //accept user to skill register
    public function userAccept(Request $req)
    {
        $userId = $req->input('userId');
        $expertId = $req->input('expertId');
        $skillId = $req->input('skillId');

        $userSkill = UserSkill::where([['user_id', '=', $userId], ['skill_category_id', '=', $skillId]])->first();
        $userSkill->status = 1; //set status to pending
        $userSkill->expert_id = $expertId;
        $userSkill->save();

        return $this->manageSkillList($req);
    }

    public function getExamList(Request $req, $skillId){
        $examList = SkillCategory::find($skillId)->examList()->orderBy('order')->get();
        return response()->json($examList);
    }

    public function addExam(Request $req)
    {
        $data = $req->all();

        $skill = SkillCategory::find($data['belongs']); //find skill

        \DB::beginTransaction();
        try {
            $data['order'] = $skill->examList()->max('order') + 1;
            Exam::create($data);

            \DB::commit();
            return $this->getExamList($req, $skill->id);
        } catch (\Exception $e){

            \DB::rollBack();
            return response()->json(['msg'=>__('messages.error')], 500);
        }
    }

    public function deleteExam(Request $req, $id)
    {
        $user = auth()->user();
        try{
            $exam = Exam::find($id);
            $skill = $user->manages()->where('skill_categories.id', '=', $exam->belongs)->first();

            //check this user has manage permission to delete
            if($skill){
                //find lager order exam in skill and decrease 1
                $list = $skill->examList()->where('order', '>', $exam->order)->get();
                foreach ($list as $e){
                    $e->order = $e->order - 1;
                    $e->save();
                }
                $exam->delete();
            }else{
                throw new \Exception(__('messages.not_found'));
            }
        }catch (\Exception $e){

            return response()->json(__('messages.not_found'), 404);
        }

    }

    public function upExamOrder(Request $req, $id)
    {
        $user = auth()->user();
        try{
            $exam = Exam::find($id);
            $skill = $user->manages()->where('skill_categories.id', '=', $exam->belongs)->first();

            //check this user has manage permission to delete
            if($skill){
                //find 1 small than exam in skill
                $e = $skill->examList()->where('order', '=', ($exam->order - 1))->first();
                $exam->order = $e->order;
                $e->order = $e->order + 1;
                $e->save();
                $exam->save();
                return response()->json('messages.success');
            }else{
                throw new \Exception(__('messages.not_found'));
            }
        }catch (\Exception $e){
            return response()->json(__('messages.not_found'), 404);
        }
    }

    public function downExamOrder(Request $req, $id)
    {
        $user = auth()->user();
        try{
            $exam = Exam::find($id);
            $skill = $user->manages()->where('skill_categories.id', '=', $exam->belongs)->first();

            //check this user has manage permission to delete
            if($skill){
                //find 1 small than exam in skill
                $e = $skill->examList()->where('order', '=', ($exam->order + 1))->first();
                $exam->order = $e->order;
                $e->order = $e->order - 1;
                $e->save();
                $exam->save();
                return response()->json('messages.success');
            }else{
                throw new \Exception(__('messages.not_found'));
            }
        }catch (\Exception $e){
            return response()->json(__('messages.not_found'), 404);
        }
    }
}
