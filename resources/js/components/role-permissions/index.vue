<template>
	<div class="w-full">
		<table-template :link="'/roles/' + $route.params.id + '/permissions'" :fields="fields" :addNewBtn="false">			
			<template slot="label">
				<h4>List of Role Permissions</h4>
			</template>
		    <template slot="name" slot-scope="props">
		    	<span class="font-medium">{{ props.item.name }}</span>
		    </template>
             <template slot="slug" slot-scope="props">
                 <div v-for="(permission, key,index) in props.item.slug" class="justify-center">


                     <div v-if="permission == false" class="flex-row items-center  bg-indigo-600 px-4 py-1 rounded-full  text-2xs mr-1 mb-2">
                        <span class="flex">
                        <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                            <path style="fill:#B4BBCC;" d="M336.761,62.173H51.116C22.885,62.173,0,85.059,0,113.289v285.645
                                c0,28.231,22.885,51.116,51.116,51.116h285.645c28.231,0,51.116-22.885,51.116-51.116V113.289
                                C387.877,85.059,364.992,62.173,336.761,62.173z"/>
                            <path style="fill:#FFFFFF;" d="M313.395,113.929H74.482c-12.552,0-22.726,10.175-22.726,22.726v238.911
                                c0,12.552,10.175,22.726,22.726,22.726h238.911c12.552,0,22.726-10.175,22.726-22.726V136.656
                                C336.121,124.105,325.945,113.929,313.395,113.929z"/>
                            <path style="opacity:0.1;enable-background:new;" d="M74.653,159.553c0-12.552,10.175-22.726,22.726-22.726h238.74v-0.171
                                c0-12.552-10.175-22.726-22.726-22.726H74.482c-12.552,0-22.726,10.175-22.726,22.726v238.911c0,12.552,10.175,22.726,22.726,22.726
                                h0.171V159.553z"/>
						</svg>
                         {{key}} 
                        </span>
                     </div>

                   <div v-if="permission == true" class="flex-row items-center  bg-indigo-600 px-4 py-1 rounded-full  text-2xs mr-1 mb-2">
                        <span class="flex">
                        <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                            <path style="fill:#B4BBCC;" d="M336.761,62.173H51.116C22.885,62.173,0,85.059,0,113.289v285.645
                                c0,28.231,22.885,51.116,51.116,51.116h285.645c28.231,0,51.116-22.885,51.116-51.116V113.289
                                C387.877,85.059,364.992,62.173,336.761,62.173z"/>
                            <path style="fill:#FFFFFF;" d="M313.395,113.929H74.482c-12.552,0-22.726,10.175-22.726,22.726v238.911
                                c0,12.552,10.175,22.726,22.726,22.726h238.911c12.552,0,22.726-10.175,22.726-22.726V136.656
                                C336.121,124.105,325.945,113.929,313.395,113.929z"/>
                            <path style="opacity:0.1;enable-background:new    ;" d="M74.653,159.553c0-12.552,10.175-22.726,22.726-22.726h238.74v-0.171
                                c0-12.552-10.175-22.726-22.726-22.726H74.482c-12.552,0-22.726,10.175-22.726,22.726v238.911c0,12.552,10.175,22.726,22.726,22.726
                                h0.171V159.553z"/>
                            <path style="fill:#000000;" d="M236.938,371.327c-9.206,0-18.139-3.574-24.835-10.102L95.321,247.391
                                c-14.072-13.718-14.36-36.245-0.643-50.318c13.716-14.074,36.244-14.359,50.317-0.643l85.372,83.218
                                c34.186-48.731,105.492-134.379,226.861-212.078c16.556-10.597,38.558-5.765,49.152,10.783
                                c10.597,16.551,5.768,38.557-10.782,49.153c-163.014,104.36-226.495,223.518-227.117,224.71
                                c-5.206,9.969-14.819,16.891-25.925,18.666C240.683,371.181,238.806,371.327,236.938,371.327z"/>
                         </svg>
                         {{key}} 
                        </span>
                    </div>
                </div>
		    </template>
		</table-template>
   </div>
</template>
<script>
  import TableTemplate from "../../Table";
    export default {
        components: { TableTemplate },
        props: [ 'id' ],
        data(){
            return {
			fields: {
				name  : 'Module Name',
				slug  : 'Permission',
				
			},
		}
        },
        watch: {
            
        },
        created() {
           
        },
        methods : {            
            
        }
    }
</script>