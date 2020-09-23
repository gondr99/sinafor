<template>

    <div class="skill-card">
        <img class="skill-card-img" :src="`/images/icons/${skill.filename}`" alt="skill-image">
        <div>
            <h5 class="card-title">{{skill.name}} <span class="badge badge-danger" v-if="newCount !== 0">NEW!</span></h5>
            <p class="card-text">{{skill.description}}</p>
        </div>
        <div class="control mt-2">
            <button type="button" class="btn btn-primary mr-2" @click="showRegisterUser">
                {{trans('title.register_count')}} <span class="badge badge-light">{{registerCount}}</span>
            </button>

            <button type="button" class="btn btn-success mr-2" @click="showCertificatedUser">
                {{trans('title.certificated_count')}} <span class="badge badge-light">{{certificatedCount}}</span>
            </button>
        </div>
        <div class="user-box mt-2" v-if="showUserList">
            <learning-user v-for="user in userList" :key="user.id" :user="user" :skillId="skill.id"></learning-user>
        </div>
    </div>

</template>

<script>
    import LearningUser from "./LearningUser";

    export default {
        name: "ExpertSkill",
        components: {
            'learning-user': LearningUser,
        },
        props: {
            skill: Object
        },
        data() {
            return {
                showUserList: false,
                userList: [],
            }
        },
        mounted() {
            //not used anymore... T.T
            // axios.get(`/expert/skill/exam/${this.skill.id}`).then(res => {
            //     this.examList = res.data;
            // }).catch(err => console.log(err));
        },
        methods: {
            showRegisterUser() {
                this.showUserList = true;
                this.userList = this.skill.userList.filter(x => x.status <= 2);
            },
            showCertificatedUser() {
                this.showUserList = true;
                this.userList = this.skill.userList.filter(x => x.status === 3);
            }
        },
        computed: {
            newCount() {
                return this.userList.filter(x => x.status === 1).length;
            },
            registerCount() {
                return this.skill.userList.filter(x => x.status <= 2).length;
            },
            certificatedCount() {
                return this.skill.userList.filter(x => x.status === 3).length;
            }
        }
    }
</script>

<style scoped>
    .control {
        grid-column: 1/3;
        display: flex;
        justify-content: flex-end;
    }

    .user-box {
        grid-column: 1/3;
        border-top: 1px solid #ddd;
        display: grid;
        gap: 10px;
        grid-template-columns: 1fr;
    }
</style>
