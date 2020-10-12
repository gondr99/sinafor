<?php

namespace App\Http\Controllers;

use App\Notifications\ExpertCertificateYou;
use App\Notifications\ExpertConfirmYou;
use App\PhaseInfo;
use App\SkillCategory;
use App\User;
use App\UserSkill;
use App\Video;
use http\Env\Response;
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

    //인증과정에 있는 모든 유저를 가져온다.
    public function getCertificate(Request $req)
    {
        $user = auth()->user();

        $skillList = $user->experts()->get();
        $userList = [];
        //관리하고 있는 스킬에 있는 유저들의 상태를 다 가져온다.
        foreach ($skillList as $skill){
            $users = $skill->registeredBy()->select('phase', 'status','detail', 'user_skills.updated_at as date','users.*')->where('expert_id', '=', $user->id)->get();

            foreach ($users as $u){
                $u->skill = $skill;
                $userList[] = $u;
            }
        }

        return response()->json($userList);
    }

    public function confirmUser(Request $req)
    {
        $noti = new ExpertConfirmYou();
        return $this->setPhaseAndSendEmail($req, 1, $noti);
    }

    public function setPhaseAndSendEmail($req, $phase, $noti)
    {
        $userId = $req->input('userId');
        $skillId = $req->input('skillId');
        $expert = auth()->user();

        $noti->payload = (Object)['name' => $expert->name, 'email' => $expert->email];
        \DB::beginTransaction();
        try{
            $userSkill = $expert->userSkill()->where([ ['user_id', '=', $userId], ['skill_category_id', '=', $skillId] ])->first();

            $userSkill->phase = $phase; //set to phase1;

            $userSkill->save();

            $user = User::find($userId);
            $user->notify($noti);
            \DB::commit();

            return $this->getCertificate($req);
        }catch (\Exception $e){
            \DB::rollback();
            //dump($e->getMessage());
            return response()->json(__('messages.not_found'), 404);
        }
    }
    //유저의 Detail정보를 업데이트 한다.
    public function updateDetail(Request $req)
    {
        $userId = $req->input('userId');
        $skillId = $req->input('skillId');
        $detail = $req->input('detail');
        $status = $req->input('status');

        $expert = auth()->user();
        \DB::beginTransaction();
        try {
            $userSkill = $expert->userSkill()->where([ ['user_id', '=', $userId], ['skill_category_id', '=', $skillId] ])->first();
            $phase = $userSkill->phase;
            if($status === 2) { //Approved
                $phase += 1;
            }
            $phaseInfo = PhaseInfo::create([
                'user_id' => $userId,
                'expert_id' => $expert->id,
                'skill_category_id' => $skillId,
                'phase' => $phase,
                'status' => $status,
                'detail' => $detail]);

            $userSkill->phase = $phase;
            if($status === 2){
                //다음 페이즈로 넘어갔다면 현재 상태를 0으로 변경
                $userSkill->status = 0;
            }else{
                $userSkill->status = $status;
            }
            $userSkill->detail = $detail;
            $userSkill->save();

            \DB::commit();

            return $this->getCertificate($req);
        } catch (\Exception $e){
            \DB::rollback();
            return response()->json(__('messages.not_found'), 404);
        }
    }

    public function getVideoList(Request $req, $userId, $skillId)
    {
        $list = Video::where([['user_id','=', $userId], ['skill_category_id', '=', $skillId]])->get();
        return response()->json($list);
    }


    //이 뒤쪽은 재활용 가능함.


    public function certificateUser(Request $req)
    {
        $noti = new ExpertCertificateYou();
        return $this->setStatusAndSendEmail($req, 3, $noti);
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
