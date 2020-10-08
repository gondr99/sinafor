<template>
    <div class="participant-card m-3 p-3">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" :class="{active: page === 0}" @click="page = 0">{{trans('menu.participant')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" :class="{active: page === 1}" @click="page = 1">{{trans('menu.certification')}}</a>
            </li>
        </ul>
        <div class="page-info">
            <!-- page 0 start -->
            <div class="page" v-if="page === 0">
                <!--  form-start-->
                <div class="form">
                    <div class="form-header mt-4">
                        <div class="left-info p-5">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">{{trans('register.email')}}</label>
                                <div class="col-sm-9">
                                    <input type="email" readonly class="form-control" :value="user.email">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">{{trans('register.name')}}</label>
                                <div class="col-sm-9">
                                    <input type="text" readonly class="form-control" :value="user.name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">{{trans('register.phone')}}</label>
                                <div class="col-sm-9">
                                    <input type="text" readonly class="form-control" :value="user.phone">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label>{{trans('register.info')}}</label>
                                    <div>
                                        <textarea readonly class="form-control" :value="user.info"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="img-container rounded">
                            <img :src="`/images/profiles/${user.profile}`" alt="">
                        </div>
                    </div>

                </div>
                <!-- form-end -->
            </div>
            <!-- page 0 end -->

            <!-- page 1 start -->
            <div class="page" v-if="page === 1">
                <div class="form p-4" v-if="skillInfo !== null">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">{{trans('title.category_level1')}}</label>
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control" :value="skillInfo.level1.name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">{{trans('title.category_level2')}}</label>
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control" :value="skillInfo.level2.name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">{{trans('title.skill_title')}}</label>
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control" :value="skillInfo.name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">{{trans('title.state')}}</label>
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control" :value="skillInfo.state">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">{{trans('title.assigned_teacher')}}</label>
                        <div class="col-sm-9">
                            <select v-model.number="selectedExpert" class="form-control" v-if="skillInfo.expert_id === null">
                                <option value="-1">---</option>
                                <option :value="expert.id" :key="expert.id" v-for="expert in expertList">{{expert.name}}</option>
                            </select>
                            <input type="text" readonly class="form-control" v-else :value="skillInfo.expertName">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">{{trans('title.phase')}}</label>
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control" :value="phaseList[skillInfo.phase].name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">{{trans('title.status')}}</label>
                        <div class="col-sm-9">
                            <input type="text" readonly class="form-control" v-if="skillInfo.phase === 0" value="---">
                            <input type="text" readonly class="form-control" v-else :value="statusName[skillInfo.status]">
                        </div>
                    </div>
                    <div class="form-row mt-2 d-flex justify-content-center">
                        <button class="btn btn-outline-primary btn-lg" @click="saveData">{{trans('menu.save')}}</button>
                    </div>
                </div>
            </div>
            <!-- page 1 end -->
        </div>
    </div>
</template>

<script>
    export default {
        name: "ParticipantComponent",
        props: {
            user: Object,
        },
        mounted() {
            this.statusName = window.statusName;
            this.phaseList = window.phaseList;
            axios.get(`/manager/userSkillInfo/${this.user.id}/${this.user.skillId}`).then(res => {
                this.skillInfo = res.data;
            });
        },
        data() {
            return {
                page: 0,
                skillInfo: null,
                selectedExpert: -1,
                statusName: [],
                phaseList: []
            }
        },
        methods: {
            saveData() {
                axios.put('/manager/user', {userId: this.user.id, expertId: this.selectedExpert, skillId: this.user.skillId}).then(res => {
                    const data = res.data;
                    Swal.fire(data.msg);
                    this.skillInfo = data.skillInfo;
                });
            }
        },
        computed: {
            expertList() {
                if (this.skillInfo !== null) {
                    return this.skillInfo.expertList;
                } else {
                    return [];
                }
            }
        }

    }
</script>

<style scoped>
    .participant-card {
        background-color: #fff;
        border-radius: 10px;
    }

    .form-header {
        display: grid;
        grid-template-columns: 1fr 200px;
    }

    .img-container {
        width: 200px;
        height: 200px;
        overflow: hidden;
    }

    .img-container > img {
        width: 100%;
        height: auto;
        object-fit: cover;
    }
</style>
