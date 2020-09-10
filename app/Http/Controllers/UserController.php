<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegister;
use App\User;
use Illuminate\Http\Request;

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
        unset($input['_token']); //remove token
        unset($input['password_confirmation']);  //remove confirm
        //not yet! still development...
        $input['password'] = bcrypt($input['password']);
        try {
            $user = User::create($input);
            return redirect('/')->with('flash_message', __('register.success'));
        }catch (\Exception $e){
            return back()->withInput()->with('flash_message', __('register.error'));
        }


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
}
