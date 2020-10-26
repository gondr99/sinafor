<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', 'StaticController@index');

Route::get('/user/register', 'UserController@registerPage');
Route::post('/user/register', 'UserController@registerProcess');
Route::get('/user/login', 'UserController@loginPage');
Route::get('/user/logout', 'UserController@logout');

Route::post('/user/login', 'UserController@loginProcess');

//email verified email
Route::get('/user/email', 'UserController@checkEmail');
Route::get('/user/email_check', function (){
    if(auth()->user()->email_verified_at !== null){
        return redirect('/main');
    }
    return view('check_email');
});


//all user must verified email
Route::middleware(['emailVerify', 'checkLogin'])->group(function(){
    Route::get('/user/my_certification', 'CertificationController@index');

    Route::get('/user/{userId?}', 'UserController@getUserData');

    Route::get('/main', 'StaticController@mainPage');
    Route::get('/skill/register', 'SkillSelectController@index');

    Route::get('/skill/skillLevel', 'SkillController@getLevel');
    Route::get('/skill/skillList', 'SkillController@skillList');

    Route::get('/skill/register/{id}', 'SkillSelectController@registerSkillPage')->where('id', '[0-9]+');
    Route::put('/skill/register/{id}', 'SkillSelectController@registerSkill')->where('id', '[0-9]+');

    //myPage router
    Route::get('/skill/mypage', 'MyPageController@index');
    Route::get('/skill/register/list', 'SkillSelectController@registerList');

    //learning router
    Route::get('/skill/learn/{skillId}', 'SkillSelectController@learningPage')->where('skillId', '[0-9]+');

    //get state list from server
    Route::get('/state', 'StaticController@getStateList');

    Route::get('/user/skill_detail/{skillId}', 'CertificationController@getSkillDetail')->where('skillId', '[0-9]+');

    //upload video on phase4
    Route::post('/skill/video', 'CertificationController@uploadVideo');
    //get video list on phase4
    Route::get('/skill/video/{skillId}', 'CertificationController@getVideos')->where('skillId', '[0-9]+');

    //upload video router 2 - 권한이 필요하다고 판단됨.
    Route::get('/upload_video/{userId}/{skillId}/{filename}', 'StaticController@getUploadVideo')->where(['userId'=>'[0-9]+', 'skillId' =>'[0-9]+']);;
    Route::delete('/skill/video/{videoId}', 'CertificationController@deleteVideo')->where('videoId', '[0-9]+');

    Route::get('/skill/assistance', 'ChatController@index');

    //채팅관련 라우팅
    //방가져오기
    Route::get('/chat/room/{userId}', 'ChatController@getRoom');
    //안읽은 메시지 갯수 가져오기
    Route::get('/chat/unread', 'ChatController@getUnread');
    //읽음 처리하기
    Route::put('/chat/read', 'ChatController@putRead');
    //토큰 가져오기
    Route::get('/token', 'ChatController@getToken');
});

//manager router
Route::middleware(['emailVerify', 'checkManager'])->group(function(){

    //자신이 관리하는 모든 스킬의 정보를 가져온다(레벨정보 포함)
    Route::get('/manager/skill', 'ManagerController@manageSkillList');
    Route::get('/manager', 'ManagerController@index');

    Route::put('/manager/user', 'ManagerController@userAccept');

    //자신이 관리하는 스킬의 유저들을 보여준다.
    Route::get('/manager/user_list', 'ManagerController@getMangeUserList');
    //해당 유저의 상세 신청정보를 본다.
    Route::get('/manager/userSkillInfo/{userId}/{skillId}', 'ManagerController@getUserSkillInfo')->where(['userId'=>'[0-9]+', 'skillId' =>'[0-9]+']);

    //스킬의 레벨정보를 불러온다
    Route::get('/manager/level', 'SkillController@getLevel');
    //스킬의 pdf 파일 리스트를 받아온다.
    Route::get('/manager/pdf/{skillId}', 'ManagerController@getPDF')->where('skillId', '[0-9]+');
    //스킬에 pdf 파일을 업로드 한다.
    Route::post('/manager/pdf', 'ManagerController@uploadPDF');
    //스킬에서 pdf를 삭제한다.
    Route::delete('/manager/pdf/{fildId}', 'ManagerController@deletePDF')->where('fileId', '[0-9]+');;
    //스킬정보를 수정합니다.
    Route::put('/manager/skill/{skillId}', 'ManagerController@modifySkill')->where('skillId','[0-9]+');
});

