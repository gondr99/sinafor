<template>
    <div class="row">
        <div class="col-8 offset-2 bg-box p-4">
            <form @submit.prevent="makeUser">
                <div class="form-group">
                    <label for="email">{{  trans('register.email') }}</label>
                    <input type="email" class="form-control" id="email" placeholder="name@example.com" name="email" required v-model="formData.email">
                </div>
                <div class="form-group">
                    <label for="name">{{ trans('register.name') }}</label>
                    <input type="text" class="form-control" id="name" name="name" :placeholder="trans('placeholder.name')" required v-model="formData.name">
                </div>
                <div class="form-group">
                    <label for="phone">{{ trans('register.phone') }}</label>
                    <input type="text" class="form-control" id="phone" name="phone" :placeholder="trans('placeholder.phone')" required v-model="formData.phone">
                </div>
                <div class="form-group">
                    <label for="password">{{ trans('register.password') }}</label>
                    <input type="password" class="form-control" id="password" name="password" required v-model="formData.password">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">{{ trans('register.password_confirm') }}</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required v-model="formData.passwordConfirm">
                </div>
                <div class="form-group">
                    <label for="info">{{ trans('register.profile_img') }}</label>
                    <input type="file" accept="image/*" name="profile" class="form-control" @change="fileChange">
                </div>
                <div class="form-group">
                    <label for="info">{{ trans('register.info') }}</label>
                    <textarea class="form-control" id="info" rows="3" name="info" required v-model="formData.info"></textarea>
                </div>
                <button class="btn btn-primary">{{trans('register.submit')}}</button>
                <button type="button" class="btn btn-danger" @click="cancel">{{trans('register.cancel')}}</button>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AddUserComponent",
        data(){
            return {
                formData:{
                    email:'',
                    name:'',
                    phone:'',
                    password:'',
                    passwordConfirm:'',
                    info:'',
                    profile:null
                }
            }
        },
        methods:{
            makeUser(){
                if(this.formData.password !== this.formData.passwordConfirm){
                    Swal.fire(this.trans('register.password_incorrect'));
                    return;
                }
                const data = new FormData();
                for(let key in this.formData){
                    data.append(key, this.formData[key]);
                }
                axios.post('/admin/add_user', data, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(res => {
                    Swal.fire(res.data.msg);
                    if(res.data.success){
                        this.$emit('complete');
                    }
                }).catch(err => {
                    Swal.fire(err.response.data);
                });
            },
            cancel(){
                this.$emit('cancel')
            },
            fileChange(e){
                this.formData.profile = e.target.files[0];
            }
        }
    }
</script>

<style scoped>
    .bg-box {
        background-color: #fff;
        border-radius: 10px;
    }
</style>
