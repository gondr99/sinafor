@extends('layout/master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    {{__('title.verified_email_already_send')}}
                </div>
                <div class="card-body">
                    {{__('title.email_already_send_content')}}
                </div>
            </div>
        </div>
    </div>
@endsection
