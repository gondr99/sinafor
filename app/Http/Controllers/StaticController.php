<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaticController extends Controller
{
    public function index(Request $req)
    {

        if(auth()->check()){
            return redirect('/main');
        }
        return view("index");
    }

    public function mainPage(){
        return view('main');
    }

    public function getIconImage(Request $req, $filename)
    {
        //if file not exist 404 image downloaded
        if( !Storage::exists('/icons/' . $filename)){
            return Storage::download('404.jpg');
        }
        return Storage::download('/icons/' . $filename);
    }
}
