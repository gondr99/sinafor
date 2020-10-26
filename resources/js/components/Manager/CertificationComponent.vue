<template>

    <div class="certification-app">

        <div class="title row">
            <h4>{{trans('menu.certifications')}}</h4>
        </div>
        <!--    row-start-->
        <div class="row">
            <div class="col-12 p-2">
                <div class="form">
                    <div class="form-row">
                        <div class="col-3">
                            <input type="text" class="form-control" :placeholder="trans('placeholder.search')" v-model="word">
                        </div>
                        <div class="col-3">
                            <select class="form-control" v-model.number="status">
                                <option value="-1">---</option>
                                <option :value="idx" v-for="(name, idx) in statusName">{{name}}</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <select class="form-control" v-model.number="phase">
                                <option value="-1">---</option>
                                <option :value="p.id" v-for="(p, idx) in phaseList">{{p.name}}</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <button class="btn btn-outline-primary" @click="searchUser">{{trans('menu.search')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--    row-end -->

        <!--    row-start-->
        <div class="row">
            <div class="col-12 p-2">
                <table class="table table-striped">
                    <tr>
                        <th>ID</th>
                        <th>{{trans('title.name')}}</th>
                        <th>{{trans('title.skill_name')}}</th>
                        <th>{{trans('title.date_applied')}}</th>
                        <th>{{trans('title.phase')}}</th>
                        <th>{{trans('title.status')}}</th>
                        <th>{{trans('title.view_edit')}}</th>
                        <th>{{trans('menu.delete')}}</th>
                    </tr>
                    <tr v-for="user in userList">
                        <td>{{user.id}}</td>
                        <td>{{user.name}}</td>
                        <td>{{user.skillName}}</td>
                        <td>{{user.date}}</td>
                        <td v-if="user.phase >= 5">Complete</td>
                        <td v-else-if="user.phase > 0">{{user.phase}}</td>
                        <td v-else>{{trans('title.applying')}}</td>

                        <td v-if="user.phase > 0">{{user.status}}</td>
                        <td v-else>---</td>

                        <td><button class="btn btn-sm btn-primary" @click="openParticipantInfo(user.id, user.skillId)">{{trans('menu.go_to_record')}}</button></td>
                        <td><button class="btn btn-sm btn-danger" @click="delUserCertificate(user.id, user.skillId)">{{trans('menu.delete')}}</button></td>
                    </tr>
                </table>
            </div>
        </div>
        <!--    row-end -->

        <!--    pagination-start -->
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item" :class="{disabled: start === 1}">
                    <a class="page-link" tabindex="-1" > &lt;&lt; </a>
                </li>
                <li class="page-item" v-for="p in pageLink">
                    <a class="page-link">{{p}}</a>
                </li>

                <li class="page-item" :class="{disabled: end === totalPage}">
                    <a class="page-link"> &gt;&gt; </a>
                </li>
            </ul>
        </nav>
        <!--    pagination-end -->

        <transition name="fade">
            <div class="participant-window" v-if="isParticipantOpen">
                <div class="close-div" @click="closeParticipant">
                    <i class="far fa-window-close"></i>
                </div>
                <participant-component :user="viewParticipant"></participant-component>
            </div>
        </transition>
    </div>

</template>

<script>
    import ParticipantComponent from "./ParticipantComponent";
    export default {
        name: "CertificationComponent",
        components:{
            'participant-component':ParticipantComponent
        },
        mounted() {
            this.statusName = window.statusName;
            this.phaseList = window.phaseList;
            axios.get('/manager/user_list').then(res => {
                this.filterUserList = this.allUserList = res.data;
                this.totalPage = Math.ceil(this.userList.length / this.offset);
                this.makePagination();
            });
        },
        data(){
            return {
                statusName:[],
                phaseList:[],
                allUserList:[],
                filterUserList:[],
                page:1,
                pageLink:[],
                totalPage:1,
                start:0,
                end:0,
                offset:10,
                word:'',
                status:-1,
                phase:-1,
                isParticipantOpen:false,
                viewParticipant:null
            }
        },

        methods:{
            openParticipantInfo(userId, skillId){
                this.viewParticipant = this.allUserList.find(x => x.id === userId && x.skillId === skillId);
                this.isParticipantOpen = true;
            },
            closeParticipant(){
                this.viewParticipant = null;
                this.isParticipantOpen = false;
            },
            delUserCertificate(userId, skillId){
                alert("Not yet...sorry");
            },
            next(){
                if(this.end < this.totalPage ){
                    this.page = this.end + 1;
                    this.makePagination();
                }
            },
            prev(){
                if(this.start !== 1){
                    this.page = this.start - 1;
                    this.makePagination();
                }
            },
            makePagination(){
                this.pageLink = [];
                this.end = Math.ceil(this.page / this.offset) * this.offset;
                this.start = this.end - this.offset + 1;
                if(this.end > this.totalPage){
                    this.end = this.totalPage;
                }
                for(let i = this.start; i <= this.end; i++){
                    this.pageLink.push(i);
                }
            },
            searchUser(){
                let result = this.allUserList;
                if(this.word !== ""){
                    result = result.filter(x => x.name.includes(this.word));
                }
                if(this.status !== -1){
                    result = result.filter(x => x.status === this.status);
                }
                if(this.phase !== -1){
                    result = result.filter(x => x.phase === this.phase);
                }
                this.filterUserList = result;
            }
        },
        computed:{
            userList(){
                return this.filterUserList.slice( (this.page - 1) * this.offset , (this.page) * this.offset);
            }
        }
    }
</script>

<style scoped>
    .participant-window {
        position: absolute;
        z-index: 10;
        left:0;
        top:0;
        width:100%;
        min-height: 100%;
        background-color: rgba(0,0,0,0.4);
        padding:40px;
    }

    .close-div{
        position: absolute;
        right:20px;
        top:20px;
        font-size:25px;
        cursor:pointer;
        color:#fff;
    }
</style>
