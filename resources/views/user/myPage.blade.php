@extends('layout/master')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1>{{ __('title.my_page_title') }}</h1>
        </div>
    </div>
    <div id="myPageApp">

    </div>
    <script src="/js/myPageApp.js"></script>
@endsection
