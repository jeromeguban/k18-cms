<template>
	<div>
		<div class="w-full">
			<div class="flex flex-col items-center mb-4">
				<span class="leading-loose tracking-widest text-theme-15">SELECTED FILE</span>
				<div class="mt-4 w-full" v-show="data.length">
					<div class="flex flex-col">
						<div class="flex w-full my-1 items-center justify-between" v-for="(image, index) in data"
							:key="index">
							<div class="flex items-center">
								<div class="text-theme-6" v-html="getExt(files[index].file.name)"></div>
								<span class="ml-3 break-all" v-text="files[index].file.name"></span>
								<span class="bg-theme-6 px-2 py-1 text-2xs rounded-full text-white ml-4"
									v-if="!checkStatus(files[index])">Uploading Failed</span>
							</div>

							<span class="text-theme-6 cursor-pointer" @click="removeFile(index)">
								<svg class="fill-current w-4 h-4" xmlns="http://www.w3.org/2000/svg"
									viewBox="0 0 320 512">
									<path
										d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z" />
								</svg>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="flex flex-col justify-center items-center text-md text-theme-15 cursor-pointer"
				@dragenter="onDragEnter" @dragleave="onDragLeave" @dragover.prevent @drop="onDrop"
				:class="{ 'bg-gray-200': isDragging }">
				<div
					class="px-4 pb-4 mt-5 flex flex-col items-center justify-center cursor-pointer relative cursor-pointer">
					<svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
						icon-name="file-plus" data-lucide="file-plus" class="lucide lucide-file-plus block mx-auto">
						<path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"></path>
						<polyline points="14 2 14 8 20 8"></polyline>
						<line x1="12" y1="18" x2="12" y2="12"></line>
						<line x1="9" y1="15" x2="15" y2="15"></line>
					</svg>
					<span class="text-primary mt-1">DROP FILES HERE OR ClICK
						TO UPLOAD</span>
					<input id="horizontal-form-1" type="file"
						class="w-full h-full top-0 left-0 absolute opacity-0 cursor-pointer" multiple
						@change="onInputChange">
				</div>

			</div>
		</div>
	</div>
