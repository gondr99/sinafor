<template>
    <div>
        <div class="row">
            <div class="col-4">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" :placeholder="trans('placeholder.search_text')" v-model="word" @keypress.enter="search">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" @click="search">검색</button>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <button class="btn btn-outline-primary" @click="openAddUserPage">{{trans('adminmenu.add_user')}}</button>
            </div>
        </div>

        <div class="row">
            <div class="col-12 p-2 user-list-box">
                <userinfo-component v-for="user in serchedUserList" :key="user.id" :user="user" @refreshUser="refreshUser"></userinfo-component>
            </div>
        </div>

        <div class="row mt-3" v-if="serchedUserList.length !== 0">
            <div class="col-12">
                <ul class="pagination justify-content-center">
                    <li class="page-item" v-for="page in pageList" :class="{disabled:page.disable, active:page.active}">
                        <a v-if="page.disable" class="page-link">{{page.value}}</a>
                        <a v-else class="page-link" @click="loadPage(page.link)">{{page.value}}</a>
                    </li>
                </ul>

            </div>
        </div>

        <transition name="fade">
            <div v-if="addUserPopup" class="add-user-popup">
                <add-user-component @cancel="addUserCancel" @complete="addUserComplete"></add-user-component>
            </div>
        </transition>
    </div>

</template>

<script>
    import UserInfoComponent from "./UserInfoComponent";
    import AddUserComponent from "./AddUserComponent";
    export default {
        name: "UserManageComponent",
        components:{
            'userinfo-component':UserInfoComponent,
            'add-user-component':AddUserComponent
        },
        mounted() {
            this.loadUserData();
        },
        methods:{
            loadPage(page){
                if(this.page === page) return;
                this.page = page;
                this.loadUserData();
            },
            loadUserData(){
                let url = `/admin/user/list/${this.page}`;
                if(this.word.trim() !== ''){
                    url += `?word=${this.word}`;
                }
                axios.get(url).then(res =>{
                    this.serchedUserList = res.data.list;
                    this.totalCount = res.data.totalCount;
                    this.makePagination();
                });
            },
            search(){
                //페이지 1로 바꾸고 검색
                this.page = 1;
                this.loadUserData();
            },
            refreshUser(userId){
                let userIdx = this.serchedUserList.findIndex(x => x.id == userId);
                axios.get(`/admin/user/${userId}`).then(res => {
                    this.$set(this.serchedUserList, userIdx, res.data);
                });
            },
            makePagination(){
                this.pageList = [];
                let totalPage = Math.ceil(this.totalCount / this.pageCount);
                let pageEnd = Math.ceil(this.page / this.pageCount) * this.pageCount;
                let pageStart = pageEnd - this.pageCount + 1;
                if(pageEnd > totalPage) pageEnd = totalPage;

                this.pageList.push({value: '<<', disable:pageStart === 1, active:false, link:pageStart - 1});
                for(let i = pageStart; i <= pageEnd; i++){
                    this.pageList.push({value:i, disable:false, active: i === this.page, link:i});
                }
                this.pageList.push({value: '>>', disable:pageEnd === totalPage, active:false, link:pageEnd + 1});
            },
            openAddUserPage(){
                this.addUserPopup = true;
            },
            addUserComplete(){
                this.loadUserData();
                this.addUserPopup = false;
            },
            addUserCancel(){
                this.addUserPopup = false;
            },
        },
        data(){
            return {
                word:'',
                serchedUserList:[],

                page:1,
                pageList:[],
                totalCount:1,
                pageCount:10,

                addUserPopup:false
            }
        }
    }
</script>

<style scoped>

    .user-list-box {
        padding:20px;
        display: grid;
        grid-gap:20px;
        grid-template-columns: 1fr;
        grid-auto-rows: 50px;
    }

    .add-user-popup {
        position: absolute;
        top:0;
        left:0;
        width:100%;
        padding:20px;
        background-color: rgba(0,0,0,0.3);
        z-index: 10;
    }
</style>

