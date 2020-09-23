@extends('layout/master')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1>{{ __('title.learning_page_title') }}</h1>
            <a href="/skill/mypage" class="btn btn-outline-primary">{{__('menu.back')}}</a>
        </div>
    </div>
    <div id="learningApp">
        <learning-app :skill="{{$skill}}" :exam-list="{{$examList}}"></learning-app>
    </div>
    <script src="/js/learningApp.js"></script>
@endsection
