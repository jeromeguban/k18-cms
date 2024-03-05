<template>
	<div class="w-full">
		<div class="flex flex-col relative mb-3 mt-1" :style="'max-width:' + customMaxWidth">
			<div class="flex flex-col border border-gray-200 border-solid p-3 w-full "
				:class="{ 'rounded' : !selected }" @click.self="changeFocus(); selected = !selected">
				<div class="flex justify-between" @click.self="changeFocus(); selected = !selected">
					<div class="flex flex-wrap" :class="{ 'w-full' : !multiple }"
						@click.self="changeFocus(); selected = !selected">
						<div class="rounded px-3 py-2 text-gray-600 mr-1 mt-1 bg-gray-200 flex items-center cursor-pointer"
							style="font-size: 0.750rem;" v-for="(option, index) in displayValue" v-if="multiple">
							<span>{{ setLabel(option) }}</span>
							<div @click="remove(option)">
								<svg class="fill-current h-3 w-3 ml-4" xmlns="http://www.w3.org/2000/svg"
									viewBox="0 0 320 512">
									<path
										d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z" />
								</svg>
							</div>
						</div>

						<input type="text" :id="'input_'+name" class="outline-none font-semibold"
							:class="{ 'pr-4 w-full' : !multiple }" v-if="!multiple" v-model="selectedValue"
							@click="changeFocus(); selected = !selected">
					</div>

					<div class="cursor-pointer" @click="changeFocus(); selected = !selected; selectedValue = null">
						<svg v-if="!selected" class="fill-current h-3 w-3 mt-1" xmlns="http://www.w3.org/2000/svg"
							viewBox="0 0 320 512">
							<path
								d="M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z" />
						</svg>
						<svg v-if="selected" class="fill-current h-3 w-3 mt-2" xmlns="http://www.w3.org/2000/svg"
							viewBox="0 0 320 512">
							<path
								d="M288.662 352H31.338c-17.818 0-26.741-21.543-14.142-34.142l128.662-128.662c7.81-7.81 20.474-7.81 28.284 0l128.662 128.662c12.6 12.599 3.676 34.142-14.142 34.142z" />
						</svg>
					</div>
				</div>
			</div>
			<div class="">
				<click-outside :handler="handleClickOutside">
					<div class="bg-white border border-t-0 border-gray-200 w-full absolute mb-8 z-50"
						style="	box-shadow: 0 0 3px rgba(3;1,30,47,.05); max-height: 255px;" v-if="selected">
						<div class=" p-3 border-b border-gray-200 border-solid flex justify-between">
							<input type="text" :id="'search_'+name" class="outline-none font-semibold" v-if="selected"
								v-model="search">
							<svg class="fill-current h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg"
								viewBox="0 0 512 512">
								<path
									d="M508.5 481.6l-129-129c-2.3-2.3-5.3-3.5-8.5-3.5h-10.3C395 312 416 262.5 416 208 416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c54.5 0 104-21 141.1-55.2V371c0 3.2 1.3 6.2 3.5 8.5l129 129c4.7 4.7 12.3 4.7 17 0l9.9-9.9c4.7-4.7 4.7-12.3 0-17zM208 384c-97.3 0-176-78.7-176-176S110.7 32 208 32s176 78.7 176 176-78.7 176-176 176z" />
							</svg>
						</div>
						<div class="flex flex-col overflow-y-auto border-solid w-full" style="max-height: 200px;"
							v-if="selected">
							<!-- <li class="multiselect__list">
					    This is just a test
					    <svg class="fill-current h-3 w-3 ml-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
					    	<path d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z"/>
					    </svg>
						</li> -->
							<li class="flex justify-between items-center list-none px-4 cursor-pointer transition-all duration-100 ease-in-out mb-px"
								style="min-height: 40px;" v-for="(option, index) in dropDownOptions"
								:class="{ 'bg-gray-500 text-white hover:bg-gray-700' : checkIfSelected(option) }"
								@click="event(option)">
								{{ setLabel(option) }}

								<svg class="fill-current h-3 w-3 ml-4" xmlns="http://www.w3.org/2000/svg"
									viewBox="0 0 448 512" v-if="!checkIfSelected(option)">
									<path
										d="M413.505 91.951L133.49 371.966l-98.995-98.995c-4.686-4.686-12.284-4.686-16.971 0L6.211 284.284c-4.686 4.686-4.686 12.284 0 16.971l118.794 118.794c4.686 4.686 12.284 4.686 16.971 0l299.813-299.813c4.686-4.686 4.686-12.284 0-16.971l-11.314-11.314c-4.686-4.686-12.284-4.686-16.97 0z" />
								</svg>

								<svg class="fill-current h-3 w-3 ml-4" xmlns="http://www.w3.org/2000/svg"
									viewBox="0 0 320 512" v-if="checkIfSelected(option)">
									<path
										d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z" />
								</svg>
							</li>
						</div>

					</div>
				</click-outside>
			</div>
		</div>
		<span class="text-red-500 my-2 flex items-center text-2xs" v-if="errorMessage">
			{{ errorMessage }}
		</span>
	</div>
