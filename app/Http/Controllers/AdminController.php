<?php

namespace App\Http\Controllers;

use App\User;
use App\UserCategory;
use App\UserManage;
use App\UserRole;
use Illuminate\Http\Request;

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
        return response()->json($list);
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
}
