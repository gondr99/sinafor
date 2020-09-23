@extends('layout/master')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1>{{ __('title.manager_page_title') }}</h1>
        </div>
    </div>
    <div id="managerApp">

    </div>
    <script src="/js/managerApp.js"></script>
@endsection
