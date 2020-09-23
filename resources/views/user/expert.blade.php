@extends('layout/master')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1>{{ __('title.expert_page_title') }}</h1>
        </div>
    </div>
    <div id="expertApp">

    </div>
    <script src="/js/expertApp.js"></script>
@endsection
