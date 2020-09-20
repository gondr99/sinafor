<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyPageController extends Controller
{
    public function index(Request $req)
    {
        return view('/user/myPage');
    }
}
