<template>
    <div class="role-component">
        <div>
            <div class="title bg-primary text-white d-flex justify-content-center align-items-center">{{trans('adminmenu.expert_list')}}</div>
            <div class="user-list p-2">
                <div class="user" :class="{active: selectUser !== null && selectUser.id === u.id}" v-for="u in expertList" @click="selectUser = u">{{u.name}}</div>
            </div>
        </div>
        <div>
            <div class="title px-2 bg-primary text-white d-flex justify-content-between align-items-center">
                {{trans('adminmenu.owned_skill_list')}}
                <button class="btn btn-sm btn-danger" v-if="selectUser !== null" @click="removeAll">{{trans('adminmenu.remove_all')}}</button>
            </div>
            <div class="skill-list p-3">
                <div class="skill d-flex justify-content-between px-2 align-items-center" v-for="s in ownedSkillList">
                    <span>{{s.name}}</span>
                    <button class="btn btn-sm btn-danger" @click="removeSkill(s.id)"><i class="far fa-minus-square"></i></button>
                </div>
            </div>

        </div>
        <div>
            <div class="title px-2 bg-primary text-white d-flex justify-content-between align-items-center">
                {{trans('adminmenu.skill_list')}}
                <button class="btn btn-sm btn-success" v-if="selectUser !== null" @click="addAll">{{trans('adminmenu.add_all')}}</button>
            </div>
            <div class="skill-list p-3">
                <div class="skill d-flex justify-content-between px-2 align-items-center" v-for="s in otherSkillList">
                    <span>{{s.name}}</span>
                    <button class="btn btn-sm btn-success" @click="addSkill(s.id)"><i class="fas fa-plus-square"></i></button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ExpertRoleComponent",
        props:{
            skillList:Array
        },
        mounted() {
            axios.get('/admin/expertList').then(res => this.expertList = res.data);
        },
        data(){
            return {
                selectUser:null,
                expertList:[]
            }
        },
        methods:{
            addSkill(skillId){
                axios.put(`/admin/skill/expert/${skillId}`,{ user_id:this.selectUser.id }).then(res => {
                    this.$emit("refresh");
                });
            },
            removeSkill(skillId){
                axios.delete(`/admin/skill/expert/${skillId}?user_id=${this.selectUser.id}`).then(res => {
                    this.$emit("refresh");
                });
            },
            addAll(){
                axios.put(`/admin/skill/expert/all`,{ user_id:this.selectUser.id }).then(res => {
                    this.$emit("refresh");
                });
            },
            removeAll(){
                axios.delete(`/admin/skill/expert/all?user_id=${this.selectUser.id}`).then(res => {
                    this.$emit("refresh");
                });
            }
        },
        computed:{
            ownedSkillList(){
                if(this.selectUser === null) return [];
                return this.skillList.filter(s => s.expertList.find(u => u.id === this.selectUser.id) !== undefined);
            },
            otherSkillList(){
                if(this.selectUser === null) return [];
                return this.skillList.filter(s => s.expertList.find(u => u.id === this.selectUser.id) === undefined);
            }
        }
    }
</script>

<style scoped>
    .role-component {
        display: grid;
        grid-template-columns: 200px 1fr 1fr;
        gap:10px;
    }

    .skill-list {
        border:1px solid #ddd;
        border-radius: 0 0 5px 5px;
        border-top:0;
        display: grid;
        gap:10px;
        grid-template-columns: 1fr;
    }

    .skill {
        border:1px solid #ddd;
        border-radius: 0.25rem;
        height:50px;
    }

    .user-list {
        display: grid;
        grid-template-columns: 1fr;
        gap:10px;
        border:1px solid #ddd;
        border-radius: 0 0 5px 5px;
        border-top:0;
    }

    .title {
        position: relative;
        height:40px;
    }

    .title > .btn {
        font-size:10px;
    }
    .user {
        border-radius: 0.25px;
        border:1px solid #ddd;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .user.active {
        background-color: #3352ff;
        color:#fff;
    }

</style>
