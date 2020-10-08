<?php

namespace App\Http\Controllers;

use App\PhaseInfo;
use Illuminate\Http\Request;

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
            ])->first();

        return response()->json($detailInfo);
    }
}