</template>
<script>
import ClickOutside from 'onclick-outside'
export default {
	components: { ClickOutside },
	props: {
		options: {
			type: [Array, Object],
			required: true,
		},
		multiple: {
			type: Boolean,
			multiple: false,
		},
		closeOnSelect: {
			type: Boolean,
			default: true
		},
		customLabel: {
			default: null,
			type: Function
		},
		placeholder: {
			type: String,
			default: null
		},
		errorMessage: {
			type: String,
			default: null
		},
		name: {
			type: [String, Function],
			required: true,
		},
		label: {
			type: String,
			required: false,
		},
		trackBy: {
			type: String,
			required: false,
		},
		customClass: {
			type: String,
			default: null,
		},
		customMaxWidth: {
			type: String,
			default: '380px',
		},
		value: {
			default: null
		}

	},

	data() {
		return {
			selected: false,
			search: null,
			selectedValue: this.value || null
		}
	},

	watch: {
		'selected'() {
			if (this.value && !this.multiple && !this.selected)
				this.selectedValue = this.setLabel(this.value)
		},
		'value'() {

			if (this.value && !this.multiple && !this.selected) {
				this.selectedValue = this.setLabel(this.value)
				return
			}

			this.selectedValue = null
		}
	},

	computed: {
		displayValue: {
			get() {
				return this.value
			},

			set(modifiedValue) {

				let finalValue = modifiedValue

				if (this.trackBy) {
					if (this.multiple && modifiedValue.length) {
						finalValue = modifiedValue.map((value) => {

							let foundValue = value[this.trackBy] || value

							if (typeof foundValue === 'number')
								foundValue = foundValue.toString()

							return foundValue
						})
					}

					if (!this.multiple && typeof modifiedValue === 'object') {
						finalValue = modifiedValue && modifiedValue[this.trackBy] ? modifiedValue[this.trackBy] : modifiedValue

						if (typeof finalValue === 'number')
							finalValue = finalValue.toString()

					}

				}

				this.$emit('input', finalValue)
			},
		},
		dropDownOptions() {
			if (this.search)
				return this.options.filter((option) => {

					let value = new RegExp(this.search, 'gi')
					let label = this.setLabel(option).toString()

					return label.match(value)

				})

			return this.options
		},
	},


	methods: {
		event(data) {

			this.selectedValue = null

			if (!this.checkIfSelected(data))
				return this.add(data)

			this.remove(data)

		},

		checkIfSelected(data) {

			if (this.trackBy) {

				if (this.multiple && this.displayValue.length)
					return this.displayValue.find((option) => {
						return option === this.getTrackByOption(data)[this.trackBy]
					})

				return this.displayValue === this.getTrackByOption(data)[this.trackBy]

			}

			if (this.multiple && this.displayValue.length)
				return this.displayValue.find((option) => {
					return option === data
				})

			return this.displayValue === this.getTrackByOption(data)

		},

		add(data) {

			if (this.displayValue && this.multiple) {

				let data_to_added = this.trackBy && data[this.trackBy] ? data[this.trackBy] : data

				this.displayValue.push(data_to_added)

			}


			if (data && !this.multiple) {
				this.selectedValue = this.setLabel(data)
				this.displayValue = data
			}

			if (this.closeOnSelect)
				this.close()
		},

		remove(data) {
			if (this.multiple) {

				let data_to_be_removed = this.trackBy && data[this.trackBy] ? data[this.trackBy] : data

				for (let index = 0; index < this.displayValue.length; index++) {
					if (data_to_be_removed === this.displayValue[index])
						this.displayValue.splice(index, 1)
				}
				return
			}

			this.displayValue = null
		},

		close() {
			this.selected = false
			this.search = null
		},

		handleClickOutside(e) {
			this.selected = false

		},

		setLabel(data) {

			let initialValue = this.getTrackByOption(data)

			return this.customLabel ?
				this.customLabel(initialValue) :
				(this.label && initialValue[this.label] ? initialValue[this.label] : initialValue)
		},

		getTrackByOption(value) {

			if (this.trackBy && (typeof value === 'string' || typeof value === 'number')) {
				return this.options.find((option) => {
					return option[this.trackBy] == value
				}) || value
			}

			return value
		},

		changeFocus() {
			setTimeout(() => {
				document.querySelector(`#search_${this.name}`).focus()
				document.querySelector(`#search_${this.name}`).select()
			}, 100)
		}
	}
}
</script>
