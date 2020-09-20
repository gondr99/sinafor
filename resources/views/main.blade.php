@extends('layout/master')

@section('content')
    <div class="row">
        <div class="col-12" id="menuGrid">
            <div class="item">
                <div class="card">
                    <img src="https://via.placeholder.com/150" class="card-img-top" alt="dummy image">
                    <div class="card-body">
                        <div class="card-title"><a href="/skill/register">{{ __('menu.skill_registration')  }}</a></div>
                    </div>
                </div>
            </div>

            <div class="item">
                <div class="card">
                    <img src="https://via.placeholder.com/150" class="card-img-top" alt="dummy image">
                    <div class="card-body">
                        <div class="card-title"><a href="/skill/mypage">{{ __('menu.my_page')  }}</a></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