//expert router
Route::middleware(['emailVerify', 'checkExpert'])->group(function(){
    Route::get('/expert', 'ExpertController@index');
    //관리하고 있는 사용자들의 리스트를 가져온다.
    Route::get('/expert/certificate', 'ExpertController@getCertificate');
    //사용자를 확인한다.
    Route::put('/expert/confirm', 'ExpertController@confirmUser');
    //사용자의 Status와 Detail을 업데이트 한다.
    Route::post('/expert/update_detail', 'ExpertController@updateDetail');
    //관리하는 Skill에 Phase4에서 올린 영상을 가져온다.
    Route::get('/expert/video/{userId}/{skillId}', 'ExpertController@getVideoList')->where(['userId'=>'[0-9]+', 'skillId' =>'[0-9]+']);
    //채팅페이지 입장
    Route::get('/expert/answer', 'ChatController@index');

    Route::put('/expert/certificate', 'ExpertController@certificateUser');
    Route::get('/expert/request', 'ExpertController@index');
});


Route::middleware(['checkAdmin'])->group(function () {
    Route::get('/admin', 'AdminController@index');

    Route::get('/admin/category', 'AdminController@getCategory');
    Route::post('/admin/category', 'AdminController@addCategory');
    Route::delete('/admin/category', 'AdminController@delCategory');

    Route::post('/admin/skill', 'SkillController@addSkill');


    Route::get('/admin/skillList/{level2}', 'SkillController@level2SkillList')->where('level2', '[0-9]+');

    Route::get('/admin/skillList', 'SkillController@allSkillInfo');
    Route::get('/admin/expertList', 'AdminController@getExpertList');
    Route::get('/admin/managerList', 'AdminController@getManagerList');


//    Route::get('/admin/skill/manager/{id}', 'SkillController@getSkillManager')->where('id', '[0-9]+');
    //각 스킬에 매니저를 부여하거나 빼주는 매서드들
    Route::put('/admin/skill/manager/{skillId}', 'SkillController@addManager')->where('skillId', '[0-9]+');
    Route::delete('/admin/skill/manager/{skillId}', 'SkillController@deleteManager')->where('skillId', '[0-9]+');
    Route::put('/admin/skill/manager/all', 'SkillController@addAllManager');
    Route::delete('/admin/skill/manager/all', 'SkillController@deleteAllManager');

    //각 스킬을 전문가에게 부여하거나 빼주는 매서드들
    Route::delete('/admin/skill/expert/{skillId}', 'SkillController@deleteExpert')->where('skillId', '[0-9]+');
    Route::put('/admin/skill/expert/{skillId}', 'SkillController@addExpert')->where('skillId', '[0-9]+');
    Route::put('/admin/skill/expert/all', 'SkillController@addAllExpert');
    Route::delete('/admin/skill/expert/all', 'SkillController@deleteAllExpert');

    Route::get('/admin/user/list/{page?}', 'AdminController@getUserList')->where('page', '[0-9]+');
    Route::put('/admin/user/role', 'AdminController@addRole');
    Route::delete('/admin/user/role', 'AdminController@deleteRole');

    Route::get('/admin/user/{id}', 'AdminController@getUserData')->where('id', '[0-9]+');

    Route::get('/admin/skillLevel', 'SkillController@getLevel');
    Route::post('/admin/levelOne', 'SkillController@addLevelOne');
    Route::delete('/admin/levelOne/{oneId}', 'SkillController@delLevelOne')->where('oneId', '[0-9]+');
    Route::post('/admin/levelTwo/{oneId}', 'SkillController@addLevelTwo')->where('oneId', '[0-9]+');
    Route::delete('/admin/levelTwo/{twoId}', 'SkillController@delLevelTwo')->where('twoId', '[0-9]+');


    Route::post('/admin/add_user', 'AdminController@addUser');
});

