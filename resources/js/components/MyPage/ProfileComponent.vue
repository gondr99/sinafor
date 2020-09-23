<template>
    <div class="profile-box">
        <div class="card-box">
            <div class="header-box">
                <div class="profile-img">
                    <img :src="profileSrc" alt="profile">
                </div>
                <div class="info">
                    <div>{{user.name}}</div>
                    <div>{{user.email}}</div>
                    <div>{{user.phone}}</div>
                </div>
            </div>
        </div>
        <div class="body-box card-box">
            <div class="card-box skill-box">
                <div class="title">
                    <h4>{{trans('title.register_skill_list')}}</h4>
                </div>
                <div class="content">
                    <span class="badge badge-primary mr-2" v-for="s in registedSkill" :key="s.id">{{s.name}}</span>
                </div>
            </div>
            <div class="card-box">
                <div class="title">
                    <h4>{{trans('title.confirm_skill_list')}}</h4>
                </div>
                <div class="content">
                    <span class="badge badge-primary mr-2" v-for="s in confirmSkill" :key="s.id">{{s.name}}</span>
                </div>
            </div>
            <div class="card-box">
                <div class="title">
                    <h4>{{trans('title.certificated_skill_list')}}</h4>
                </div>
                <div class="content">
                    <span class="badge badge-primary mr-2" v-for="s in certificateSkill" :key="s.id">{{s.name}}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        name: "ProfileComponent",
        computed:mapState({
            user:state => state.user,
            profileSrc(state){
                return state.user.profile === undefined ? '' : `/images/profiles/${state.user.profile}`
            },
            registedSkill: state => state.user.skillList.filter(x => x.status === 1),
            confirmSkill: state => state.user.skillList.filter(x => x.status === 2),
            certificateSkill: state => state.user.skillList.filter(x => x.status === 3),

        })
    }
</script>

<style scoped>
    .card-box {
        border:1px solid #ddd;
        padding:5px;
        border-radius: 0.25rem;
    }

    .header-box {
        display: grid;
        grid-template-columns: 2fr 3fr;
    }

    .profile-img > img{
        width:100%;
        height:auto;
    }

    .card-box .title > h4{
        font-size:18px;
        margin-bottom:0.7rem;
    }

    .skill-box{
        padding:8px;
    }
    .card-box .content {
        padding:8px 12px;
    }
</style>
