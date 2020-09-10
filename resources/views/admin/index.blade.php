@extends('layout/master')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1>{{ __('adminmenu.title') }}</h1>
        </div>
    </div>
    <div id="adminApp">

    </div>
    <script src="/js/adminApp.js"></script>
@endsection
