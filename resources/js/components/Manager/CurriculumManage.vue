<template>
    <div>
        <div class="select-form">
            <h4>{{trans('title.select_manage_skill')}}</h4>
            <div class="form-row mt-2">
                <div class="col-6">
                    <select class="form-control" v-model="skillId">
                        <option v-for="skill in skillList" :value="skill.id">{{skill.name}}</option>
                    </select>
                </div>
                <div class="col-6">
                    <button class="btn btn-outline-success" @click="loadList">{{trans('menu.load')}}</button>
                </div>
            </div>
        </div>


        <div class="input-form my-3" v-if="formActive">
            <div class="form-row mb-2">
                <label class="col-2 col-form-label">{{trans('menu.exam_name')}}</label>
                <div class="col-6">
                    <input type="text" v-model="formData.title" class="form-control">
                </div>
            </div>

            <div class="form-row mb-2">
                <label class="col-2 col-form-label">{{trans('menu.exam_category')}}</label>
                <div class="col-6">
                    <div class="form-check form-check-inline" v-for="t in typeList">
                        <input class="form-check-input" type="radio" :value="t.id" v-model="formData.type">
                        <label class="form-check-label">
                            {{t.name}}
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-row mb-2">
                <div class="col-3">
                    <button class="btn btn-outline-info" @click="addExam">{{trans('menu.add_exam')}}</button>
                </div>
            </div>

        </div>

        <div class="curri-list mt-3" v-if="formActive">
            <exam-compo v-for="exam in examList" :key="exam.id" :exam="exam" @refreshlist="loadData"></exam-compo>
        </div>
    </div>
</template>

<script>
    import ExamComponent from "./ExamComponent";
    import {mapState} from 'vuex';

    export default {
        name: "CurriculumManage",
        components:{
            'exam-compo':ExamComponent
        },
        data(){
            return {
                skillId:0,
                typeList:[
                    {id:0, name:this.trans('menu.textarea')},
                    {id:1, name:this.trans('menu.file_upload')},
                    {id:2, name:this.trans('menu.video_upload')},
                ],
                formData:{
                    title:'',
                    type:0,
                    belongs:0
                },
                formActive:false,
                examList:[]
            }
        },
        methods:{
            loadList(){
                if(this.skillId === 0) return;
                this.formActive = true;
                this.formData.belongs = this.skillId;
                this.loadData();
            },
            loadData(){
                axios.get(`/manager/exam/${this.skillId}`).then(res => {
                    this.examList = res.data;
                });
            },
            addExam(){
                if(this.formData.name === ''){
                    Swal.fire(this.trans('messages.empty'));
                    return;
                }

                axios.post('/manager/exam', this.formData, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(res => {
                    this.examList = res.data;
                    this.formData.title = ""
                    this.formData.type = 0;
                }).catch( err => {
                    console.log(err);
                });
            }
        },
        computed: mapState({
            skillList: state => state.skillList,
        })
    }
</script>

<style scoped>
    .curri-list {
        border:1px solid #ddd;
        border-radius: 0.25rem;
        display: grid;
        grid-template-columns: 1fr;
        gap:10px;
        padding: 0.5rem;
    }

    .input-form {
        padding:0.6rem;
        border-radius: 0.25rem;
        border:1px solid #ddd;
    }
</style>
