<template>
    <div>
        <div class="row">
            <div class="col-4 p-2">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" :placeholder="trans('placeholder.search_text')" v-model="searchWord" @keypress.enter="search">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" @click="search">검색</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 p-2 user-list-box">
                <userinfo-component v-for="user in serchedUserList" :key="user.id" :user="user" @refreshUser="refreshUser"></userinfo-component>
            </div>
        </div>

        <div class="row" v-if="serchedUserList.length !== 0">
            <div class="col-12">
                여기엔 페이지 네이션이 들어가야 합니다.
            </div>
        </div>
    </div>

</template>

<script>
    import UserInfoComponent from "./UserInfoComponent";
    export default {
        name: "UserManageComponent",
        components:{
            'userinfo-component':UserInfoComponent
        },
        methods:{
            search(){
                let url = `/admin/user/list/${this.page}`;
                if(this.searchWord.trim() !== ''){
                    url += `?word=${this.word}`;
                }
                axios.get(url).then(res =>{
                    this.serchedUserList = res.data;
                });
            },
            refreshUser(userId){
                let userIdx = this.serchedUserList.findIndex(x => x.id == userId);
                axios.get(`/admin/user/${userId}`).then(res => {
                    this.$set(this.serchedUserList, userIdx, res.data);
                });
            }
        },
        data(){
            return {
                searchWord:'',
                page:1,
                serchedUserList:[]
            }
        }
    }
</script>

<style scoped>

    .user-list-box {
        padding:20px;
        display: grid;
        grid-template-columns: 1fr;
        grid-auto-rows: 50px;
    }
</style>

