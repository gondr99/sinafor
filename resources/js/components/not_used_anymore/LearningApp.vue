<template>
    <div class="learning-app">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center align-items-center skill-img-box" :style="`background-image: url('/images/icons/${skill.filename}')`">
                    <div class="title-box">
                        <h4 class="title">{{skill.name}}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 one-grid">

                <div class="card" v-for="(exam, idx) in examList">
                    <div class="card-header">
                        {{exam.title}} <span class="badge badge-primary">{{ typeList[exam.type].name }}</span>
                        <span v-if="exam.pass === 1" class="badge badge-primary">{{trans('title.subject_complete')}}</span>
                        <span v-if="isProgress(idx)" class="badge badge-warning">{{trans('title.subject_progress')}}</span>
                    </div>
                    <div class="card-body">
                        <div v-if="exam.subjectList.length === 0" class="msg-box">{{trans('messages.list_is_empty')}}</div>
                        <subject-component v-for="subject in exam.subjectList" :subject="subject" :key="subject.id"></subject-component>
                        <button v-if="isProgress(idx)" class="btn btn-outline-primary btn-sm">{{trans('menu.send_subject')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SubjectComponent from "../Learning/SubjectComponent";
    export default {
        name: "LearningApp",
        components:{
            'subject-component':SubjectComponent
        },
        props:{
            skill:Object,
            examList:Array
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
        methods:{
            isProgress(idx){
                const el = this.examList;
                if( el[idx].pass === 1) return false;
                return idx === 0 || el[idx-1].pass === 1;
            }
        }
    }
</script>

<style scoped>

</style>
