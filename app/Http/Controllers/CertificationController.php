<?php

namespace App\Http\Controllers;

use App\PhaseInfo;
use App\UserSkill;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificationController extends Controller
{
    public function index(Request $req)
    {
        return view('user/certification');
    }

    public function getSkillDetail(Request $req, $skillId)
    {
        $user = auth()->user();
        $detailInfo = PhaseInfo::where([
            ['user_id', '=', $user->id],
            ['skill_category_id', '=', $skillId],
            ])->orderByDesc('id')->first();

        return response()->json($detailInfo);
    }

    public function getVideos(Request $req, $skillId)
    {
        $user = auth()->user();
        $list = Video::where([['user_id','=', $user->id], ['skill_category_id', '=', $skillId]])->get();
        return response()->json($list);
    }

    public function uploadVideo(Request $req)
    {
        $user = auth()->user();
        $file = $req->file('file');
        $skillId = $req->input('skillId');
        try{
            $userSkill = UserSkill::where([['user_id','=', $user->id], ['skill_category_id', '=', $skillId]])->first();

            $prefix = time();
            $filename = $prefix . "_" . $file->getClientOriginalName();
            $file->storeAs('videos', $filename);
            //['user_id', 'expert_id', 'skill_category_id', 'filename'];
            Video::create(['user_id'=>$user->id, 'expert_id'=> $userSkill->expert_id, 'skill_category_id' => $skillId, 'filename' => $filename]);

            return response()->json('success', 200);
        }catch(\Exception $e) {
            return response()->json(__('messages.not_found'), 404);
        }
    }

    public function deleteVideo(Request $req, $videoId)
    {
        $user = auth()->user();
        $video = Video::find($videoId);
        if($video){
            if($video->user_id !== $user->id){
                return response(__('messages.not_auth'), 403);
            }
            $video->delete();
            Storage::delete("/videos/" . $video->filename);
        }
        return response()->json('success', 200);
    }
}
