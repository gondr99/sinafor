<template>
    <div class="certification-app">

        <div class="title row">
            <h4>{{trans('menu.qualifications')}}</h4>
        </div>
        <!--    row-start-->
        <div class="row">
            <div class="col-12 p-2">
                <div class="form">
                    <div class="form-row">
                        <div class="col-3">
                            <input type="text" class="form-control" :placeholder="trans('placeholder.search')" v-model="word">
                        </div>
                        <div class="col-3">
                            <select class="form-control" v-model.number="level1">
                                <option value="-1">---</option>
                                <option :value="level.id" v-for="level in level1List">{{level.name}}</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <select class="form-control" v-model.number="level2">
                                <option value="-1">---</option>
                                <option :value="level.id" :key="level.id" v-for="level in level2List">{{level.name}}</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <button class="btn btn-outline-primary" @click="searchQualification">{{trans('menu.search')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--    row-end -->

        <!--    row-start-->
        <div class="row">
            <div class="col-12 p-2">
                <table class="table table-striped">
                    <tr>
                        <th>ID</th>
                        <th>{{trans('title.category_level1')}}</th>
                        <th>{{trans('title.category_level2')}}</th>
                        <th>{{trans('title.skill_title')}}</th>
                        <th>{{trans('title.view_edit')}}</th>
                    </tr>
                    <tr v-for="q in qualificationList">
                        <td>{{q.id}}</td>
                        <td>{{q.level1.name}}</td>
                        <td>{{q.level2.name}}</td>
                        <td>{{q.name}}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" @click="openQualificationInfo(q.id)">{{trans('menu.go_to_record')}}</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <!--    row-end -->

        <!--    pagination-start -->
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item" :class="{disabled: start === 1}">
                    <a class="page-link" tabindex="-1"> &lt;&lt; </a>
                </li>
                <li class="page-item" v-for="p in pageLink">
                    <a class="page-link">{{p}}</a>
                </li>

                <li class="page-item" :class="{disabled: end === totalPage}">
                    <a class="page-link"> &gt;&gt; </a>
                </li>
            </ul>
        </nav>
        <!--    pagination-end -->

        <transition name="fade">
            <div class="qualification-window" v-if="isQualificationOpen">
                <div class="close-div" @click="closeQualification">
                    <i class="far fa-window-close"></i>
                </div>
                <qualification-form-component :qualification="viewQualification" @modify="onModify"></qualification-form-component>
            </div>
        </transition>
    </div>
</template>

<script>
    import QualificationFormComponent from "./QualificationFormComponent";

    export default {
        name: "QualificationComponent",
        components: {
            'qualification-form-component': QualificationFormComponent
        },
        mounted() {
            axios.get('/manager/skill').then(res => {
                this.filteredSkillList = this.allSkillList = res.data;
                this.allSkillList.forEach(x => {
                    if(this.level1List.find(level1 => level1.id === x.level1.id) === undefined){
                        this.level1List.push(x.level1);
                    }
                    if(this.level2List.find(level2 => level2.id === x.level2.id) === undefined){
                        this.level2List.push(x.level2);
                    }
                });
                this.makePagination();
            });
        },
        data() {
            return {
                word: '',
                level1: -1,
                level2: -1,
                level1List: [],
                level2List: [],
                allSkillList: [],
                filteredSkillList:[],
                page: 1,
                pageLink: [],
                totalPage: 1,
                start: 0,
                end: 0,
                offset: 10,
                isQualificationOpen: false,
                viewQualification: null,
            }
        },
        methods: {
            searchQualification() {
                let result = this.allSkillList;
                if(this.word !== ""){
                    result = result.filter(x => x.name.includes(this.word));
                }
                if(this.level1 !== -1){
                    result = result.filter(x => x.level1.id === this.level1);
                }
                if(this.level2 !== -1){
                    result = result.filter(x => x.level2.id === this.level2);
                }
                this.filteredSkillList = result;
            },
            openQualificationInfo(skillId) {
                this.viewQualification = this.allSkillList.find(x => x.id === skillId);
                this.isQualificationOpen = true;
            },
            closeQualification() {
                this.viewQualification = null;
                this.isQualificationOpen = false;
            },
            onModify(payload){
                let idx = this.allSkillList.findIndex(x => x.id === payload.id);
                this.allSkillList.splice(idx, 1, payload);
            },
            next() {
                if (this.end < this.totalPage) {
                    this.page = this.end + 1;
                    this.makePagination();
                }
            },
            prev() {
                if (this.start !== 1) {
                    this.page = this.start - 1;
                    this.makePagination();
                }
            },
            makePagination() {
                this.pageLink = [];
                this.end = Math.ceil(this.page / this.offset) * this.offset;
                this.start = this.end - this.offset + 1;
                if (this.end > this.totalPage) {
                    this.end = this.totalPage;
                }
                for (let i = this.start; i <= this.end; i++) {
                    this.pageLink.push(i);
                }
            },

        },
        computed: {
            qualificationList() {
                return this.filteredSkillList.slice( (this.page - 1) * this.offset , (this.page) * this.offset);
            }
        }
    }
</script>

<style scoped>
    .qualification-window {
        position: absolute;
        z-index: 10;
        left: 0;
        top: 0;
        width: 100%;
        min-height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
        padding: 40px;
    }

    .close-div {
        position: absolute;
        right: 20px;
        top: 20px;
        font-size: 25px;
        cursor: pointer;
        color: #fff;
    }
</style>
