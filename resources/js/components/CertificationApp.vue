<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 p-0">
                <header-component :title="trans('title.my_certification')" @toggle="searchToggle = !searchToggle"></header-component>
            </div>
        </div>
        <!-- row start-->
        <div class="row">
            <div class="col-12 p-0">
                <!-- card start-->
                <div class="card">
                    <div class="card-body">
                        <transition name="fade">
                            <div class="input-group mb-3" v-if="searchToggle">
                                <input type="text" class="form-control" :placeholder="trans('title.find_my_qualification')" id="searchInput" v-model="word">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" @click="searchCertification">{{trans('menu.search')}}</button>
                                </div>
                            </div>
                        </transition>
                        <!-- content-panel start-->
                        <div class="content-panel">

                            <div class="certification-list" v-if="mode === 0">
                                <div class="certification-box" v-for="c in certificationList">
                                    <div class="skill-content p-3">
                                        <div class="info-bar">
                                            <div class="tags">
                                                <span class="tag bg-dark text-white">{{trans('title.date_applied')}}</span>
                                                <span class="tag bg-primary text-white">{{c.created_at.substr(0, 10)}}</span>
                                            </div>
                                            <div class="tags">
                                                <span class="tag bg-dark text-white">{{trans('title.skill_status')}}</span>
                                                <span class="tag bg-danger text-white">{{ statusName[c.status] }}</span>
                                            </div>
                                        </div>
                                        <div class="title">
                                            <h4>{{c.name}}</h4>
                                        </div>
                                        <div class="desc">
                                            <p>{{c.description}}</p>
                                        </div>
                                        <div class="button-row">
                                            <button class="btn btn-outline-primary" @click="viewDetail(c.id)">{{trans('menu.view_detail')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="searched-list" v-else-if="mode === 1">

                            </div>
                            <div class="view-info" v-else-if="mode === 2">
                                <div class="loading" v-if="viewSkill === null">
                                    <div class="spin-container"><i class="fas fa-spinner"></i></div>
                                </div>

                                <div class="skill-content" v-if="viewSkill !== null">
                                    <div class="phase-indicator">
                                        <div class="phase-icon" :class="{active:viewSkill.detail !== undefined && viewSkill.detail.phase === p.id, disable:viewSkill.detail !== undefined && viewSkill.detail.phase < p.id}" v-for="p in phaseList" :key="p.id">
                                            <div class="icon">
                                                <i :class="p.icon"></i>
                                            </div>
                                            <div class="text">
                                                <span>{{ p.name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="status-indicator">
                                        <div class="tags">
                                            <span class="tag bg-dark text-white">{{trans('title.skill_status')}}</span>
                                            <span class="tag bg-danger text-white">{{ statusName[viewSkill.status] }}</span>
                                        </div>
                                    </div>

                                    <div class="detail-info">
                                        <div class="d-flex justify-content-between">
                                            <div class="tags">
                                                <span class="tag bg-dark text-white">{{trans('title.date_applied')}}</span>
                                                <span class="tag bg-primary text-white">{{viewSkill.created_at.substr(0, 10)}}</span>
                                            </div>

                                            <div class="tags" v-if="viewSkill.detail !== undefined">
                                                <span class="tag bg-dark text-white">{{trans('title.date_applied')}}</span>
                                                <span class="tag bg-danger text-white">{{statusName[viewSkill.detail.status]}}</span>
                                            </div>
                                        </div>

                                        <div class="title">
                                            <h4>{{viewSkill.name}}</h4>
                                        </div>

                                        <div class="detail">
                                            <p v-if="viewSkill.detail !== undefined">{{viewSkill.detail.detail}}</p>
                                            <p v-else>
                                                {{trans('messages.wait_for_match')}}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-lg btn-primary">{{trans('menu.get_assistance')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- content-panel end-->
                    </div>
                </div>
                <!-- card end-->
            </div>
        </div>
        <!-- row end-->
    </div>
</template>

<script>
    import HeaderComponent from "./HeaderComponent";

    export default {
        name: "CertificationApp",
        components:{
            'header-component':HeaderComponent,
        },
        mounted() {
            axios.get('/skill/register/list').then( res => {
                this.certificationList = res.data;
            });
            this.statusName = window.statusName;
        },
        methods:{
            searchCertification(){

            },
            viewDetail(id){
                this.mode = 2;
                this.viewSkill = null;
                this.loadSkillDetail(id);
            },

            async loadSkillDetail(id){

                let skill = this.certificationList.find(x => x.id === id);
                //전문가와 매칭이 완료된 상태라면 세부데이터도 가져온다.
                if(skill.status > 0){
                    skill.detail = await axios.get(`/user/skill_detail/${skill.id}`);
                }
                this.viewSkill = skill;
            }
        },
        data(){
            return {
                phaseList:[
                    {id:1, name:'Phase1', icon:'far fa-id-card'},
                    {id:2, name:'Phase2', icon:'far fa-handshake'},
                    {id:3, name:'Phase3', icon:'far fa-file-word'},
                    {id:4, name:'Phase4', icon:'far fa-file-video'},
                ],
                statusName:['Applying', 'Phase1', 'Phase2', 'Phase3', 'Phase4', 'Wait for Expert'],
                searchToggle:false,
                word:'',
                mode:0,
                certificationList:[],
                viewSkill:{}
            }
        }
    }
</script>

<style scoped>
    .certification-list {
        display: grid;
        grid-template-columns: 1fr;
        gap:20px;
    }
    .certification-box {
        border-radius: 0.75rem;
        box-shadow: 2px 2px 2px 2px rgba(0,0,0, 0.2);
        display: grid;
        grid-template-columns: 1fr;
    }


    .skill-content {
        display: grid;
        grid-template-columns: 1fr;
        grid-gap:20px;
    }

    .info-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .button-row {
        display: flex;
        justify-content: center;
    }
    .phase-indicator {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-gap:20px;
    }

    .phase-icon {
        display: grid;
        grid-template-columns: 1fr;
        grid-auto-rows: 60px 30px;
    }

    .phase-icon > div {
        width:100%;
        height:100%;
        display: flex;
        justify-content: center;
    }
    .phase-icon > .icon > i{
        font-size:40px;
    }

    .phase-icon.disable {
        color:#ddd;
    }

    .phase-icon.active {
        color:#3f9ae5;
    }

    .detail-info {
        display: grid;
        grid-template-columns: 1fr;
        grid-gap:20px;
    }
</style>
