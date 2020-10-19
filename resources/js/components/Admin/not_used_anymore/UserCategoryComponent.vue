<template>
    <div class="row">
        <div class="col-12">
            <div class="input-group mb-3">
                <input type="text" class="form-control" :placeholder="trans('placeholder.add_user_category')" v-model="inputCategory" @keydown.enter="addUserCategory">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" @click="addUserCategory">{{ trans('menu.add')}}</button>
                </div>
            </div>
        </div>
        <div class="col-12 card-box">
            <div class="card" v-for="(item, idx) in categoryList" :key="item.id">
                <div class="card-body">
                    <h5 class="card-title">{{item.name}}</h5>
                    <div class="item-menu">
                        <button class="btn btn-sm btn-outline-danger" @click="delUserCategory(item.id)">{{ trans('menu.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        name: "UserCategoryComponent",
        mounted() {
            axios.get('/admin/category').then( res=>{
                this.$store.commit('refreshCategory', res.data);
            }).catch(err => {
                console.log(err);
            });
        },
        data(){
            return {
                inputCategory:''
            }
        },
        methods:{
            addUserCategory(){
                if(this.inputCategory.trim() === ""){
                    Swal.fire( this.trans('messages.empty') );
                    return;
                }
                axios.post('/admin/category', {name:this.inputCategory}).then(res => {
                    const data = res.data;
                    this.$store.commit('addCategory', data.data); //add data to category
                    Swal.fire( data.msg );
                }).catch(err => {
                    console.log(err);
                })
            },
            delUserCategory(idx){
                axios.delete(`/admin/category?id=${idx}`).then(res =>{
                    this.$store.commit('delCategory', idx); //delete category from data;
                }).catch(err => {
                    console.log(err);
                });
            }
        },
        computed: mapState({
            categoryList: state => state.categoryList
        })
    }
</script>

<style scoped>
    .card-box {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap:20px;
    }
</style>
