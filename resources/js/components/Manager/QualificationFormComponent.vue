<template>
    <div class="qualification-form-card m-3 p-3">
        <h4>{{trans('title.qualification_form')}}</h4>
        <div class="form">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">{{trans('title.category_level1')}}</label>
                <div class="col-sm-6">
                    <select class="form-control" v-model="level1">
                        <option :value="level.id" v-for="level in levelList" :key="level.id">{{level.name}}</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">{{trans('title.category_level2')}}</label>
                <div class="col-sm-6">
                    <select class="form-control" v-model="level2">
                        <option :value="level.id" v-for="level in level2List" :key="level.id">{{level.name}}</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">{{trans('title.skill_title')}}</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" v-model="skillName">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">{{trans('title.skill_pdf')}}</label>
                <div class="col-sm-6">
                    <input type="file" class="form-control" @change="fileChange" ref="pdfFile" accept="application/pdf">
                </div>
                <div class="col-sm-3">
                    <button class="btn btn-outline-primary" @click="uploadFile">{{trans('menu.upload')}}</button>
                </div>
            </div>

            <div class="form-row row">
                <div class="col-9 offset-3 file-list">
                    <div class="pdf-file bg-success text-white p-2" v-for="f in pdfFileList">
                        <i class="fas fa-file-pdf icon"></i>
                        <a :href="`/file/pdfs/${f.filename}`" :download="f.original" class="filename" :title="f.original">{{f.original}}</a>
                        <div class="close-div d-flex justify-content-end" @click="deletePDF(f.id)">
                            <i class="far fa-window-close"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-row mt-3 d-flex justify-content-center">
                <button class="btn btn-outline-primary btn-lg" @click="saveData">{{trans('menu.save')}}</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "QualificationFormComponent",
        props: {
            'qualification': Object
        },
        mounted() {
            this.loadData();
        },
        data() {
            return {
                level1: -1,
                level2: -1,
                levelList: [],
                skillName: '',
                pdfFile: '',
                pdfFileList: [],
            }
        },
        computed: {
            level2List() {
                return this.level1 < 0 ? [] : this.levelList.find(x => x.id === this.level1).two;
            }
        },
        methods: {
            saveData() {
                axios.put(`/manager/skill/${this.qualification.id}`, {level2: this.level2, name: this.skillName}).then(res => {
                    let {msg, skill} = res.data;
                    Swal.fire(msg);
                    this.$emit('modify', skill);
                });
            },
            fileChange() {
                this.pdfFile = this.$refs.pdfFile.files[0];
            },
            uploadFile() {
                let formData = new FormData();
                formData.append('file', this.pdfFile);
                formData.append('skillId', this.qualification.id);

                axios.post('/manager/pdf', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(res => {
                    this.pdfFileList = res.data;
                });
            },
            loadData() {
                axios.get('/manager/level').then(res => {
                    this.levelList = res.data;
                    this.level1 = this.qualification.level1.id;
                    this.level2 = this.qualification.level2.id;
                    this.skillName = this.qualification.name;
                });

                axios.get(`/manager/pdf/${this.qualification.id}`).then(res => {
                    this.pdfFileList = res.data;
                });
            },
            deletePDF(id) {
                axios.delete(`/manager/pdf/${id}`).then(res => {
                    this.pdfFileList = res.data;
                })
            }
        }
    }
</script>

<style scoped>
    .qualification-form-card {
        background-color: #fff;
        border-radius: 10px;
    }

    .file-list {
        display: grid;
        grid-gap: 10px;
        grid-template-columns: repeat(4, 1fr);
    }

    .pdf-file {
        border-radius: 0.5rem;
        position: relative;
        width: 100%;
        height: 40px;
        display: grid;
        grid-template-columns: 25px 1fr 25px;
        grid-gap: 10px;
    }

    .pdf-file > .icon {
        font-size: 20px;
    }

    .pdf-file > .filename {
        font-size: 14px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        color: #fff;
    }

    .pdf-file > .close-div {
        font-size: 20px;
        cursor: pointer;
    }

</style>
