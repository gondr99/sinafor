<template>
    <div class="desktop-box">
        <div class="row">
            <div class="col-2">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link" :class="{active:menuIndex == 0}" @click="menuIndex = 0">{{  trans('menu.certifications') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{active:menuIndex == 1}" @click="menuIndex = 1">{{  trans('menu.qualifications') }}</a>
                    </li>
                </ul>
            </div>
            <div class="col-10">
                <div class="box p-2">
                    <div class="loading" v-if="menuIndex === -1">
                        <div class="spin-container"><i class="fas fa-spinner"></i></div>
                    </div>
                    <certification-component v-else-if="menuIndex === 0"></certification-component>
                    <qualification-component v-else-if="menuIndex === 1"></qualification-component>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import CertificationComponent from "./Manager/CertificationComponent";
    import QualificationComponent from "./Manager/QualificationComponent";
    export default {
        name: "ManagerApp",
        mounted() {
            axios.get('/manager/skill').then(res => {
               this.$store.commit('refreshSkillList', res.data);
               this.menuIndex = 0;
            });
        },
        components:{
            'certification-component':CertificationComponent,
            'qualification-component':QualificationComponent,
        },
        data(){
            return {
                menuIndex:-1
            }
        }
    }
</script>

<style scoped>

</style>
