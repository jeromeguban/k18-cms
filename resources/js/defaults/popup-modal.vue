<template>
	<transition name="fade">	
		<div v-if="show" class="fixed inset-0 w-full h-screen flex items-center justify-center" style="z-index: 90" :class="{ 'bg-semi-65' : overlay}" >
			<div class="relative w-full bg-white shadow-lg rounded-lg" :class="[ getModalSize().width, getModalSize().height ]"
				:style=" noHeight ? 'height: auto' : '' ">
				<button aria-label="close" class="absolute top-0 right-0 text-xl text-gray-500 my-2 mx-4" @click.prevent="close">
					<svg class="fill-current w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z"/></svg>
				</button>
				<slot />
			</div>

			
</div>
	
	</transition>
</template>
<style scoped>
	.fade-enter-active,
	.fade-leave-active {
		transition: all 0.4s;
	}
	.fade-enter,
	.fade-leave-to {
		opacity: 0;
	}
</style>
<script>
export default {
	props: {
		identifier: {
			type: String,
			required: true
		},
		triggerEvent: {
			type: Boolean,
			default: false
		},
		overlay: {
			type: Boolean,
			default: true
		},
		modalSize: {
			type: String,
			default: '2xl'
		},
		noHeight: {
			type: Boolean,
			default: false,
		},
		confirmClose: {
			type: Boolean,
			default: false
		},

		// modalSize: {
		// 	type: Object,
		// 	default: function () {
		// 		return {
		// 			width: 'max-w-6xl',
		// 			height: '80vh'
		// 		}
		// 	}
		// }
	},
	data() {
		return {
			show: false,
			modal: [
				{
					name: '2xl',
					width: 'max-w-6xl',
					height: 'modal-height-2xl'
				},
				{
					name: 'xl',
					width: 'max-w-4xl',
					height: 'modal-height-xl'
				},
				{
					name: 'lg',
					width: 'max-w-xl',
					height: 'modal-height-lg'
				},
				{
					name: 'md',
					width: 'max-w-md',
					height: 'modal-height-md'
				},
				{
					name: 'sm',
					width: 'max-w-xs',
					height: 'modal-height-sm'
				},


			]
		}
	},
	watch: {
		showing(value) {
			if(value) {
				return document.querySelector('body').classList.add('overflow-hidden');
			}
			document.querySelector('body').classList.remove('overflow-hidden');
		}
	},
	mounted() {
		
		setTimeout(()=>{
			this.intializeIdentifier();
		}, 100)
		
		app.$on('confirmation-modal', ()=>{
			this.confirmationModal();
		});	

		app.$on('close-modal', ()=>{
			this.show = false;
			this.$emit('close');
		});	
	},

	created() {
		this.getModalSize();

		
	},



	methods: {
		close() {
			if(this.confirmClose){
				this.$modal.dialog({
					message: 'Are you sure you want to close it?',
					confirmButton: 'Okay',
					cancelButton: 'Cancel',
					clickConfirm: true,
				    clickCancel : false,
					title: 'Close'
				})
				.then(confirmed => {  
					this.show = !this.show;
					this.$emit('close');
				}).catch();
			}

			else{
				this.show = !this.show;
				this.$emit('close');
			}


		},


		confirmationModal() {
	
			this.$modal.dialog({
				message: 'Do you want to add another Data?',
				confirmButton: 'Yes',
				cancelButton: 'No',
				clickConfirm: false,
				clickCancel : true,
				title: 'Continue?'
			})
			.then(confirmed => {  
				this.show = !this.show;
				this.$emit('close');
			}).catch();

		},

		intializeIdentifier() {
			let event = document.createEvent('Event');

			event.initEvent('click', true, true);
			
			document.querySelectorAll(this.identifier).forEach((node)=>{
				node.addEventListener("click", ()=>{
					this.show = !this.show;
				}, false);
			})

			if(this.triggerEvent) 
				document.querySelector(this.identifier).dispatchEvent(event);
		},

		getModalSize() {
			return this.modal.find((modal) => {
				return modal.name == this.modalSize;
			})
		}
	}
}
</script>