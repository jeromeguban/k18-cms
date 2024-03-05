<template>
    <modal :active="active" @close="active = false"  style="background:#000000a6; z-index: 999999;">
        
        <div v-if="params.type == 'success'">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
              <circle class="path circle" fill="none" stroke="#73AF55" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
              <polyline class="path check" fill="none" stroke="#73AF55" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
            </svg>
            <p class="success">{{ params.title ? params.title : 'Success' }}</p>
        </div>

        <div v-if="params.type == 'error'">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
              <circle class="path circle" fill="none" stroke="#D06079" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
              <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3"/>
              <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" x2="34.4" y2="92.2"/>
            </svg>
            <p class="error">{{ params.title ? params.title : 'Error!' }}</p>
        </div>

        <div class="w-full text-center">
        <span class="text-gray-600 font-medium text-normal">{{ params.message }}</span>
        </div>
        
        <template v-slot:footer >
            <div class=" flex items-center justify-center">
                <button
                    class="transition border border-blue-500 bg-white text-theme-1 py-2 px-8 text-theme-1 rounded-lg mr-2 mt-8"
                    @click.prevent="handleClick(params.clickCancel)"
                    v-if="params.cancelButton"
                    v-text="params.cancelButton"
                >
                </button>

                <button
                    class="transition btn-primary py-2 px-8 text-white rounded-lg mt-8"
                    @click.prevent="handleClick(params.clickConfirm)"
                    v-if="params.confirmButton"
                    v-text="params.confirmButton"
                >
                </button>
            </div>
        </template>
    </modal>
</template>
<style scoped>
    svg {
      width: 45px;
      display: block;
      margin: 10px auto 0;
    }
    .path {
      stroke-dasharray: 1000;
      stroke-dashoffset: 0;
    }
    .path.circle {
      -webkit-animation: dash 0.9s ease-in-out;
      animation: dash 0.9s ease-in-out;
    }
    .path.line {
      stroke-dashoffset: 1000;
      -webkit-animation: dash 0.9s 0.35s ease-in-out forwards;
      animation: dash 0.9s 0.35s ease-in-out forwards;
    }
    .path.check {
      stroke-dashoffset: -100;
      -webkit-animation: dash-check 0.9s 0.35s ease-in-out forwards;
      animation: dash-check 0.9s 0.35s ease-in-out forwards;
    }
    p {
      text-align: center;
      margin: 20px 0 20px;
      font-size: 1.25em;
    }
    p.success {
      color: #73AF55;
    }
    p.error {
      color: #D06079;
    }
    @-webkit-keyframes dash {
      0% {
        stroke-dashoffset: 1000;
      }
      100% {
        stroke-dashoffset: 0;
      }
    }
    @keyframes dash {
      0% {
        stroke-dashoffset: 1000;
      }
      100% {
        stroke-dashoffset: 0;
      }
    }
    @-webkit-keyframes dash-check {
      0% {
        stroke-dashoffset: -100;
      }
      100% {
        stroke-dashoffset: 900;
      }
    }
    @keyframes dash-check {
      0% {
        stroke-dashoffset: -100;
      }
      100% {
        stroke-dashoffset: 900;
      }
    }

</style>
<script>    
    import Modal from '../plugins/Modal/ModalPlugin';

    export default {
        data() {
            return {
                params: this.defaultParams(),
                active: false,
            };
        },

        beforeMount() {             
            Modal.events.$on('show', params => {
                this.active = true;
                Object.assign(this.params, params);
            });

            Modal.events.$on('hide', (data) => {
                this.params = this.defaultParams();
                this.active = false;
            });
        },

        methods: {
            handleClick(confirmed) {
                Modal.events.$emit('clicked', confirmed);

                this.$modal.hide();
            },
            defaultParams() {
                return {
                    message: 'Are you sure?',
                    confirmButton: 'Continue',
                    cancelButton: 'Cancel',
                    clickConfirm: true,
                    clickCancel : false
                }
            }
        } 
    }
</script>


