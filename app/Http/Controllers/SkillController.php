<?php

namespace App\Http\Controllers;

use App\SkillCategory;
use App\User;
use App\UserCategory;
use App\UserManage;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class SkillController extends Controller
{
    public function skillList(Request $req)
    {
        $list = SkillCategory::get()->map(function($skill){
            $skill->managerList = $skill->manages()->get();
            return $skill;
        });

        return response()->json($list);
    }

    public function addSkill(Request $req)
    {
        $name = $req->input('name');
        $description = $req->input('description');
        $img = $req->file('image');

        if (substr($img->getMimeType(), 0, 5) != 'image') {
            return response()->json(__('messages.type_error'), 406); //now allowed
        }

        $prefix = time();
        $filename = $prefix . "_" . $img->getClientOriginalName();
        $path = $img->storeAs('icons', $filename);
        Image::make(storage_path('app/'. $path))->resize(64, 64)->save(storage_path('app/icons'). '/icon_' . $filename);
        $item = SkillCategory::create(['name'=>$name, 'filename'=> $filename, 'description'=>$description]);
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

        if($user->roles()->where('name', '=', 'Skill Manager')->count() < 1){
            return response()->json(__('messages.not_auth'), 404);
        }

        $mange = UserManage::create(['user_id'=>$user->id, 'skill_category_id'=>$id]);

        return response()->json(__('messages.success'));
    }

    public function getManagerList(Request $req)
    {
        $list = UserCategory::where('name', '=', 'Skill Manager')->first()->users()->get();
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

}
