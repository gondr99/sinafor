<?php

namespace App\Http\Controllers;

use App\EmailCheck;
use App\Http\Requests\UserRegister;
use App\Notifications\SendRegisterEmail;
use App\User;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class UserController extends Controller
{
    public function registerPage(Request $req)
    {
        //$category = UserCategory::get(); //get all user category list from db;
        return view('user/register');
    }

    public function registerProcess(UserRegister $req)
    {
        //all validating login is in UserRegister Request file

        $input = $req->input();

        $uploadImage = $req->file('profile');
        $prefix = time();
        $filename = $prefix . "_" . $uploadImage->getClientOriginalName();
        $uploadImage->storeAs('profiles', $filename);  //save file to profiles folder
        $input['profile'] = $filename;

        unset($input['_token']); //remove token
        unset($input['password_confirmation']);  //remove confirm

        $input['password'] = bcrypt($input['password']);
        $user = User::where('email', '=', $input['email'])->first();
        if($user ){
            return back()->withInput()->with('flash_message', __('register.duplicated'));
        }
        \DB::beginTransaction();
        try {
            $user = User::create($input);
            $key = hash('sha256', $prefix . $user->email);
            EmailCheck::create(['user_id' => $user->id, 'hash' => $key]);
            $user->notify(new SendRegisterEmail((Object)['username'=>$user->name, 'hash'=> $key]));

            \DB::commit();
            return redirect('/')->with('flash_message', __('register.success'));
        }catch (\Exception $e){
            \DB::rollback();
            return back()->withInput()->with('flash_message', __('register.error'));
        }
    }

    public function checkEmail(Request $req)
    {
        $hash = $req->input('hash');
        $checker = EmailCheck::where('hash', '=', $hash)->first();
        if(!$checker){
            return redirect('/')->with('flash_message', __('register.expired_email_verify_link'));
        }

        $user = User::find($checker->user_id);
        $user->email_verified_at = time();
        $user->save();
        $checker->delete();

        return redirect('/')->with('flash_message', __('register.email_verify_complete'));
    }

    public function loginPage(Request $req)
    {
        return view('user/login');
    }

    public function loginProcess(Request $req)
    {
        $credentials = $req->input();
        $req->validate([
            'email' => 'bail|required',  //if first validating is failed, second is ignore
            'password' => 'required'
        ],[
            'email.required' => __('register.email_required'),
            'password.required' => __('register.password_required'),
        ]);

        $remember = false;
        if( isset($credentials['remember'])){
            unset($credentials['remember']);
            $remember = true;
        }
        unset($credentials['_token']);

        if(auth()->attempt($credentials, $remember)){
            return redirect('/')->with('flash_message', __('messages.success_login'));
        }else {
            return back()->with('flash_message', __('messages.login_failed'))->withInput();
        }
    }

    public function logout(Request $req)
    {
        auth()->logout();
        return redirect('/')->with('flash_message', __('messages.success_logout'));
    }

    public function getUserData(Request $req, $userId = 0)
    {
        if($userId === 0)
            $user = auth()->user();
        else{
            $user = User::find($userId);
            if(!$user) return response()->json(__('messages.not_found'), 403);
        }

        $user->roleList = $user->roles()->get();
        $user->skillList = $user->registered()->select('status', 'skill_categories.*')->get();
        return response()->json($user);
    }
}
