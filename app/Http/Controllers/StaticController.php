<?php

namespace App\Http\Controllers;

use App\State;
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

    public function getImage(Request $req, $path, $filename)
    {
        //if file not exist 404 image downloaded
        if( !Storage::exists("/{$path}/" . $filename)){
            return Storage::download('404.jpg');
        }
        return Storage::download("/{$path}/" . $filename);
    }

    public function getVideo(Request $req, $path, $filename)
    {
        //if file not exist 404 image downloaded
        if( !Storage::exists("/{$path}/" . $filename)){
            abort(404);
            return;
        }

        return Storage::download("/{$path}/" . $filename);
    }

    public function getStateList(Request $req)
    {
        return response()->json(State::get());
    }

    public function getFile(Request $req, $path, $filename)
    {
        return Storage::download("/{$path}/" . $filename, );
    }
}
