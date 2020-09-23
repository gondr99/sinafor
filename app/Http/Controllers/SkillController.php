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
    public function getLevel(Request $req){
        $levelOnes = LevelOne::get()->map(function($one){
            $one->two = $one->level2()->get();
            return $one;
        });

        return response()->json($levelOnes);
    }

    public function addLevelOne(Request $req)
    {
        $name = $req->input('name');
        $one = LevelOne::create(['name' => $name]);
        $one->two = [];

        return $this->getLevel($req);  //data refreshed when success input
    }

    public function addLevelTwo(Request $req, $oneId)
    {
        $name = $req->input('name');
        $one = LevelOne::find($oneId);
        if($one){
            $one->level2()->create(['name'=>$name]);
            return $this->getLevel($req);  //data refreshed when success input
        }else{
            return response()->json(__('messages.not_found'), 404); //not found
        }
    }

    public function delLevelOne(Request $req, $oneId)
    {
        try {
            LevelOne::find($oneId)->delete();
            return $this->getLevel($req);  //data refreshed when success delete
        } catch (\Exception $e){
            return response()->json(__('messages.not_found'), 404); //not found
        }
    }

    public function delLevelTwo(Request $req, $twoId)
    {
        try {
            LevelTwo::find($twoId)->delete();
            return $this->getLevel($req);  //data refreshed when success delete
        } catch (\Exception $e){
            return response()->json(__('messages.not_found'), 404); //not found
        }
    }

    //Get all skill with expert and manager from database;
    public function skillList(Request $req)
    {
        $user = auth()->user();
        $registeredSkillList = $user->registered()->select('status', 'skill_categories.id')->get();

        $list = SkillCategory::get()->map(function($skill) use($registeredSkillList){
            $skill->managerList = $skill->manages()->get();
            $skill->expertList = $skill->expertedBy()->get();
            $skill->status = 0;
            for($i = 0; $i < count($registeredSkillList); $i++){
                if($registeredSkillList[$i]->id === $skill->id){
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
        Image::make(storage_path('app/'. $path))->resize(64, 64)->save(storage_path('app/icons'). '/icon_' . $filename);
        $item = SkillCategory::create(['name'=>$name, 'filename'=> $filename, 'description'=>$description, 'belongs'=>$level2]);
        $item->managerList = [];
        return response()->json($item);
    }

    public function getManger(Request $req, $id)
    {
        $skill = SkillCategory::find($id);
        if($skill === null){
            return response()->json(__('messages.not_found'), 404);
        }
        //get specify skill manager list;
        return response()->json($skill->manages()->get());
    }

    public function addManager(Request $req, $id)
    {
        $userId = $req->input('user_id');
        $user = User::find($userId);
        $skill = SkillCategory::find($id);
        if($user === null || $skill === null){
            return response()->json(__('messages.not_found'), 404);
        }

        if($user->roles()->where( 'name', '=', env('MANAGER_NAME') )->count() < 1){
            return response()->json(__('messages.not_auth'), 404);
        }

        $mange = UserManage::create(['user_id'=>$user->id, 'skill_category_id'=>$id]);

        return response()->json(__('messages.success'));
    }

    public function getManagerList(Request $req)
    {
        $list = UserCategory::where('name', '=', env('MANAGER_NAME'))->first()->users()->get();
        return response()->json($list);
    }

    public function getExpertList(Request $req)
    {
        $list = UserCategory::where('name', '=', env('EXPERT_NAME'))->first()->users()->get();
        return response()->json($list);
    }

    public function getSkillManager(Request $req, $id)
    {
        $skill = SkillCategory::find($id);
        if($skill === null){
            return response()->json(__('messages.not_found'), 404);
        }
        $managerList = $skill->manages()->get();
        return response()->json($managerList);
    }

    public function deleteManager(Request $req, $id){
        //drop manager from skill($id)
        $uid = $req->input('uid');
        $manage = UserManage::where([['user_id', '=', $uid],['skill_category_id', '=', $id]])->first();

        if($manage){
            $manage->delete();
            return response()->json(__('messages.success'));
        }
        return response()->json(__('messages.not_found'), 404);
    }

    public function deleteExpert(Request $req, $id){
        //drop manager from skill($id)
        $uid = $req->input('uid');
        $manage = UserExpert::where([['user_id', '=', $uid],['skill_category_id', '=', $id]])->first();

        if($manage){
            $manage->delete();
            return response()->json(__('messages.success'));
        }
        return response()->json(__('messages.not_found'), 404);
    }

    public function addExpert(Request $req, $id)
    {
        $userId = $req->input('user_id');
        $user = User::find($userId);
        $skill = SkillCategory::find($id);
        if($user === null || $skill === null){
            return response()->json(__('messages.not_found'), 404);
        }

        if($user->roles()->where('name', '=', 'Expert')->count() < 1){
            return response()->json(__('messages.not_auth'), 404);
        }

        $expert = UserExpert::create(['user_id'=>$user->id, 'skill_category_id'=>$id]);

        return response()->json(__('messages.success'));
    }
}
