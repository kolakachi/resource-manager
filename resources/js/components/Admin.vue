<template>
    <div class="container">
        <h3 class="mb-5 font-weight-light text-uppercase">Resource Management Page</h3>
        <div class="row">
            <div class="card col-sm-2 mb-2" data-bs-toggle="modal" data-bs-target="#addModal">
                <div class="card-body admin-add-card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M24 10h-10v-10h-4v10h-10v4h10v10h4v-10h10z"/></svg>
                </div>
            </div>
            <div class="card col-sm-2 mb-2 " v-for="(res, index) in resources" :key="index">
                <div class="card-body">
                    <h5 class="card-title">{{ res.title }}</h5>
                    <p class="card-text">Type: <span class="ml-4">{{ res.type }}</span></p>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" @click="setResourceEdit(index)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                            <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
                        </svg>
                    </button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" @click="setResourceEdit(index)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Resource</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form @submit.prevent="storeResource" autocomplete="off">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Enter resource title</label>
                                <input type="text" class="form-control" v-model="resource.title" required>
                            </div>
                            <div class="form-group mt-2">
                                <label>Select resource type</label>
                                <select class="form-control" v-model="resource.type" required>
                                    <option value="link">Link</option>
                                    <option value="html">HTML snippet</option>
                                    <option value="pdf">PDF download</option>
                                </select>
                            </div>
                            <div class="form-group mt-2" v-if="resource.type == 'link'">
                                <div class="form-group">
                                    <label>Enter resource link</label>
                                    <input type="url" class="form-control" v-model="resource.link" required>
                                </div>
                                <div class="form-check form-switch mt-2" title='Toggle'>
                                    <input type="checkbox" class="form-check-input" id="switch" v-model="resource.open_in_new_tab" />
                                    <label class="form-check-label" for='switch'>Open in a new tab</label>
                                </div>
                            </div>
                            <div class="form-group mt-2" v-show="resource.type == 'html'">
                                <div class="form-group">
                                    <label>Enter resource description</label>
                                    <textarea class="form-control" id="" cols="30" rows="5" v-model="resource.description"></textarea>
                                </div>
                                <div class="form-group mt-2">
                                    <label>Enter resource HTML</label>
                                    <textarea name="" id="codeArea" cols="30" rows="10"></textarea>
                                </div>
                            </div>

                            <div class="form-group mt-2" v-show="resource.type == 'pdf'">
                                <input type="file" name="" id="" accept="application/pdf" @change="setMediaUpload($event)" v-if="resource.file_name == '' || resource.file_name == null">
                                <ul class="list-group mt-2" v-if="resource.file_name != '' && resource.file_name != null">
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">{{ resource.file_name }}</div>
                                        </div>
                                        <span class="badge bg-primary rounded-pill" id="file-upload" @click="changeFile()">remove</span>
                                    </li>
                                </ul>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" :disabled="isLoading">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade new-user-modal" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="auduienceModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" >
            <div class="modal-content" id="delete-content" style="max-height: 150px">
                <div class="pr-2 pt-2">
                
                </div>
                <div class="modal-body text-center" style="height: 150px">
                    <h4 class="font-weight-bold mb-3">Remove resource</h4>
                    <p class="m-1">This action cannot be undone</p>
                    <div class="mt-4">
                        <button type="button" data-bs-dismiss="modal" class="btn px-3 mr-2 btn-primary btn-sm">Cancel</button>
                        <button type="button" class="btn px-3 btn-danger btn-sm" @click="deleteResource()">Remove</button>
                    </div>

                </div>
            </div>
            </div>
        </div>

        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Resource</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form @submit.prevent="updateResource" autocomplete="off">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Enter resource title</label>
                                <input type="text" class="form-control" v-model="resourceEdit.title" required>
                            </div>
                            <div class="form-group mt-2">
                                <label>Select resource type</label>
                                <select class="form-control" v-model="resourceEdit.type" required>
                                    <option value="link">Link</option>
                                    <option value="html">HTML snippet</option>
                                    <option value="pdf">PDF download</option>
                                </select>
                            </div>
                            <div class="form-group mt-2" v-if="resourceEdit.type == 'link'">
                                <div class="form-group">
                                    <label>Enter resource link</label>
                                    <input type="url" class="form-control" v-model="resourceEdit.link" required>
                                </div>
                                <div class="form-check form-switch mt-2" title='Toggle'>
                                    <input type="checkbox" class="form-check-input" id="switch" v-model="resourceEdit.open_in_new_tab" />
                                    <label class="form-check-label" for='switch'>Open in a new tab</label>
                                </div>
                            </div>
                            <div class="form-group mt-2" v-show="resourceEdit.type == 'html'">
                                <div class="form-group">
                                    <label>Enter resource description</label>
                                    <textarea class="form-control" id="" cols="30" rows="5" v-model="resourceEdit.description"></textarea>
                                </div>
                                <div class="form-group mt-2">
                                    <label>Enter resource HTML</label>
                                    <textarea name="" id="codeAreaEdit" cols="30" rows="10"></textarea>
                                </div>
                            </div>

                            <div class="form-group mt-2" v-show="resourceEdit.type == 'pdf'">
                                <input type="file" name="" id="" accept="application/pdf" @change="setMediaUpload($event, true)" v-if="resourceEdit.file_name == '' || resourceEdit.file_name == null">
                                <ul class="list-group mt-2" v-if="resourceEdit.file_name != '' && resourceEdit.file_name != null">
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">{{ resourceEdit.file_name }}</div>
                                        </div>
                                        <span class="badge bg-primary rounded-pill" id="file-upload" @click="updateFile()">remove</span>
                                    </li>
                                </ul>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" :disabled="isLoading">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { Notification } from "element-ui";
