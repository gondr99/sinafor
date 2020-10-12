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
                                            <div class="tags" v-if="c.phase === 0">
                                                <span class="tag bg-dark text-white">{{trans('title.skill_status')}}</span>
                                                <span class="tag bg-danger text-white">{{ phaseList[0].name }}</span>
                                            </div>
                                            <div class="tags" v-else-if="c.phase >= 1">
                                                <span class="tag bg-dark text-white">{{phaseList[c.phase].name}}</span>
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
                                        <div class="phase-icon" :class="{active:viewSkill.phase === p.id, disable:viewSkill.phase < p.id}" v-for="p in phases" :key="p.id">
                                            <div class="icon">
                                                <i :class="p.icon"></i>
                                            </div>
                                            <div class="text">
                                                <span>{{ p.name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="status-indicator">
                                        <div class="tags" v-if="viewSkill.phase === 0">
                                            <span class="tag bg-dark text-white">{{trans('title.skill_status')}}</span>
                                            <span class="tag bg-danger text-white">{{ phaseList[0].name }}</span>
                                        </div>
                                        <div class="tags" v-else-if="viewSkill.phase >= 1">
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
                                        </div>

                                        <div class="title">
                                            <h4>{{viewSkill.name}}</h4>
                                        </div>

                                        <div class="detail">
                                            <p v-if="viewSkill.detail !== ''">{{viewSkill.detail}}</p>
                                            <p v-else>
                                                {{trans('messages.wait_for_match')}}
                                            </p>
                                        </div>

<!--                                        video upload available only phase 4-->
                                        <div class="video-upload" v-if="viewSkill.phase === 4">
                                            <div class="menu-bar text-right">
                                                <i class="far fa-plus-square text-danger" @click="addVideo" ></i>
                                            </div>
                                            <input type="file" class="hidden" ref="videoFile" accept="video/mp4,video/x-m4v,video/*" @change="uploadVideos">
                                            <div class="video-list">
                                                <div class="video-container" v-for="v in viewSkill.videoList" :key="v.id">
                                                    <div class="video-header bg-primary p-2">
                                                        <i class="far fa-window-close text-white" @click="deleteVideo(v.id)"></i>
                                                    </div>
                                                    <video controls :src="`/upload_video/${v.user_id}/${v.skill_category_id}/${v.filename}`"></video>
                                                </div>

                                            </div>
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
            this.phaseList = window.phaseList;
        },
        methods:{
            searchCertification(){

            },
            viewDetail(id){
                this.mode = 2;
                this.viewSkill = null;
                this.loadSkillDetail(id);
            },
            //비디오 업로드 버튼
            addVideo(){
                this.$refs.videoFile.click();
            },
            //실제 업로딩
            uploadVideos(){
                const file = this.$refs.videoFile.files[0];
                if(file.size > 50000000){  //more than 50Mbyte
                    Swal.fire(this.trans('messages.filesize_exceed'));
                    return;
                }

                let formData = new FormData();
                formData.append('file', file);
                formData.append('skillId', this.viewSkill.id);

                axios.post('/skill/video', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(res => {
                    this.loadSkillDetail(this.viewSkill.id);
                });
            },
            deleteVideo(id){
                axios.delete(`/skill/video/${id}`).then(res => {
                    this.loadSkillDetail(this.viewSkill.id);
                });
            },
            async loadSkillDetail(id){
                this.viewSkill = null;
                let skill = this.certificationList.find(x => x.id === id);
                // 만약 추가적으로 Detail에 관한 히스토리를 봐야한다면 이부분을 주석해제하고 변경해야 한다.
                // if(skill.phase > 0){
                //     let res = await axios.get(`/user/skill_detail/${skill.id}`);
                //     skill.detail = res.data.detail;
                // }
                if(skill.phase === 4){
                    let res = await axios.get(`/skill/video/${id}`);
                    skill.videoList = res.data;
                }
                this.viewSkill = skill;
            }
        },
        data(){
            return {
                phaseList:[],
                statusName:[],
                searchToggle:false,
                word:'',
                mode:0,
                certificationList:[],
                viewSkill:{}
            }
        },
        computed:{
            phases(){
                return this.phaseList.slice(1); //맨 처음 것은 applying이라 제거
            }
        }
    }
</script>

<style scoped>

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

    .hidden {
        display: none;
    }

    .video-upload > .menu-bar > i {
        font-size:25px;
        cursor: pointer;
    }

    .video-list {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-auto-rows: 150px;
        grid-gap:20px;
    }

    .video-container {
        display: flex;
        flex-direction: column;
        height:100%;
        width:100%;
        border-radius: 5px;
        overflow: hidden;
    }

    .video-container > .video-header {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        height:40px;
        font-size:25px;
    }

    .video-container > video {
        flex:1;
        width:100%;
        height:calc(100% - 40px);
    }
</style>
