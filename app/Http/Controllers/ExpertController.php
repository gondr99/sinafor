<?php

namespace App\Http\Controllers;

use App\Notifications\ExpertCertificateYou;
use App\Notifications\ExpertConfirmYou;
use App\SkillCategory;
use App\User;
use App\UserSkill;
use Illuminate\Http\Request;

class ExpertController extends Controller
{
    public function index(Request $req)
    {
        return view('user/expert');
    }

    public function getSkillList(Request $req)
    {
        $user = auth()->user();
        $list = $user->experts()->get()->map(function($skill){
            $skill->userList = $skill->registeredBy()->select('users.*', 'status')->get();
            return $skill;
        });
        return response()->json($list);
    }

    public function confirmUser(Request $req)
    {
        $noti = new ExpertConfirmYou();
        return $this->setStatusAndSendEmail($req, 2, $noti);
    }

    public function certificateUser(Request $req)
    {
        $noti = new ExpertCertificateYou();
        return $this->setStatusAndSendEmail($req, 3, $noti);
    }

    public function setStatusAndSendEmail($req, $status, $noti)
    {
        $userId = $req->input('userId');
        $expert = auth()->user();
        $noti->payload = (Object)['name' => $expert->name, 'email' => $expert->email];
        \DB::beginTransaction();
        try{
            $userSkill = $expert->userSkill()->where('user_id', '=', $userId)->first();
            $userSkill->status = $status; //set to confirm;
            $userSkill->save();

            $user = User::find($userId);
            $user->notify($noti);
            \DB::commit();
            return $this->getSkillList($req);
        }catch (\Exception $e){
            \DB::rollback();
            return response()->json(__('messages.not_found'), 404);
        }
    }

}


//no more used code....너무 아까워..지우기가.
//public function getExamList(Request $req, $skillId)
//{
//    $skill = SkillCategory::find($skillId);
//    $list = $skill->examList()->get();
//    return response()->json($list);
//}
//
////show user's learning Page to expert
//public function showUserLearningPage(Request $req){
//
//}
//
////get user's progress status from skill
//public function getProgress(Request $req, $skillId, $userId)
//{
//    $user = auth()->user();
//    try {
//        $skill = $user->experts()->where('skill_categories.id', '=', $skillId)->first();
//
//        $user = $skill->registeredBy()->where('users.id', '=', $userId)->first();
//
//        //얼마나 수강했고 몇개가 완료되었는지 보내줄 수 있도록 코딩해야 해(2020.09.22) 으아아아....살려줘..
//
//
//    }catch (\Exception $e){
//        return response()->json(__('messages.not_found'), 404);
//    }
//
//}
