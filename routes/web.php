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
Route::middleware(['emailVerify'])->group(function(){
    Route::middleware(['checkLogin'])->group(function(){
        Route::get('/user', 'UserController@getUserData');
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

        Route::get('/user/my_certification', 'CertificationController@index');
        Route::get('/user/skill_detail/{skillId}', 'CertificationController@getSkillDetail')->where('skillId', '[0-9]+');
    });

//manager router
    Route::middleware(['checkManager'])->group(function(){
        Route::get('/manager', 'ManagerController@index');

        Route::put('/manager/user', 'ManagerController@userAccept');

        //더이상 안쓰임...흐미..아까워..
//        Route::post('/manager/exam', 'ManagerController@addExam');
//        Route::get('/manager/exam/{skillId}', 'ManagerController@getExamList')->where('skillId', '[0-9]+');
//
//        Route::put('/manager/exam/up/{id}', 'ManagerController@upExamOrder')->where('id', '[0-9]+');
//        Route::put('/manager/exam/down/{id}', 'ManagerController@downExamOrder')->where('id', '[0-9]+');
//        Route::delete('/manager/exam/{id}', 'ManagerController@deleteExam')->where('id', '[0-9]+');

        //자신이 관리하는 스킬의 유저들을 보여준다.
        Route::get('/manager/user_list', 'ManagerController@getMangeUserList');
        //해당 유저의 상세 신청정보를 본다.
        Route::get('/manager/userSkillInfo/{userId}/{skillId}', 'ManagerController@getUserSkillInfo')->where(['userId'=>'[0-9]+', 'skillId' =>'[0-9]+']);
        //자신이 관리하는 모든 스킬의 정보를 가져온다(레벨정보 포함)
        Route::get('/manager/skill', 'ManagerController@manageSkillList');
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
    Route::middleware(['checkExpert'])->group(function(){
        Route::get('/expert', 'ExpertController@index');
        Route::get('/expert/skill', 'ExpertController@getSkillList');
        Route::put('/expert/confirm', 'ExpertController@confirmUser');
        Route::put('/expert/certificate', 'ExpertController@certificateUser');

        // no more used ....지우기엔 너무 아까워...
        //Route::get('/expert/skill/exam/{skillId}', 'ExpertController@getExamList')->where('skillId', '[0-9]+');
        //Route::get('/expert/teach/{skillId}/{userId}', 'ExpertController@showUserLearningPage')->where('skillId', '[0-9]+')->where('userId', '[0-9]+');
        //Route::get('/expert/progress/{skillId}/{userId}', 'ExpertController@getProgress')->where('skillId', '[0-9]+')->where('userId', '[0-9]+');
    });
});


Route::middleware(['checkAdmin'])->group(function () {
    Route::get('/admin', 'AdminController@index');

    Route::get('/admin/category', 'AdminController@getCategory');
    Route::post('/admin/category', 'AdminController@addCategory');
    Route::delete('/admin/category', 'AdminController@delCategory');

    Route::post('/admin/skill', 'SkillController@addSkill');

    //get all skillList with expert and managerdata
    Route::get('/admin/skill', 'SkillController@skillList');


    //get user who has permission to mange

    Route::get('/admin/skill/manager', 'SkillController@getManagerList');
    Route::get('/admin/skill/manager/{id}', 'SkillController@getSkillManager')->where('id', '[0-9]+');
    Route::put('/admin/skill/manager/{id}', 'SkillController@addManager')->where('id', '[0-9]+');
    Route::delete('/admin/skill/manager/{id}', 'SkillController@deleteManager')->where('id', '[0-9]+');

    //get All expert list from database
    Route::get('/admin/skill/expert', 'SkillController@getExpertList');

    Route::delete('/admin/skill/expert/{id}', 'SkillController@deleteExpert')->where('id', '[0-9]+');
    Route::put('/admin/skill/expert/{id}', 'SkillController@addExpert')->where('id', '[0-9]+');

    Route::get('/admin/user/list/{page?}', 'AdminController@getUserList')->where('page', '[0-9]+');
    Route::put('/admin/user/role', 'AdminController@addRole');
    Route::delete('/admin/user/role', 'AdminController@deleteRole');

    Route::get('/admin/user/{id}', 'AdminController@getUserData')->where('id', '[0-9]+');

    Route::get('/admin/skillLevel', 'SkillController@getLevel');
    Route::post('/admin/levelOne', 'SkillController@addLevelOne');
    Route::delete('/admin/levelOne/{oneId}', 'SkillController@delLevelOne')->where('oneId', '[0-9]+');
    Route::post('/admin/levelTwo/{oneId}', 'SkillController@addLevelTwo')->where('oneId', '[0-9]+');
    Route::delete('/admin/levelTwo/{twoId}', 'SkillController@delLevelTwo')->where('twoId', '[0-9]+');
});

//image router
Route::get('/images/{path}/{filename}', 'StaticController@getImage')->where('path', '[a-z]+');

//video router - 권한이 필요하다면 이부분을 미들웨어 안쪽으로 넣어야 한다.
Route::get('/videos/{path}/{filename}', 'StaticController@getVideo')->where('path', '[a-z]+');

//pdffile router
Route::get('/file/{path}/{filename}', 'StaticController@getFile')->where('path', '[a-z]+');;

//send email -> this is only debug usage
//Route::get('/send', function(){
//    $user = [
//        'email' => 'gondr99@gmail.com',
//        'name' => 'ㅊㅊㅊ'
//    ];
//
//    $data = [
//        'detail' => 'This is your detail',
//        'name'=>'ccc'
//    ];
//
//    Mail::send('email.registermail', $data, function($message) use($user){
//        $message->from('gondr99@gmail.com', 'master of sinafor');
//        $message->to($user['email'], $user['name'])->subject('Welcome!');
//    });
//});

Route::get('/js/lang.js', function () {
//    $strings = Cache::rememberForever('lang.js', function () {
//        $lang = config('app.locale');
//
//        $files   = glob(resource_path('lang/' . $lang . '/*.php'));
//        $strings = [];
//
//        foreach ($files as $file) {
//            $name           = basename($file, '.php');
//            $strings[$name] = require $file;
//        }
//
//        return $strings;
//    });  //개발이 끝나면 이부분을 주석해제할 것. 속도향상을 위한 캐시 처리

    $lang = config('app.locale');

    $files = glob(resource_path('lang/' . $lang . '/*.php'));
    $strings = [];

    foreach ($files as $file) {
        $name = basename($file, '.php');
        $strings[$name] = require $file;
    }

    header('Content-Type: text/javascript');
    echo('window.i18n = ' . json_encode($strings, JSON_UNESCAPED_UNICODE) . ';');
    exit();
})->name('assets.lang');

Route::get('/js/roleName.js', function(){
    header('Content-Type: text/javascript');
    $arr = ['admin' => env('ADMIN_NAME'), 'manager' => env('MANAGER_NAME'), 'expert' => env('EXPERT_NAME')];
    //0->pending
    //1->Not approved
    //2->Approved

    echo("window.roleList = " . json_encode($arr, JSON_UNESCAPED_UNICODE) . ";");
    $arr = [0=>env('PENDING'), 1=>env('NOTAPPROVED'), 2=>env('APPROVED')];
    echo("window.statusName = " . json_encode($arr, JSON_UNESCAPED_UNICODE) . ";");
    $arr = [ ['id'=>0, 'name'=>env('APPLYING')],  ['id'=>1, 'name'=>'phase1'], ['id'=>2, 'name'=>'phase2'], ['id'=>3, 'name'=>'phase3'], ['id'=>4, 'name'=>'phase4'] ];
    echo("window.phaseList = " . json_encode($arr, JSON_UNESCAPED_UNICODE) . ";");
});

// -> this is only debug usage
Route::get('/debug', function () {

    //트랜젝션 시작
    \DB::beginTransaction();
    try {
        $admin = App\User::create(['email' => 'admin@admin', 'password' => bcrypt('admin1234'), 'name' => '관리자', 'info' => 'admin', 'phone' => '010-6304-2759', 'profile' => '1600764168_iu.png']);
        $admin->email_verified_at = time();
        $admin->save();
        $verified = App\UserCategory::create(['name' => env('VERIFIED_NAME')]);
        App\UserCategory::create(['name' => env('EXPERT_NAME')]);
        App\UserCategory::create(['name' => env('MANAGER_NAME')]);
        $adminCategory = App\UserCategory::create(['name' => env('ADMIN_NAME')]);

        //관리자에 권한부여
        App\UserRole::create(['user_id' => $admin->id, 'user_category_id' => $adminCategory->id]);
        App\UserRole::create(['user_id' => $admin->id, 'user_category_id' => $verified->id]);

        $user = App\User::create(['email' => 'user@user', 'password' => bcrypt('user1234'), 'name' => '일반유저', 'info' => 'normal user', 'phone' => '010-1111-1111', 'profile' => '1600764168_sana.png']);
        $user->email_verified_at = time();
        $user->save();

        $expert = App\User::create(['email' => 'expert@expert', 'password' => bcrypt('expert1234'), 'name' => '전문가', 'info' => 'expert user', 'phone' => '010-2222-2222', 'profile' => '1600764168_zzwi.png']);
        $expert->email_verified_at = time();
        $expert->save();
        $manager = App\User::create(['email' => 'manager@manager', 'password' => bcrypt('manager1234'), 'name' => '매니저', 'info' => 'manager user', 'phone' => '010-3333-3333', 'profile' => '1600764168_momo.png']);
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