//image router
Route::get('/images/{path}/{filename}', 'StaticController@getImage')->where('path', '[a-z]+');

//video router - 권한이 필요하다면 이부분을 미들웨어 안쪽으로 넣어야 한다.
Route::get('/videos/{path}/{filename}', 'StaticController@getVideo')->where('path', '[a-z]+');



//pdffile router
Route::get('/file/{path}/{filename}', 'StaticController@getFile')->where('path', '[a-z]+');;

Route::get('/js/lang.js', function () {
    $strings = Cache::rememberForever('lang.js', function () {
        $lang = config('app.locale');
        var_dump($lang);
        $files   = glob(resource_path('lang/' . $lang . '/*.php'));
        $strings = [];

        foreach ($files as $file) {
            $name           = basename($file, '.php');
            $strings[$name] = require $file;
        }

        return $strings;
    });  //개발이 끝나면 이부분을 주석해제할 것. 속도향상을 위한 캐시 처리


    //$lang = config('app.locale');
//    $files = glob(resource_path('lang/' . $lang . '/*.php'));
//    $strings = [];
//
//    foreach ($files as $file) {
//        $name = basename($file, '.php');
//        $strings[$name] = require $file;
//    }

    header('Content-Type: text/javascript');
    echo('window.i18n = ' . json_encode($strings, JSON_UNESCAPED_UNICODE) . ';');
    exit();
})->name('assets.lang');

Route::get('/js/roleName.js', function(){
    header('Content-Type: text/javascript');
    $arr = ['admin' => 'Admin', 'manager' => 'Manager', 'expert' => 'Expert'];
    //$arr = ['admin' => env('ADMIN_NAME'), 'manager' => env('MANAGER_NAME'), 'expert' => env('EXPERT_NAME')];   //env bug is still remain..
//    EXPERT_NAME=Expert
//MANAGER_NAME=Manager
//ADMIN_NAME=Admin
//VERIFIED_NAME=Verified
    //0->pending
    //1->Not approved
    //2->Approved
    //EXPERT_NAME=Expert
    //MANAGER_NAME=Manager
    //ADMIN_NAME=Admin
    //VERIFIED_NAME=Verified
//    PENDING=Pending
//    NOTAPPROVED="Not approved"
//    APPROVED=Approved
//
//    APPLYING=Applying

    echo("window.roleList = " . json_encode($arr, JSON_UNESCAPED_UNICODE) . ";");
    $arr = [0=>'Pending', 1=>'Not approved', 2=>'Approved'];
    echo("window.statusName = " . json_encode($arr, JSON_UNESCAPED_UNICODE) . ";");

    $arr = [
        ['id'=>0, 'name'=>'Applying', 'icon'=>'far fa-id-card'],
        ['id'=>1, 'name'=>'Phase1', 'icon' => 'far fa-handshake'],
        ['id'=>2, 'name'=>'Phase2', 'icon' => 'far fa-file-word'],
        ['id'=>3, 'name'=>'Phase3', 'icon' => 'far fa-file-word'],
        ['id'=>4, 'name'=>'Phase4', 'icon' => 'far fa-file-video']
    ];
    echo("window.phaseList = " . json_encode($arr, JSON_UNESCAPED_UNICODE) . ";");
});

