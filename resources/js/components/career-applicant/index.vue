<template>
    <div class="w-full">
        <table-template :link="'/career/'+ this.$route.params.id+'/applicants'" :fields="fields" :params="params" :addNewBtn="false">
            <template slot="label">
                <h4>List of Applicant</h4>
            </template>

            <template slot="name" slot-scope="props">
              <span class="font-medium">{{ props.item.name }}</span>
            </template>

            <template slot="posting_id" slot-scope="props">

                <a class="flex items-center mr-3 " href="javascript:;" @click.prevent="viewResume(props.item.career_applicant_id)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="image" data-lucide="image" class="lucide lucide-image block mx-auto"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>Show Resume
                </a>

                <a class="flex items-center mr-3 " href="javascript:;" @click.prevent="downloadResume(props.item.career_applicant_id)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="file-text" data-lucide="file-text" class="lucide lucide-file-text block mx-auto"><path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><line x1="10" y1="9" x2="8" y2="9"></line></svg>Download Resume
                </a>

            </template>
          </table-template>



        </div>
    </template>
    <script>
    import TableTemplate from "../../Table";

    export default {
        components: { TableTemplate },
        data() {
            return {
                fields: {
                    career_applicant_firstname  : 'First Name' ,
                    career_applicant_lastname   : 'Last Name' ,
                    career_applicant_middlename : 'Middle Name' ,
                    email      : 'Email' ,
                    mobile_no  : 'Mobile No',
                    posting_id : 'Actions'
                },
            }
        },

        watch: {

        },


        methods: {

            viewResume(id) {
                let link = 'career-applicant/'+ id + '/resume'

                window.open(link, "_blank")
            },

            downloadResume(id) {
                let link = 'career-applicant/'+ id +'/download'

                window.open(link, "_blank")
            }
        }
    }
    </script>