import { Dropzone } from "dropzone";

export default {
    name: "admin",
    data() {
        return {
            isLoading: false,
            resource: {
                title: '',
                type:'link',
                file_upload:{},
                file_name:"",
                description:'',
                html:'',
                link:'',
                open_in_new_tab:false
            },
            resourceEdit: {
                id:'',
                title: '',
                type:'link',
                file_upload:{},
                file_name:"",
                description:'',
                html:'',
                link:'',
                open_in_new_tab:false
            },
            resources:[],
            url:{
                store: '/api/admin/store',
                list: '/api/admin/list',
                delete: '/api/admin/delete',
                update: '/api/admin/update',
            },
            editorMirror:{}
        }
    },
    async mounted() {
        const response = await axios.get(this.url.list);
        const { resources } = response.data;
        this.resources = resources;

        this.editorMirror = CodeMirror.fromTextArea(document.getElementById("codeArea"), {
                lineWrapping: true,
                autofocus: true,
                autoRefresh: true,
        });
        this.editorMirror.on("change", () => {
            this.resource.html = this.editorMirror.getValue();
        });

        
    },
    methods: {
        storeResource() {
            const formData = new FormData();
            for(let key in this.resource){
                formData.append(key,this.resource[key]);
            }
            this.isLoading = true;
            axios.post(this.url.store, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then((response) => {
                    this.isLoading = false;
                    $("#addModal").modal('hide');
                    this.resources.push(response.data.resource);
                    this.clearResource();
                    Notification({
                        title: 'Success',
                        message: response.data.message,
                        type: 'success'
                    });
                })
                .catch((error) => {
                    this.isLoading = false;
                    if (error.response) {
                        Notification({
                            title: 'error',
                            message: error.response.data.message
                        });
                    }else{
                        Notification({
                            title: 'error',
                            message: "Unable to complete request. Try again Later"
                        });
                    }
                })
        },
        setMediaUpload(event, edit = false){
            if(edit){
                this.resourceEdit.file_upload = event.currentTarget.files[0];
                this.resourceEdit.file_name = this.resourceEdit.file_upload.name;
            }else{
                this.resource.file_upload = event.currentTarget.files[0];
                this.resource.file_name = this.resource.file_upload.name;
            }
           
        },
        changeFile(edit = false){
           if(edit){
                this.resourceEdit.file_name = "";
           }else{
                this.resource.file_name = "";
           }
        },
        setResourceEdit(index){
            this.resourceEdit = {};
            this.resourceEdit = Object.assign({}, this.resources[index], {});
            this.resourceEdit.index = index;

            if(this.editorMirrorUpdate){
                this.editorMirrorUpdate.toTextArea();
            }

            this.editorMirrorUpdate = CodeMirror.fromTextArea(document.getElementById("codeAreaEdit"), {
                lineWrapping: true,
                autofocus: true,
                autoRefresh: true,
            });
            this.editorMirrorUpdate.on("change", () => {
                this.resourceEdit.html = this.editorMirrorUpdate.getValue();
            });
            if(this.resourceEdit.html){
                this.editorMirrorUpdate.setValue(this.resourceEdit.html);
            }
        },
        deleteResource(){
            let resource = {
                id: this.resourceEdit.id,
            };
            this.isLoading = true;
            let index = this.resourceEdit.index;
            axios.delete(this.url.delete, {data: resource})
                .then(response => {
                    this.isLoading = false;
                    this.resources.splice(index, 1);
                    $("#deleteModal").modal('hide');

                    Notification({
                        title: 'Success',
                        message: response.data.message,
                        type: 'success'
                    });

                }).catch(error => {
                    this.isLoading = false;
                    if (error.response) {
                        Notification({
                            title: 'error',
                            message: error.response.data.message
                        });
                    }else{
                        Notification({
                            title: 'error',
                            message: "Unable to complete request. Try again Later"
                        });
                    }
                });
        },
        updateResource(){
            const formData = new FormData();
            for(let key in this.resourceEdit){
                if(key != 'file_upload'){
                    formData.append(key,this.resourceEdit[key]);
                }
            }
            if(this.resourceEdit.file_upload){
                formData.append('file_upload',this.resourceEdit.file_upload);
            }
            this.isLoading = true;
            axios.post(this.url.update, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then((response) => {
                    this.isLoading = false;
                    $("#editModal").modal('hide');
                    let updatedResource = response.data.resource;
                    this.resources = this.resources.map((resource, index) => {
                        if (resource.id === updatedResource.id) {
                            resource = Object.assign({}, updatedResource);
                        }
                        return resource;
                    })
                    Notification({
                        title: 'Success',
                        message: response.data.message,
                        type: 'success'
                    });
                })
                .catch((error) => {
                    this.isLoading = false;
                    if (error.response) {
                        Notification({
                            title: 'error',
                            message: error.response.data.message
                        });
                    }else{
                        Notification({
                            title: 'error',
                            message: "Unable to complete request. Try again Later"
                        });
                    }
                })
        },
        clearResource(){
            this.resource = {
                title: '',
                type:'link',
                file_upload:{},
                file_name:"",
                description:'',
                html:'',
                link:'',
                open_in_new_tab:false
            }
            this.editorMirror.setValue('');
        }
    },
};
</script>
<style scoped>
.admin-add-card{
    cursor: pointer;
    display: flex;
    align-content: center;
}
.card{
    margin-right:10px;
}
svg{
    margin: auto;
    display: block;
}

#file-upload{
    cursor: pointer;
}
</style>