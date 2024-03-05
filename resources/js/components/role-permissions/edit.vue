<template> 
<div class="flex-grow">
	<div class="pl-5 pr-5 pb-5">
        <div class="overflow-x-auto">
            <table class="table table--sm">
                <thead>
                    <tr>
                       <h2>Permissions</h2>
                    </tr>
                </thead>
                <tbody class="mt-4">
                   <tr v-for="(role_permission, index) in role_permissions">

                            <td>
                                <div class="parent-div flex flex-col">
                                    <div class="content-div flex-1">{{ role_permission.name }}</div>
                                </div> 
                            </td>
                            <td v-for="(slug, key) in role_permission.slug">
                                <div class="parent-div flex flex-col">
                                    <div class="content-div flex-1">
                                    <input type="checkbox" 
                                        v-model="slug.status"
                                        v-on:change="update(role_permission.id, role_permission.slug)"
                                        > {{ slug.permission }}
                                    </div>
                                </div> 
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>

		<!-- <div class="bg-white rounded-sm p-5 table-box-shadow">
			<div class="table-container sm:mt-6 mt-0">
                <form class="flex flex-col h-full" enctype="multipart/form-data">
                    <table class="table-responsive"  >
                        <tr>
                            <th>Module Name</th>
                            <th colspan="10">Permissions</th>
                        </tr>
                        <tr v-for="(role_permission, index) in role_permissions">

                            <td>
                                <div class="parent-div flex flex-col">
                                    <div class="content-div flex-1">{{ role_permission.name }}</div>
                                </div> 
                            </td>
                            <td v-for="(slug, key) in role_permission.slug">
                                <div class="parent-div flex flex-col">
                                    <div class="content-div flex-1">
                                    <input type="checkbox" 
                                        v-model="slug.status"
                                        v-on:change="update(role_permission.id, role_permission.slug)"
                                        > {{ slug.permission }}
                                    </div>
                                </div> 
                            </td>
                        </tr>
                    </table>
                </form>
			</div>
		</div> -->
	</div>
</div>	
</template>
<script>
	export default {
        props: [ 'id' ],
        data(){
            return {
                role_permissions : [],
            }
        },
        created() {
            this.get();
        },
        watch:{
            'id' () {
                 this.get();
            },
        },
        methods : {
            get() {
                axios.get('/roles/' + this.id + '/permissions')
                .then( response =>  {
                    this.role_permissions = response.data;
                    this.role_permissions.forEach(role_permission => {
                        role_permission.slug = this.convertSlugToArray(role_permission.slug);
                    });
                }).catch((error) => {
                    console.log(error);
                });
    		},
            update(role_permission_id, permissions) {
                axios.patch('/roles/' + this.id + '/permissions/' + role_permission_id, { permissions })
                .then( response => {
                    if(response.data.success == 1) {
                        console.log('Succesfuly Updated');
                    }
                }).catch((error) => {
                    console.log(error);
                });
            },
            // The slug field in database is object.
            // We cant make list of checkbox using object,
            // Thus, we need to convert it into array.
            convertSlugToArray(slug_object) {
                let slug_array  = [];
                for (let key in slug_object) {
                    slug_array.push({
                        permission:    key,
                        status:  slug_object[key]
                    });
                }
                return slug_array;
            } 
        }
    }
</script>