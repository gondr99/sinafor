<?php

namespace App\Http\Controllers;

use App\User;
use App\UserCategory;
use App\UserManage;
use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $req)
    {
        return view('admin/index');
    }

    public function getCategory(Request $req)
    {
        $list = UserCategory::get();
        return response()->json($list);
    }

    public function addCategory(Request $req)
    {
        $name = $req->input('name');
        $userCategory = UserCategory::create(['name' => $name]);
        return response()->json( ['msg' => __('messages.success_add'), 'data' => $userCategory] );
    }

    public function delCategory(Request $req)
    {
        $id = $req->input('id');
        $userCategory = UserCategory::find($id);
        if(!$userCategory){
            return response()->json(__('messages.error'), 500 );
        }
        $cnt = $userCategory->users()->count();
        if($cnt > 0){
            return response()->json(['msg' => __('messages.del_deny')] );
        }
        $userCategory->delete();
        return response()->json(['msg' => __('messages.success_del')] );
    }

    public function getUserList(Request $req, $page = 1){
        if($page <= 0) $page = 1;
        $list = User::skip( ($page - 1) * 10 )->take(10);
        $word = $req->input('word');

        if($word !== null){
            $list = $list->where('name', 'like', '%' . $word . '%')->orWhere('email', 'like', '%' . $word . '%');

        }
        $list = $list->get();

        foreach ($list as $user){
            $user->roles = \DB::table('user_roles')
                ->join('user_categories', 'user_roles.user_category_id', '=', 'user_categories.id')
                ->where('user_roles.user_id', '=', $user->id)->select('user_categories.id', 'name')->get();

        }
        $totalCount = User::count();
        return response()->json(['list'=>$list, 'totalCount' => $totalCount]);
    }

    public function addRole(Request $req)
    {
        $userId = $req->input('user_id');
        $categoryName = $req->input('category_name');

        $category = UserCategory::where('name', '=', $categoryName)->first();

        UserRole::create(['user_id'=> $userId, 'user_category_id' => $category->id]);
        return response()->json(['msg'=>__('messages.success'), 'role'=>$category]);
    }

    public function deleteRole(Request $req){
        $uid = $req->input('uid');
        $cid = $req->input('cid');
        $role = UserRole::where([['user_id', '=', $uid],['user_category_id', '=', $cid]])->first();

        $manageCount = UserManage::where('user_id', '=', $uid)->count();
        if($manageCount !== 0){
            return response()->json(__('messages.not_allow_remove_role'), 401);
        }
        if($role){
            $role->delete();
            return response()->json(__('message.success'));
        }
        return response()->json(__('messages.not_found'), 404);
    }

    public function getUserData(Request $req, $id)
    {
        $user = User::find($id);
        if($user === null){
            return response()->json(__('messages.not_found'), 404);
        }

        $user->roles = \DB::table('user_roles')
            ->join('user_categories', 'user_roles.user_category_id', '=', 'user_categories.id')
            ->where('user_roles.user_id', '=', $user->id)->select('user_categories.id', 'name')->get();

        return response()->json($user);
    }

    public function addUser(Request $req){
        //회원추가하기
        $input = $req->input();

        $uploadImage = $req->file('profile');
        if($uploadImage){
            $prefix = time();
            $filename = $prefix . "_" . $uploadImage->getClientOriginalName();
            $uploadImage->storeAs('profiles', $filename);  //save file to profiles folder
            $input['profile'] = $filename;
        }
        $input['password'] = bcrypt($input['password']);
        unset($input['_token']); //remove token
        unset($input['password_confirmation']);  //remove confirm

        $user = User::where('email', '=', $input['email'])->first();
        if($user ){
            return response()->json(['msg'=>__('register.duplicated'), 'success'=>false]);
        }
        \DB::beginTransaction();
        try {
            $user = User::create($input);
            //admin이 만드는 유저는 이메일 확인을 바로 해준다.
            $user->email_verified_at = time();
            $user->save();

            \DB::commit();
            return response()->json(['msg'=>__('register.success'), 'success'=>true]);
        }catch (\Exception $e){
            \DB::rollback();
            return response()->json(__('register.error'), 500);
        }
    }

    public function getExpertList(Request $req)
    {
        $expert = UserCategory::where('name', '=', env('EXPERT_NAME'))->first();
        $list = $expert->users()->get();
        return response()->json($list);
    }

    public function getManagerList(Request $req)
    {
        $manager = UserCategory::where('name', '=', env('MANAGER_NAME'))->first();
        $list = $manager->users()->get();
        return response()->json($list);

    }
}
