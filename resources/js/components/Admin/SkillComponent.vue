<template>
    <div class="card">
        <div class="img-box">
            <img class="card-inner-image" :src="`/images/icons/${item.filename}`" alt="skill-image">
            <div class="card-manager-list">
                <span class="badge badge-danger" v-if="item.managerList.length == 0">{{trans('adminmenu.no_manager')}}</span>
                <span class="badge badge-primary p-1 mr-1" v-for="manager in item.managerList" :key="manager.id">{{manager.name}}</span>
            </div>
        </div>
        <div class="card-body">
            <h5 class="card-title">{{item.name}}</h5>
            <p class="card-text">{{item.description}}</p>

            <div class="item-menu" v-if="admin">
                <button class="btn btn-sm btn-outline-success" @click="openPopupWindow(0)">{{ trans('menu.manager') }}
                </button>
                <button class="btn btn-sm btn-outline-info" @click="openPopupWindow(1)">{{ trans('menu.set_expert') }}
                </button>
                <button class="btn btn-sm btn-outline-danger" @click="delSkill">{{ trans('menu.delete') }}(미구현)</button>
            </div>
            <div class="item-menu" v-if="!admin">
                <button v-if="item.status === 0" class="btn btn-sm btn-outline-success" @click="registerSkill">{{ trans('menu.skill_registration') }}</button>
                <button v-if="item.status !== 0" class="btn btn-sm btn-danger">{{ trans('menu.skill_regist_already') }}</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SkillComponent",
        props: ['item', 'admin'],
        data(){
            return {
                name
            }
        },
        methods: {
            async registerSkill() {
                const result = await this.checkSure();
                if (result.isConfirmed){
                    axios.put(`/skill/register/${this.item.id}`).then(res => {
                        Swal.fire(res.data.msg);
                    }).catch(err => {
                        console.log(err);
                    })
                }
            },
            delSkill() {
                //아마 안쓰일듯.
            },
            openPopupWindow(mode) {
                this.$parent.openPopupWindow(this.item.id, mode);
            },
            checkSure(){
                return Swal.fire({
                    title: this.trans('messages.skill_register'),
                    text: this.trans('messages.sure'),
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: this.trans('messages.yes'),
                    cancelButtonText: this.trans('messages.cancel')
                });
            }
        }
    }
</script>

<style>
    .card > .img-box {
        position: relative;
    }
    .card-inner-image {
        width: 100%;
        height: 100%;
        max-height: 200px;
        object-fit: cover;
        object-position: center center;
    }

    .card-manager-list {
        position: absolute;
        width:60%;
        right:10px;
        bottom:10px;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-gap:10px;
    }

</style>
