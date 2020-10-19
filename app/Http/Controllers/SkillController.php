<?php

namespace App\Http\Controllers;

use App\LevelOne;
use App\LevelTwo;
use App\SkillCategory;
use App\User;
use App\UserExpert;
use App\UserCategory;
use App\UserManage;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class SkillController extends Controller
{
    //send skill level list
    public function getLevel(Request $req)
    {
        $levelOnes = LevelOne::get()->map(function ($one) {
            $one->two = $one->level2()->get();
            return $one;
        });

        return response()->json($levelOnes);
    }

    public function addLevelOne(Request $req)
    {
        $name = $req->input('name');
        $image = $req->file('image');
        $desc = $req->input('desc');

        if (trim($name) === "" || $image === null || trim($desc) === "") {
            return response()->json(__('messages.require_empty'), 406); //now allowed
        }

        if (substr($image->getMimeType(), 0, 5) != 'image') {
            return response()->json(__('messages.type_error'), 406); //now allowed
        }

        $prefix = time();
        $filename = $prefix . "_" . $image->getClientOriginalName();
        $image->storeAs('skills', $filename);
        $one = LevelOne::create(['name' => $name, 'image' => $filename, 'desc' => $desc]);
        $one->two = [];

        return $this->getLevel($req);  //data refreshed when success input
    }

    public function addLevelTwo(Request $req, $oneId)
    {
        $name = $req->input('name');
        $image = $req->file('image');
        $desc = $req->input('desc');

        if (trim($name) === "" || $image === null || trim($desc) === "") {
            return response()->json(__('messages.require_empty'), 406); //now allowed
        }

        if (substr($image->getMimeType(), 0, 5) != 'image') {
            return response()->json(__('messages.type_error'), 406); //now allowed
        }

        $one = LevelOne::find($oneId);
        if ($one) {

            $prefix = time();
            $filename = $prefix . "_" . $image->getClientOriginalName();
            $image->storeAs('skills', $filename);

            $one->level2()->create(['name' => $name, 'image' => $filename, 'desc' => $desc]);
            return $this->getLevel($req);  //data refreshed when success input
        } else {
            return response()->json(__('messages.not_found'), 404); //not found
        }
    }

    public function delLevelOne(Request $req, $oneId)
    {
        try {
            LevelOne::find($oneId)->delete();
            return $this->getLevel($req);  //data refreshed when success delete
        } catch (\Exception $e) {
            return response()->json(__('messages.not_found'), 404); //not found
        }
    }

    public function delLevelTwo(Request $req, $twoId)
    {
        try {
            LevelTwo::find($twoId)->delete();
            return $this->getLevel($req);  //data refreshed when success delete
        } catch (\Exception $e) {
            return response()->json(__('messages.not_found'), 404); //not found
        }
    }

    //레벨2에 속한 스킬들을 전부 가져온다.
    public function level2SkillList(Request $req, $level2)
    {
        $lv = LevelTwo::find($level2);
        $list = $lv->skills()->get()->map(function ($skill) {
            $skill->managerList = $skill->manages()->get();
            $skill->expertList = $skill->expertedBy()->get();
            return $skill;
        });
        return response()->json($list);
    }

    public function allSkillInfo(Request $req)
    {
        $list = SkillCategory::get()->map(function ($skill) {
            $skill->managerList = $skill->manages()->get();
            $skill->expertList = $skill->expertedBy()->get();
            return $skill;
        });

        return response()->json($list);
    }

    //Get all skill with expert and manager from database;
    public function skillList(Request $req)
    {
        $user = auth()->user();
        $registeredSkillList = $user->registered()->select('status', 'skill_categories.id')->get();

        $list = SkillCategory::get()->map(function ($skill) use ($registeredSkillList) {
            $skill->managerList = $skill->manages()->get();
            $skill->expertList = $skill->expertedBy()->get();
            $skill->status = 0;
            for ($i = 0; $i < count($registeredSkillList); $i++) {
                if ($registeredSkillList[$i]->id === $skill->id) {
                    $skill->status = $registeredSkillList[$i]->status;
                    break;
                }
            }
            return $skill;
        });

        return response()->json($list);
    }

    public function addSkill(Request $req)
    {
        $name = $req->input('name');
        $description = $req->input('description');
        $level2 = $req->input('level2');

        $img = $req->file('image');

        if (substr($img->getMimeType(), 0, 5) != 'image') {
            return response()->json(__('messages.type_error'), 406); //now allowed
        }

        $prefix = time();
        $filename = $prefix . "_" . $img->getClientOriginalName();
        $path = $img->storeAs('icons', $filename);
        Image::make(storage_path('app/' . $path))->resize(64, 64)->save(storage_path('app/icons') . '/icon_' . $filename);
        $item = SkillCategory::create(['name' => $name, 'filename' => $filename, 'description' => $description, 'belongs' => $level2]);
        $item->managerList = [];
        $item->expertList = [];
        return response()->json($item);
    }

    public function getManger(Request $req, $id)
    {
        $skill = SkillCategory::find($id);
        if ($skill === null) {
            return response()->json(__('messages.not_found'), 404);
        }
        //get specify skill manager list;
        return response()->json($skill->manages()->get());
    }


    public function deleteManager(Request $req, $skillId)
    {
        //drop manager from skill($id)
        $uid = $req->input('user_id');
        $manage = UserManage::where([['user_id', '=', $uid], ['skill_category_id', '=', $skillId]])->first();
        //제거시 cascade라면 여기서 작업해줘야 한다.
        if ($manage) {
            $manage->delete();
            return response()->json(__('messages.success'));
        }
        return response()->json(__('messages.not_found'), 404);
    }

    public function addManager(Request $req, $skillId)
    {
        $userId = $req->input('user_id');
        $user = User::find($userId);
        $skill = SkillCategory::find($skillId);
        if ($user === null || $skill === null) {
            return response()->json(__('messages.not_found'), 404);
        }

        if ($user->roles()->where('name', '=', env('MANAGER_NAME'))->count() < 1) {
            return response()->json(__('messages.not_auth'), 404);
        }

        $mange = UserManage::create(['user_id' => $user->id, 'skill_category_id' => $skillId]);

        return response()->json(__('messages.success'));
    }

    public function deleteExpert(Request $req, $skillId)
    {
        //drop manager from skill($id)
        $uid = $req->input('user_id');
        $manage = UserExpert::where([['user_id', '=', $uid], ['skill_category_id', '=', $skillId]])->first();
        //제거시 cascade라면 여기서 작업해줘야 한다.
        if ($manage) {
            $manage->delete();
            return response()->json(__('messages.success'));
        }
        return response()->json(__('messages.not_found'), 404);
    }

    public function addExpert(Request $req, $skillId)
    {
        $userId = $req->input('user_id');
        $user = User::find($userId);
        $skill = SkillCategory::find($skillId);
        if ($user === null || $skill === null) {
            return response()->json(__('messages.not_found'), 404);
        }

        if ($user->roles()->where('name', '=', 'Expert')->count() < 1) {
            return response()->json(__('messages.not_auth'), 404);
        }

        $expert = UserExpert::create(['user_id' => $user->id, 'skill_category_id' => $skillId]);

        return response()->json(__('messages.success'));
    }

    public function addAllExpert(Request $req)
    {
        //모든 스킬을 한 사람에게 부여하기.
        $userId = $req->input('user_id');
        $user = User::find($userId);
        if ($user === null) {
            return response()->json(__('messages.not_found'), 404);
        }
        $skillList = SkillCategory::get();

        \DB::beginTransaction();
        try {
            foreach ($skillList as $skill) {
                //현재 해당 스킬의 전문가가 아니라면
                if (UserExpert::where([['user_id', '=', $user->id], ['skill_category_id', '=', $skill->id]])->count() < 1) {
                    UserExpert::create(['user_id' => $user->id, 'skill_category_id' => $skill->id]);
                }
            }
            \DB::commit();
            return response()->json(__('messages.success'));
        }catch (\Exception $e){
            \DB::rollback();
            dd($e);
            return response()->json(__('register.error'), 500);
        }
    }

    public function deleteAllExpert(Request $req)
    {
        //모든 스킬을 전문가에서 제거하기
        $userId = $req->input('user_id');
        $user = User::find($userId);
        if ($user === null) {
            return response()->json(__('messages.not_found'), 404);
        }

        UserExpert::where('user_id', '=', $userId)->delete();
        //제거시 cascade라면 여기서 작업해줘야 한다.
        return response()->json(__('messages.success'));
    }

    public function addAllManager(Request $req)
    {
        //모든 스킬을 한 사람에게 부여하기.
        $userId = $req->input('user_id');
        $user = User::find($userId);
        if ($user === null) {
            return response()->json(__('messages.not_found'), 404);
        }
        $skillList = SkillCategory::get();

        \DB::beginTransaction();
        try {
            foreach ($skillList as $skill) {
                //현재 해당 스킬의 매니저가 아니라면
                if (UserManage::where([['user_id', '=', $user->id], ['skill_category_id', '=', $skill->id]])->count() < 1) {
                    UserManage::create(['user_id' => $user->id, 'skill_category_id' => $skill->id]);
                }
            }
            \DB::commit();
            return response()->json(__('messages.success'));
        }catch (\Exception $e){
            \DB::rollback();
            return response()->json(__('register.error'), 500);
        }
    }

    public function deleteAllManager(Request $req)
    {
        //모든 스킬을 매니저에서 제거하기
        $userId = $req->input('user_id');
        $user = User::find($userId);
        if ($user === null) {
            return response()->json(__('messages.not_found'), 404);
        }

        UserManage::where('user_id', '=', $userId)->delete();
        //제거시 cascade라면 여기서 작업해줘야 한다.
        return response()->json(__('messages.success'));
    }


}

//    public function getSkillManager(Request $req, $id)
//    {
//        $skill = SkillCategory::find($id);
//        if($skill === null){
//            return response()->json(__('messages.not_found'), 404);
//        }
//        $managerList = $skill->manages()->get();
//        return response()->json($managerList);
//    }