// -> this is only debug usage
Route::get('/debug', function () {

    //트랜젝션 시작
    \DB::beginTransaction();
    try {
        $admin = App\User::create(['email' => 'admin@admin', 'password' => bcrypt('admin1234'), 'name' => 'Admin user', 'info' => 'admin', 'phone' => '010-6304-2759', 'profile' => '1600764168_iu.png']);
        $admin->email_verified_at = time();
        $admin->save();
        $verified = App\UserCategory::create(['name' => env('VERIFIED_NAME')]);
        App\UserCategory::create(['name' => env('EXPERT_NAME')]);
        App\UserCategory::create(['name' => env('MANAGER_NAME')]);
        $adminCategory = App\UserCategory::create(['name' => env('ADMIN_NAME')]);

        //관리자에 권한부여
        App\UserRole::create(['user_id' => $admin->id, 'user_category_id' => $adminCategory->id]);
        App\UserRole::create(['user_id' => $admin->id, 'user_category_id' => $verified->id]);

        $user = App\User::create(['email' => 'user@user', 'password' => bcrypt('user1234'), 'name' => 'Jhon doe', 'info' => 'normal user', 'phone' => '010-1111-1111', 'profile' => '1600764168_sana.jpg']);
        $user->email_verified_at = time();
        $user->save();

        $expert = App\User::create(['email' => 'expert@expert', 'password' => bcrypt('expert1234'), 'name' => 'Expert user', 'info' => 'expert user', 'phone' => '010-2222-2222', 'profile' => '1600764168_zzwi.jpg']);
        $expert->email_verified_at = time();
        $expert->save();
        $manager = App\User::create(['email' => 'manager@manager', 'password' => bcrypt('manager1234'), 'name' => 'Manager user', 'info' => 'manager user', 'phone' => '010-3333-3333', 'profile' => '1600764168_momo.jpg']);
        $manager->email_verified_at = time();
        $manager->save();

        $info = App\LevelOne::create(['name' => 'Information Tech', 'image'=>'info.jpg', 'desc' => 'lorem lipsum']);
        $mecha = App\LevelOne::create(['name' => 'Mechatronics', 'image'=>'mecha.jpg', 'desc' => 'lorem lipsum']);
        $computer = App\LevelTwo::create(['name'=>'Computer Science', 'image'=>'computer.jpg', 'desc' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed pretium tempor ex, luctus ultricies nisl commodo a. Morbi pulvinar ex.', 'belongs'=> $info->id]);
        $metal = App\LevelTwo::create(['name'=>'metal', 'image'=>'metal.jpg', 'desc' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed pretium tempor ex, luctus ultricies nisl commodo a. Morbi pulvinar ex.', 'belongs'=> $mecha->id]);


        App\SkillCategory::create(['name' => 'Web Techonology', 'filename' => '1599651457_web.jpg', 'belongs' => $computer->id,
            'description' => 'make web tech Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab aspernatur consequuntur delectus dolores eius eligendi enim, fuga in incidunt iusto nemo officiis rem repellendus? Alias deleniti dignissimos dolor eaque facere!']);
        App\SkillCategory::create(['name' => 'Android Techonology', 'filename' => '1599651534_android.jpg', 'belongs' => $computer->id,
            'description' => 'make and tech Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab aspernatur consequuntur delectus dolores eius eligendi enim, fuga in incidunt iusto nemo officiis rem repellendus? Alias deleniti dignissimos dolor eaque facere!']);
        App\SkillCategory::create(['name' => 'Pipeline Techonology', 'filename' => '1599651558_pipe.jpg', 'belongs' => $metal->id,
            'description' => 'make pipe tech Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab aspernatur consequuntur delectus dolores eius eligendi enim, fuga in incidunt iusto nemo officiis rem repellendus? Alias deleniti dignissimos dolor eaque facere!']);

        $stateList = ["Alajuela", "Cartago", "Guanacaste", "Heredia", "Limón", "Puntarenas", "San José"];

        for($i = 0; $i < count($stateList); $i++){
            App\State::create(['name' => $stateList[$i]]);
        }

        //성공시 커밋
        \DB::commit();
        echo "success";
    } catch (\Exception $e)
    {
        dump($e->getMessage());
        //실패시 롤백
        \DB::rollBack();
    }
});