</template>
<script>
export default {
	props: {
		url: {
			type: String,
			default: 'files/',
		},
		method: {
			type: String,
			default: 'POST',
		},
	},
	data() {
		return {
			isDragging: false,
			dragCount: 0,
			files: [],
			data: [],
			file_statuses: [],
		}
	},

	methods: {
		onDragEnter(e) {
			e.preventDefault();
			this.dragCount++;

			this.isDragging = true;

			return false;
		},

		onDragLeave(e) {
			e.preventDefault();

			this.isDragging = false;
		},

		onInputChange(e) {
			const files = e.target.files;
			Array.from(files).forEach(file => this.addFile(file));
			this.$emit('input', this.files);
		},




		onDrop(e) {
			e.preventDefault();
			e.stopPropagation();

			this.isDragging = false;

			const files = e.dataTransfer.files;

			Array.from(files).forEach(file => this.addFile(file));

			this.$emit('input', this.files);
		},

		addFile(file) {

			this.files.push({
				file,
				success: true
			});

			this.upload(file, this.files.length > 0 ? this.files.length - 1 : 0);

			const reader = new FileReader();

			reader.onload = (e) => this.data.push(e.target.result);
			reader.readAsDataURL(file);
		},

		removeFile(index) {
			this.data.splice(index, 1);
			this.files.splice(index, 1);
		},

		upload(file, index) {

			let formData = new FormData;
			formData.set('file', file);

			axios[this.method.toLowerCase()](this.url, formData)
				.then(() => {

				})
				.catch(() => {
					this.files[index].success = false;
				})
		},

		checkStatus({ success }) {
			return success;
		},

		getExt(filename) {
			let idx = filename.lastIndexOf('.');
			// handle cases like, .htaccess, filename
			let extension = (idx < 1) ? "" : filename.substr(idx + 1);

			let images = ['ai', 'gif', 'ico', 'jpeg', 'jpg', 'png', 'ps', 'psd', 'svg', 'tif'];
			let excel = ['ods', 'xlr', 'xls', 'xlsx', 'csv'];
			let word = ['doc', 'docx', 'odt'];
			let txt = ['txt', 'text', 'rtf'];
			let pdf = ['pdf'];


			if (images.includes(extension))
				return "<svg class='fill-current w-6 h-6' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><path d='M464 64H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V112c0-26.51-21.49-48-48-48zm16 336c0 8.822-7.178 16-16 16H48c-8.822 0-16-7.178-16-16V112c0-8.822 7.178-16 16-16h416c8.822 0 16 7.178 16 16v288zM112 232c30.928 0 56-25.072 56-56s-25.072-56-56-56-56 25.072-56 56 25.072 56 56 56zm0-80c13.234 0 24 10.766 24 24s-10.766 24-24 24-24-10.766-24-24 10.766-24 24-24zm207.029 23.029L224 270.059l-31.029-31.029c-9.373-9.373-24.569-9.373-33.941 0l-88 88A23.998 23.998 0 0 0 64 344v28c0 6.627 5.373 12 12 12h360c6.627 0 12-5.373 12-12v-92c0-6.365-2.529-12.47-7.029-16.971l-88-88c-9.373-9.372-24.569-9.372-33.942 0zM416 352H96v-4.686l80-80 48 48 112-112 80 80V352z'/></svg>";

			if (excel.includes(extension))
				return "<svg class='fill-current w-6 h-6' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 384 512'><path d='M369.9 97.9L286 14C277 5 264.8-.1 252.1-.1H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V131.9c0-12.7-5.1-25-14.1-34zm-22.6 22.7c2.1 2.1 3.5 4.6 4.2 7.4H256V32.5c2.8.7 5.3 2.1 7.4 4.2l83.9 83.9zM336 480H48c-8.8 0-16-7.2-16-16V48c0-8.8 7.2-16 16-16h176v104c0 13.3 10.7 24 24 24h104v304c0 8.8-7.2 16-16 16zM211.7 308l50.5-81.8c4.8-8-.9-18.2-10.3-18.2h-4.1c-4.1 0-7.9 2.1-10.1 5.5-31 48.5-36.4 53.5-45.7 74.5-17.2-32.2-8.4-16-45.8-74.5-2.2-3.4-6-5.5-10.1-5.5H132c-9.4 0-15.1 10.3-10.2 18.2L173 308l-59.1 89.5c-5.1 8 .6 18.5 10.1 18.5h3.5c4.1 0 7.9-2.1 10.1-5.5 37.2-58 45.3-62.5 54.4-82.5 31.5 56.7 44.3 67.2 54.4 82.6 2.2 3.4 6 5.4 10 5.4h3.6c9.5 0 15.2-10.4 10.1-18.4L211.7 308z'/></svg>";

			if (word.includes(extension))
				return "<svg class='fill-current w-6 h-6' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 384 512'><path d='M369.9 97.9L286 14C277 5 264.8-.1 252.1-.1H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V131.9c0-12.7-5.1-25-14.1-34zm-22.6 22.7c2.1 2.1 3.5 4.6 4.2 7.4H256V32.5c2.8.7 5.3 2.1 7.4 4.2l83.9 83.9zM336 480H48c-8.8 0-16-7.2-16-16V48c0-8.8 7.2-16 16-16h176v104c0 13.3 10.7 24 24 24h104v304c0 8.8-7.2 16-16 16zm-53.6-246.5c-6.8 32.8-32.5 139.7-33.4 150.3-5.8-29.1-.7 1.6-41.8-150.9-1.4-5.2-6.2-8.9-11.6-8.9h-6.4c-5.4 0-10.2 3.6-11.6 8.9-38.3 142.3-37.4 140.6-39.4 154.7-4.1-23.9 2.1-2.9-34.4-154.4-1.3-5.4-6.1-9.2-11.7-9.2h-7.2c-7.8 0-13.5 7.3-11.6 14.9 9.5 38 34.5 137.4 42.2 168 1.3 5.3 6.1 9.1 11.6 9.1h17c5.4 0 10.2-3.7 11.6-8.9 34.2-127.7 33.5-123.4 36.7-142.9 6.5 31.1.2 7 36.7 142.9 1.4 5.2 6.2 8.9 11.6 8.9h14c5.5 0 13.7-3.7 15.1-9l42.8-168c1.9-7.6-3.8-15-11.6-15h-6.8c-5.7 0-10.6 4-11.8 9.5z'/></svg>";

			if (txt.includes(extension))
				return "<svg class='fill-current w-6 h-6' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 384 512'><path d='M369.9 97.9L286 14C277 5 264.8-.1 252.1-.1H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V131.9c0-12.7-5.1-25-14.1-34zm-22.6 22.7c2.1 2.1 3.5 4.6 4.2 7.4H256V32.5c2.8.7 5.3 2.1 7.4 4.2l83.9 83.9zM336 480H48c-8.8 0-16-7.2-16-16V48c0-8.8 7.2-16 16-16h176v104c0 13.3 10.7 24 24 24h104v304c0 8.8-7.2 16-16 16zm-48-244v8c0 6.6-5.4 12-12 12H108c-6.6 0-12-5.4-12-12v-8c0-6.6 5.4-12 12-12h168c6.6 0 12 5.4 12 12zm0 64v8c0 6.6-5.4 12-12 12H108c-6.6 0-12-5.4-12-12v-8c0-6.6 5.4-12 12-12h168c6.6 0 12 5.4 12 12zm0 64v8c0 6.6-5.4 12-12 12H108c-6.6 0-12-5.4-12-12v-8c0-6.6 5.4-12 12-12h168c6.6 0 12 5.4 12 12z'/></svg>";

			if (pdf.includes(extension))
				return "<svg class='fill-current w-6 h-6' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 384 512'><path d='M369.9 97.9L286 14C277 5 264.8-.1 252.1-.1H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V131.9c0-12.7-5.1-25-14.1-34zm-22.6 22.7c2.1 2.1 3.5 4.6 4.2 7.4H256V32.5c2.8.7 5.3 2.1 7.4 4.2l83.9 83.9zM336 480H48c-8.8 0-16-7.2-16-16V48c0-8.8 7.2-16 16-16h176v104c0 13.3 10.7 24 24 24h104v304c0 8.8-7.2 16-16 16zm-22-171.2c-13.5-13.3-55-9.2-73.7-6.7-21.2-12.8-35.2-30.4-45.1-56.6 4.3-18 12-47.2 6.4-64.9-4.4-28.1-39.7-24.7-44.6-6.8-5 18.3-.3 44.4 8.4 77.8-11.9 28.4-29.7 66.9-42.1 88.6-20.8 10.7-54.1 29.3-58.8 52.4-3.5 16.8 22.9 39.4 53.1 6.4 9.1-9.9 19.3-24.8 31.3-45.5 26.7-8.8 56.1-19.8 82-24 21.9 12 47.6 19.9 64.6 19.9 27.7.1 28.9-30.2 18.5-40.6zm-229.2 89c5.9-15.9 28.6-34.4 35.5-40.8-22.1 35.3-35.5 41.5-35.5 40.8zM180 175.5c8.7 0 7.8 37.5 2.1 47.6-5.2-16.3-5-47.6-2.1-47.6zm-28.4 159.3c11.3-19.8 21-43.2 28.8-63.7 9.7 17.7 22.1 31.7 35.1 41.5-24.3 4.7-45.4 15.1-63.9 22.2zm153.4-5.9s-5.8 7-43.5-9.1c41-3 47.7 6.4 43.5 9.1z'/></svg>";
		}


	}

}
</script>