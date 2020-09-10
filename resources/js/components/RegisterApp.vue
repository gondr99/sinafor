<template>
    <div class="row">
        <div class="col-8 offset-2">
            <form method="post">
                <input type="hidden" name="_token" :value="csrf">
                <div class="form-group">
                    <label for="email">{{  trans('register.email') }}</label>
                    <input type="email" class="form-control" id="email" placeholder="name@example.com" name="email">
                </div>
                <div class="form-group">
                    <label for="name">{{ trans('register.name') }}</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
                <div class="form-group">
                    <label for="phone">{{ trans('register.phone') }}</label>
                    <input type="text" class="form-control" id="phone" name="phone">
                </div>
                <div class="form-group">
                    <label for="password">{{ trans('register.password') }}</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">{{ trans('register.password_confirm') }}</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>
                <div class="form-group">
                    <label for="info">{{ trans('register.info') }}</label>
                    <textarea class="form-control" id="info" rows="3" name="info"></textarea>
                </div>
                <button class="btn btn-primary">{{trans('register.submit')}}</button>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name:'registerApp',
        data(){
            return {
                csrf:document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }
    }
</script>
