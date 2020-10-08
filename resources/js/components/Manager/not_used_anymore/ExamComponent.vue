<template>
    <div class="exam">
        <div class="title">{{exam.title}}</div>
        <div class="type-div bg-primary text-white">{{typeName}}</div>
        <div class="btn-row">
            <button class="mr-2 btn btn-sm btn-outline-danger" @click="deleteExam" >{{trans('menu.delete')}}</button>
            <button class="mr-2 btn btn-sm btn-outline-primary" @click="up">{{trans('menu.up')}}</button>
            <button class="mr-2 btn btn-sm btn-outline-primary" @click="down">{{trans('menu.down')}}</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ExamComponent",
        props:{
            'exam':Object
        },
        data(){
            return {
                typeList:[
                    {id:0, name:this.trans('menu.textarea')},
                    {id:1, name:this.trans('menu.file_upload')},
                    {id:2, name:this.trans('menu.video_upload')},
                ],
            }
        },
        computed:{
            typeName(){
                return this.typeList[this.exam.type].name;
            }
        },
        methods:{
            deleteExam(){
                axios.delete(`/manager/exam/${this.exam.id}`).then(res => {
                    this.$emit("refreshlist"); //refresh examList;
                }).catch(err => {
                    console.log(err);
                });
            },
            up(){
                axios.put(`/manager/exam/up/${this.exam.id}`).then(res => {
                    this.$emit("refreshlist"); //refresh examList;
                }).catch(err => {
                    console.log(err);
                });
            },
            down(){
                axios.put(`/manager/exam/down/${this.exam.id}`).then(res => {
                    this.$emit("refreshlist"); //refresh examList;
                }).catch(err => {
                    console.log(err);
                });
            }
        }
    }
</script>

<style>
    .exam {
        display: flex;
        justify-content: space-between;
        padding:0.7rem;
        border:1px solid #ddd;
        border-radius: 0.25rem;
    }
    .type-div {
        padding:0.25rem 0.7rem;
        border-radius: 0.25rem;
    }
</style>
