<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 p-0">
                <header-component :title="trans('title.certification_requests')" @toggle="searchToggle = !searchToggle"></header-component>
            </div>
        </div>

        <div class="row">
            <div class="col-12 p-0">
                <!-- card start-->
                <div class="card">
                    <div class="card-body">
                        <transition name="fade">
                            <div class="input-group mb-3" v-if="searchToggle">
                                <input type="text" class="form-control" :placeholder="trans('placeholder.find_certification')" id="searchInput" v-model="word">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" @click="searchCertification">{{trans('menu.search')}}</button>
                                </div>
                            </div>
                        </transition>

                        <!-- content-panel start-->
                        <div class="content-panel">
                            <!-- certification-list start-->
                            <div class="certification-list" v-if="mode === 0">
                                <div class="certification-box" v-for="c in certificationList" :key="generateKey(c.id, c.skill.id)">
                                    <div class="skill-content p-3">
                                        <div class="info-bar">
                                            <div class="tags">
                                                <span class="tag bg-dark text-white">{{trans('title.date_applied')}}</span>
                                                <span class="tag bg-primary text-white">{{c.date.substr(0, 10)}}</span>
                                            </div>
                                            <div class="tags" v-if="c.phase == 0">
                                                <span class="tag bg-dark text-white">{{trans('title.skill_status')}}</span>
                                                <span class="tag bg-danger text-white">{{ phaseList[0].name }}</span>
                                            </div>
                                            <div class="tags" v-else>
                                                <span class="tag bg-dark text-white">{{phaseList[c.phase].name }}</span>
                                                <span class="tag bg-danger text-white">{{ statusName[c.status] }}</span>
                                            </div>
                                        </div>
                                        <div class="title my-4">
                                            <h4>{{c.skill.name}}</h4>
                                            <p>{{trans('title.participant_name')}} : <span>{{c.name}}</span></p>
                                        </div>
                                        <div class="button-row text-center">
                                            <button class="btn btn-outline-primary" @click="viewDetail(c.id, c.skill.id)">{{trans('menu.view_detail')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- certification-list end-->

                            <!-- detail page start -->
                            <div class="detail-page" v-if="mode >= 1 && viewCertification !== null">
                                <div class="profile">
                                    <div class="img-container">
                                        <img :src="`/images/profiles/${viewCertification.profile}`" alt="">
                                    </div>
                                    <div class="info">
                                        <h4>{{ viewCertification.name }}</h4>
                                        <p>{{ viewCertification.email }}</p>
                                    </div>
                                </div>

                                <div class="phase-indicator">
                                    <div class="phase-icon" :class="{active:viewCertification.phase === p.id, disable:viewCertification.phase < p.id}"
                                         v-for="p in phases" :key="p.id">
                                        <div class="icon">
                                            <i :class="p.icon"></i>
                                        </div>
                                        <div class="text">
                                            <span>{{ p.name }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- content mode 1 start-->
                                <div class="content" v-if="mode === 1">
                                    <div class="content-header d-flex justify-content-between">
                                        <div class="tags">
                                            <span class="tag bg-dark text-white">{{trans('title.date_applied')}}</span>
                                            <span class="tag bg-primary text-white">{{viewCertification.date.substr(0, 10)}}</span>
                                        </div>
                                        <div class="tags" v-if="viewCertification.phase === 0">
                                            <span class="tag bg-dark text-white">{{trans('title.skill_status')}}</span>
                                            <span class="tag bg-danger text-white">{{ phaseList[0].name }}</span>
                                        </div>
                                        <div class="tags" v-else>
                                            <span class="tag bg-dark text-white">{{phaseList[viewCertification.phase].name }}</span>
                                            <span class="tag bg-danger text-white">{{ statusName[viewCertification.status] }}</span>
                                        </div>
                                    </div>
                                    <div class="content-body">
                                        <h4>{{viewCertification.skill.name}}</h4>
                                        <p>{{trans('title.details')}}</p>
                                        <div class="accept-apply text-center" v-if="viewCertification.phase === 0">
                                            <button class="btn btn-primary" @click="acceptApply">{{trans('menu.accept_apply')}}</button>
                                        </div>
                                        <div class="detail-info" v-else>
                                            <div class="detail">
                                                <p v-if="viewCertification.detail !== ''">
                                                    {{viewCertification.detail}}
                                                </p>
                                            </div>
                                            <div class="button-row text-center">
                                                <button class="btn btn-primary" @click="updatePage">{{trans('menu.update_phase')}}</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- content mode 1 end-->

                                <!-- content mode 2 start-->
                                <div class="content" v-if="mode === 2">
                                    <div class="content-header d-flex justify-content-between">
                                        <div class="tags">
                                            <span class="tag bg-dark text-white">{{trans('title.date_applied')}}</span>
                                            <span class="tag bg-primary text-white">{{viewCertification.date.substr(0, 10)}}</span>
                                        </div>
                                        <div class="tags" v-if="viewCertification.phase === 0">
                                            <span class="tag bg-dark text-white">{{trans('title.skill_status')}}</span>
                                            <span class="tag bg-danger text-white">{{ phaseList[0].name }}</span>
                                        </div>
                                        <div class="tags" v-else>
                                            <span class="tag bg-dark text-white">{{phaseList[viewCertification.phase].name }}</span>
                                            <span class="tag bg-danger text-white">{{ statusName[viewCertification.status] }}</span>
                                        </div>
                                    </div>
                                    <div class="content-body">
                                        <h4>{{viewCertification.skill.name}}</h4>

                                        <p>{{trans('title.upload_video_list')}}</p>
                                        <div class="video-list my-4" v-if="viewCertification.phase === 4">
                                            <div class="video-container">
                                                <video controls :src="`/upload_video/${v.user_id}/${v.skill_category_id}/${v.filename}`" v-for="v in viewCertification.videoList"></video>
                                            </div>
                                        </div>

                                        <p>{{trans('title.details')}} : </p>
                                        <textarea class="form-control" rows="6" v-model="detailInfo"></textarea>
                                        <div class="accept-apply text-center" v-if="viewCertification.phase === 0">
                                            <button class="btn btn-primary" @click="acceptApply">{{trans('menu.accept_apply')}}</button>
                                        </div>

                                        <div class="form-group row my-2">
                                            <div class="col-4 col-form-label">{{trans('title.status')}}</div>
                                            <div class="col-8">
                                                <select v-model="detailStatus" class="form-control">
                                                    <option :value="idx" v-for="(s, idx) in statusName">{{s}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="button-row text-center">
                                            <button class="btn btn-primary" @click="updateDetail">{{trans('menu.update_phase')}}</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- content mode 2 end-->

                            </div>
                            <!-- detail page end -->
                        </div>
                        <!-- content-panel end-->
                    </div>
                </div>
            </div>
            <!-- card end-->
        </div>

    </div>


</template>

<script>
    import HeaderComponent from "./HeaderComponent";

    export default {
        name: "ExpertApp",
        components: {
            'header-component': HeaderComponent
        },
        mounted() {
            axios.get('/expert/certificate').then(res => {
                this.filteredCertificationList = this.originList = res.data;
            });
            //페이즈 정보를 전역변수로부터 불러온다.
            this.statusName = window.statusName;
            this.phaseList = window.phaseList;
        },
        data() {
            return {
                searchToggle: false,
                word: '',
                mode: 0,
                originList: [],
                filteredCertificationList: [],
                statusName: [],
                phaseList: [],
                viewCertification: null,
                detailInfo: '',
                detailStatus: 0
            }
        },
        methods: {
            viewDetail(userId, skillId) {
                this.mode = 1;
                this.viewCertification = null;
                this.loadCertificationDetail(userId, skillId);
            },
            async loadCertificationDetail(userId, skillId) {
                const certification = this.originList.find(x => x.id === userId && x.skill.id === skillId);

                if(certification.phase === 4){
                    let res = await axios.get(`/expert/video/${userId}/${skillId}`);
                    certification.videoList = res.data;
                }
                this.viewCertification = certification;
            },
            searchCertification() {
                if (this.word !== "") {
                    this.filteredCertificationList = this.originList.filter(x => x.name.includes(this.word) || x.skill.name.includes(this.word));
                } else {
                    this.filteredCertificationList = this.originList;
                }
            },
            //신청을 수락함.
            acceptApply() {
                axios.put('/expert/confirm', {userId: this.viewCertification.id, skillId: this.viewCertification.skill.id}).then(res => {
                    this.filteredCertificationList = this.originList = res.data;
                    this.viewCertification = this.originList.find(x => x.id === this.viewCertification.id && x.skill.id === this.viewCertification.skill.id);
                });
            },
            //업데이트 페이지로 이동하기.
            updatePage() {
                this.mode = 2;
            },
            updateDetail() {
                if (this.detailInfo === "") {
                    Swal.fire(this.trans('messages.empty'));
                    return;
                }
                axios.post('/expert/update_detail', {
                    userId: this.viewCertification.id,
                    skillId: this.viewCertification.skill.id,
                    detail: this.detailInfo,
                    status: this.detailStatus
                }, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(res => {
                    this.filteredCertificationList = this.originList = res.data;
                    this.viewCertification = this.originList.find(x => x.id === this.viewCertification.id && x.skill.id === this.viewCertification.skill.id);
                    this.mode = 1;
                });

            },
            //스킬아이디와 유저아이디를 합쳐서 고유키 만들기
            generateKey(userId, skillId) {
                return `${userId}-${skillId}`;
            }
        },
        computed: {
            certificationList() {
                return this.filteredCertificationList;
            },
            phases() {
                return this.phaseList.slice(1);
            }
        }
    }
</script>

<style scoped>
    .info-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .detail-page {
        display: grid;
        grid-template-columns: 1fr;
        grid-gap: 20px;
    }

    .profile {
        display: grid;
        grid-template-columns: 1fr 3fr;
        grid-template-rows: 140px;
        grid-gap: 10px;
    }

    .img-container {
        width: 100%;
        height: 100%;
    }

    .img-container > img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .phase-indicator {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-gap: 20px;
    }

    .phase-icon {
        display: grid;
        grid-template-columns: 1fr;
        grid-auto-rows: 60px 30px;
    }

    .phase-icon > div {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
    }

    .phase-icon > .icon > i {
        font-size: 40px;
    }

    .phase-icon.disable {
        color: #ddd;
    }

    .phase-icon.active {
        color: #3f9ae5;
    }

    .video-list {
        display: grid;
        grid-template-columns: 1fr;
        grid-auto-rows: 200px;
        grid-gap: 20px;
    }

    .video-container > video {
        width: 100%;
        height: 100%;
    }

</style>
