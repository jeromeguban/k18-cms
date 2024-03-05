<template>
    <div class="mt-2 mb-6">
        <dashboard :uppy="uppy" :plugins="['Webcam', 'XHRUpload']"  :inline="true"/>
    </div>
</template>

<script>
import { Dashboard } from '@uppy/vue'

import '@uppy/core/dist/style.css'
import '@uppy/dashboard/dist/style.css'
import Uppy from '@uppy/core'
import Webcam from '@uppy/webcam'
import XHRUpload from '@uppy/xhr-upload'
import sha256 from 'crypto-js/sha256';


export default {

   props: {
		url: {
			type: String,
			required: true,
		},
	},


    components: {
	    Dashboard, sha256
	},

    computed: {

        uppy () {

        return new Uppy(
            {
               id: sha256(moment().format('YYYY-MM-DD HH:mm:ss')).toString(),
                restrictions: {
                    maxFileSize: 2000000,
                    maxNumberOfFiles: 30,
                    minNumberOfFiles: 1,
                    allowedFileTypes: ["image/*", ".jpg", ".jpeg", ".png"]
                },
                inline: true,
                proudlyDisplayPoweredByUppy: false,
            })
            .use(Webcam, {
                modes: [
                 'picture'
                ],
                mirror: false,
                facingMode: 'environment',
                showVideoSourceDropdown: true
            })
            .use(XHRUpload, {
                id: sha256(moment().format('YYYY-MM-DD HH:mm:ss')).toString(),
                endpoint: this.url,
                fieldName: 'file',
                formData: true,
                method: 'post',
                headers: {
                    'X-CSRF-Token': window.axios.defaults.headers.common['X-CSRF-TOKEN']
                }
            })
        },

        beforeDestroy () {
            this.uppy.close()
        }
    }
}
</script>
