<template>
    <div class="container">
        <h3 class="mb-5 font-weight-light text-uppercase">Resources Page</h3>
        <div class="row">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="links-tab" data-bs-toggle="tab" data-bs-target="#links" type="button" role="tab" aria-controls="links" aria-selected="true">Links</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pdfs-tab" data-bs-toggle="tab" data-bs-target="#pdfs" type="button" role="tab" aria-controls="pdfs" aria-selected="false">PDFs</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="html-tab" data-bs-toggle="tab" data-bs-target="#html" type="button" role="tab" aria-controls="html" aria-selected="false">HTML snippets</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                
                <div class="tab-pane fade show active" id="links" role="tabpanel" aria-labelledby="links-tab">
                    <div class="row">
                        <div class="card col-sm-2 mb-2 mt-2 ml-2" v-for="(linkResource, linkIndex) in getLinks" :key="linkIndex">
                            <div class="card-body">
                                <h5 class="card-title">{{ linkResource.title }}</h5>
                                <a :href="linkResource.link" :target="getTarget(linkResource)" >
                                    Open
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pdfs" role="tabpanel" aria-labelledby="pdfs-tab">
                    <div class="row">
                        <div class="card col-sm-2 mb-2 mt-2 ml-2" v-for="(pdfResource, pdfIndex) in getPDFs" :key="pdfIndex">
                            <div class="card-body">
                                <h5 class="card-title">{{ pdfResource.title }}</h5>
                                <a :href="getDownloadLink(pdfResource)" >
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="html" role="tabpanel" aria-labelledby="html-tab">
                    <div class="row">
                        <div class="card col-sm-2 mb-2 mt-2" v-for="(htmlResource, index) in getSnippets" :key="index">
                            <div class="card-body">
                                <h5 class="card-title">{{ htmlResource.title }}</h5>
                                <p class="card-text">{{ htmlResource.description }}</p>
                                <pre v-html="htmlResource.html"></pre>
                                <button class="btn btn-primary btn-sm" @click="copyHtml(htmlResource)">
                                   <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard" viewBox="0 0 16 16">
                                        <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                        <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { Notification } from "element-ui";

export default {
    name: "home",
    data() {
        return {
            resources:[],
            url:{
                list: '/api/resources/list',
                download: '/home/resources/download/'
            },
        }
    },
    computed:{
        getLinks(){
            var results = this.resources.filter((resource) => {
                return resource.type == 'link';
                
            });
            return results;
        },
        getPDFs(){
            var results = this.resources.filter((resource) => {
                return resource.type == 'pdf';
                
            });
            return results;
        },
        getSnippets(){
            var results = this.resources.filter((resource) => {
                return resource.type == 'html';
                
            });
            return results;
        }
    },
    async mounted() {
        const response = await axios.get(this.url.list);
        const { resources } = response.data;
        this.resources = resources;        
    },
    methods: {
        getTarget(linkResource){
            return (linkResource.open_in_new_tab ? "_blank" : "_self");
        },
        getDownloadLink(pdfResource){
            return this.url.download + pdfResource.id;
        },
        copyHtml(htmlResource) {
            this.snippet = htmlResource.html;
            document.addEventListener("copy", this.copyHtmlHandler);
            if (document.execCommand("copy")) {
                Notification({
                    title: 'Success',
                    message: "Copy Successful",
                    type: 'success'
                });
            }
            document.removeEventListener("copy", this.copyHtmlHandler);
        },
        copyHtmlHandler(e) {
            e.clipboardData.setData("text/html", this.snippet);
            e.clipboardData.setData("text/plain", this.snippet);
            e.preventDefault();
        },
        copyMessage() {
            this.$notify({
                title: 'Success',
                message: "Copy Successful",
                type: 'success'
            });
        },
    }
};
</script>
<style scoped>
.card{
    margin-right:10px;
}
</style>