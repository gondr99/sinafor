<template>
    <div class="skill-card">
        <img class="skill-card-img" :src="`/images/icons/${skill.filename}`" alt="skill-image">
        <div>
            <h5 class="card-title">{{skill.name}}</h5> {{trans('messages.registered_count')}}<span class="badge badge-primary">{{userCount}}</span>
            <p class="card-text">{{skill.description}}</p>
        </div>
        <div class="registerList">
            <h4>{{trans('title.register_user_list')}}</h4>
            <div class="user-list">
                <div class="card" v-if="userList.length === 0">
                    <div class="card-body">
                        {{trans('messages.list_is_empty')}}
                    </div>
                </div>
                <skill-user v-for="user in userList" :key="user.id" class="user" :user="user" :expert-list="expertList" :skill-id="skill.id"></skill-user>
            </div>
        </div>
    </div>
</template>

<script>
    import SkillUser from "./SkillUser";

    export default {
        name: "SkillRegister",
        components:{
            'skill-user':SkillUser
        },
        props:{
            'skill':Object
        },
        methods:{
            accept(){

            }
        },
        computed:{
            userCount(){
                return this.skill.registerUser.length;
            },
            userList(){
                return this.skill.registerUser.filter(x => x.status === 0);
            },
            expertList(){
                return this.skill.expertList;
            }
        }
    }
</script>

<style scoped>
    .registerList {
        grid-column: 1/3;
        padding:0.5rem;
    }

</style>
