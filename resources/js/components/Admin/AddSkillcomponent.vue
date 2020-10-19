<template>
    <div class="inner py-4">
        <div class="row">
            <div class="col-10 offset-1">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" :placeholder="trans('placeholder.add_skill_category')"
                           v-model="skillName">
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="uploadSpan">{{ trans('placeholder.upload_image_file') }}</span>
                    </div>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="iconFile" @change="changeFile" accept="image/*">
                        <label class="custom-file-label" for="iconFile">{{ imgText }}</label>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <textarea class="form-control" v-model="description" rows="3" :placeholder="trans('placeholder.description')"></textarea>
                </div>
                <div class="button-row text-right">
                    <button class="btn btn-primary" @click="add">{{ trans('menu.add') }}</button>
                    <button class="btn btn-danger" @click="cancel">{{ trans('menu.cancel') }}</button>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: "AddSkillcomponent",
        props:{
            "level2":Number
        },
        data(){
            return {
                skillName:'',
                file:null,
                description:'',
            }
        },
        methods:{
            changeFile(e){
                this.file = e.target.files[0];
            },
            cancel(){
                this.$emit("close");
            },
            add(){
                if(this.skillName.trim() === "" || this.file === "" || this.description.trim() === "" || this.level2 === undefined){
                    Swal.fire(this.trans('messages.require_empty'));
                    return;
                }

                let formData = new FormData();
                formData.append('level2', this.level2);
                formData.append('name', this.skillName);
                formData.append('image', this.file);
                formData.append('description', this.description);

                axios.post('/admin/skill', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(res => {
                    this.$emit('close', res.data);
                }).catch( err => {
                    Swal.fire(err.response.data);
                });
            }
        },
        computed:{
            imgText(){
                return this.file === null ? '' : this.file.name;
            }
        }
    }
</script>

<style scoped>
    .inner {
        background-color: #fff;
        border-radius: 10px;
        width:600px;
        height:300px;
    }
</style>
