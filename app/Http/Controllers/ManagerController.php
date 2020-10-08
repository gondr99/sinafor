<?php

namespace App\Http\Controllers;

use App\Exam;
use App\LevelTwo;
use App\PhaseInfo;
use App\SkillCategory;
use App\State;
use App\UploadPdf;
use App\User;
use App\UserSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $list = $this->getSkillList($user);
        return response()->json($list);
    }

    private function getSkillList(User $user){
        $list = $user->manages()->get()->map(function($skill){
            $skill->level2 = $skill->level2()->first();
            $skill->level1 = $skill->level2->level1()->first();
            return $skill;
        });
        return $list;
    }

    //관리자가 관리하는 유저들 정보 가져오기
    public function getMangeUserList(Request $req)
    {
        $user = auth()->user();
        $list = $user->manages()->get();
        $userList = [];
        foreach ($list as $skill) {
            $registerUser = $skill->registeredBy()->select('phase', 'status', 'user_skills.updated_at AS date', 'users.*')->get();
            foreach ($registerUser as $u){
                $u->skillName = $skill->name;
                $u->skillId = $skill->id;

                $userList[] = $u;
            }
        }
        return response()->json($userList);
    }

    //해당 유저가 등록한 스킬에 대한 등록정보들 가져오기
    public function getUserSkillInfo(Request $req, $userId, $skillId)
    {
        $skill = $this->makeSkillInfo($userId, $skillId);
        return response()->json($skill);
    }

    //해당 스킬에 등록된 pdf 파일 리스트 가져오기
    public function getPDF(Request $req, $skillId)
    {
        $pdfList = UploadPdf::where('skill_category_id', '=', $skillId)->get();
        return response()->json($pdfList);
    }

    //해당 스킬에 pdf 파일 등록하기
    public function uploadPDF(Request $req)
    {
        $file = $req->file('file');
        $skillId = $req->input('skillId');

        $prefix = time();
        $filename = $prefix . "_" . $file->getClientOriginalName();
        $file->storeAs('pdfs', $filename);

        UploadPdf::create(['skill_category_id'=> $skillId, 'filename' => $filename, 'original' => $file->getClientOriginalName()]);

        return $this->getPDF($req, $skillId);
    }

    //파일 삭제하기
    public function deletePDF(Request $req, $fileId)
    {
        $file = UploadPdf::find($fileId);
        if($file){
            $file->delete();
            Storage::delete("/pdfs/" . $file->filename);
        }
        return $this->getPDF($req, $file->skill_category_id);
    }

    //스킬정보 수정하기
    public function modifySkill(Request $req, $skillId)
    {
        $user = auth()->user();
        $skill = $user->manages()->where('skill_category_id', '=', $skillId)->first();
        if(!$skill){
            return response()->json(__('messages.not_auth'), 403 );
        }
        $level2 = $req->input('level2');
        $name = $req->input('name');
        $skill->belongs = $level2;
        $skill->name = $name;

        $skill->save();

        $skill->level2 = $skill->level2()->first();
        $skill->level1 = $skill->level2->level1()->first();
        return response()->json(['msg'=>__('messages.save_success'), 'skill'=>$skill]);
    }

    private function makeSkillInfo($userId, $skillId){
        $skill = SkillCategory::find($skillId);
        $skill->expertList = $skill->expertedBy()->select('users.id', 'users.name')->get();
        $skill->level2 = $skill->level2()->first();
        $skill->level1 = $skill->level2->level1()->first();

        $user = User::find($userId);

        $certificationInfo = $user->registered()->where('skill_category_id', '=', $skillId)->select('status', 'phase', 'expert_id', 'state_id')->first();

        $skill->status = $certificationInfo->status;
        $skill->phase = $certificationInfo->phase;
        $skill->expert_id = $certificationInfo->expert_id;
        if($skill->expert_id !== null){
            $skill->expertName = User::find($skill->expert_id)->name;
        }
        $skill->state = State::find($certificationInfo->state_id)->name;

        return $skill;
    }

    //accept user to skill register
    public function userAccept(Request $req)
    {
        $userId = $req->input('userId');
        $expertId = $req->input('expertId');
        $skillId = $req->input('skillId');

        $userSkill = UserSkill::where([['user_id', '=', $userId], ['skill_category_id', '=', $skillId]])->first();

        //이부분에 notify 들어가야 한다. to expert

        $userSkill->expert_id = $expertId;
        $userSkill->save();

        $skill = $this->makeSkillInfo($userId, $skillId);
        return response()->json(['msg'=>__('messages.expert_connected'), 'skillInfo' => $skill]);
    }

}


//지우기 아까워 남겨둔 코드
//public function getExamList(Request $req, $skillId){
//    $examList = SkillCategory::find($skillId)->examList()->orderBy('order')->get();
//    return response()->json($examList);
//}
//
//public function addExam(Request $req)
//{
//    $data = $req->all();
//
//    $skill = SkillCategory::find($data['belongs']); //find skill
//
//    \DB::beginTransaction();
//    try {
//        $data['order'] = $skill->examList()->max('order') + 1;
//        Exam::create($data);
//
//        \DB::commit();
//        return $this->getExamList($req, $skill->id);
//    } catch (\Exception $e){
//
//        \DB::rollBack();
//        return response()->json(['msg'=>__('messages.error')], 500);
//    }
//}
//
//public function deleteExam(Request $req, $id)
//{
//    $user = auth()->user();
//    try{
//        $exam = Exam::find($id);
//        $skill = $user->manages()->where('skill_categories.id', '=', $exam->belongs)->first();
//
//        //check this user has manage permission to delete
//        if($skill){
//            //find lager order exam in skill and decrease 1
//            $list = $skill->examList()->where('order', '>', $exam->order)->get();
//            foreach ($list as $e){
//                $e->order = $e->order - 1;
//                $e->save();
//            }
//            $exam->delete();
//        }else{
//            throw new \Exception(__('messages.not_found'));
//        }
//    }catch (\Exception $e){
//
//        return response()->json(__('messages.not_found'), 404);
//    }
//
//}
//
//public function upExamOrder(Request $req, $id)
//{
//    $user = auth()->user();
//    try{
//        $exam = Exam::find($id);
//        $skill = $user->manages()->where('skill_categories.id', '=', $exam->belongs)->first();
//
//        //check this user has manage permission to delete
//        if($skill){
//            //find 1 small than exam in skill
//            $e = $skill->examList()->where('order', '=', ($exam->order - 1))->first();
//            $exam->order = $e->order;
//            $e->order = $e->order + 1;
//            $e->save();
//            $exam->save();
//            return response()->json('messages.success');
//        }else{
//            throw new \Exception(__('messages.not_found'));
//        }
//    }catch (\Exception $e){
//        return response()->json(__('messages.not_found'), 404);
//    }
//}
//
//public function downExamOrder(Request $req, $id)
//{
//    $user = auth()->user();
//    try{
//        $exam = Exam::find($id);
//        $skill = $user->manages()->where('skill_categories.id', '=', $exam->belongs)->first();
//
//        //check this user has manage permission to delete
//        if($skill){
//            //find 1 small than exam in skill
//            $e = $skill->examList()->where('order', '=', ($exam->order + 1))->first();
//            $exam->order = $e->order;
//            $e->order = $e->order - 1;
//            $e->save();
//            $exam->save();
//            return response()->json('messages.success');
//        }else{
//            throw new \Exception(__('messages.not_found'));
//        }
//    }catch (\Exception $e){
//        return response()->json(__('messages.not_found'), 404);
//    }
//}
