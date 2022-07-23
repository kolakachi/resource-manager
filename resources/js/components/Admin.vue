<template>
    <div class="container">
        <h3 class="mb-5 font-weight-light text-uppercase">Admin Vue Init</h3>
        <div class="row">
            <div class="card col-sm-2 " data-bs-toggle="modal" data-bs-target="#addModal">
                <div class="card-body admin-add-card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M24 10h-10v-10h-4v10h-10v4h10v10h4v-10h10z"/></svg>
                </div>
            </div>
            <div class="card col-sm-2 " v-for="(res, index) in resources" :key="index">
                <div class="card-body">
                    <h5 class="card-title">{{ res.title }}</h5>
                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
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
                                    <label>Enter resource Link</label>
                                    <input type="url" class="form-control" v-model="resource.link" required>
                                </div>
                                <div class="form-check form-switch mt-2" title='Toggle'>
                                    <input type="checkbox" class="form-check-input" id="switch" v-model="resource.open_in_new_tab" />
                                    <label class="form-check-label" for='switch'>Open in new tab</label>
                                </div>
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

export default {
    name: "admin",
    data() {
        return {
            isLoading: false,
            resource: {
                title: '',
                type:'link',
                file:{},
                description:'',
                html:'',
                link:'',
                open_in_new_tab:false
            },
            resources:[],
            url:{
                store: '/api/admin/store',
                list: '/api/admin/list'
            }
        }
    },
    async mounted() {
        const response = await axios.get(this.url.list);
        const { resources } = response.data;
        this.resources = resources;
    },
    methods: {
        storeResource() {
            const formData = new FormData();
            for(let key in this.resource){
                if(key != "file"){
                    formData.append(key,this.resource[key]);
                }
            }
            this.isLoading = true;
            axios.post(this.url.store, formData)
                .then((response) => {
                    this.isLoading = false;
                    $("#addModal").modal('hide');
                    Notification({
                        title: 'Success',
                        message: response.data.message,
                        type: 'success'
                    });
                })
                .catch((error) => {
                    this.isLoading = false;
                    Notification({
                        title: 'error',
                        message: error.response.data.message
                    });
                })
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
</style>