<template>
	<div>
	<div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">Auction Landing Page Customizer </h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
			<div class="flex items-center justify-end">
				<a href="#"  @click.prevent="" data-toggle="modal" data-target="#large-slide-over-size-create-preview" class="btn btn-primary w-32 mr-2 mb-2">
					<svg class="fill-current w-3 h-3 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M384 250v12c0 6.6-5.4 12-12 12h-98v98c0 6.6-5.4 12-12 12h-12c-6.6 0-12-5.4-12-12v-98h-98c-6.6 0-12-5.4-12-12v-12c0-6.6 5.4-12 12-12h98v-98c0-6.6 5.4-12 12-12h12c6.6 0 12 5.4 12 12v98h98c6.6 0 12 5.4 12 12zm120 6c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-32 0c0-119.9-97.3-216-216-216-119.9 0-216 97.3-216 216 0 119.9 97.3 216 216 216 119.9 0 216-97.3 216-216z"/></svg>Add New
				</a>

			</div>
        </div>
    </div>
	
	<div class="intro-y box overflow-hidden mt-5">
		<div  class="max-w-7xl mx-auto flex flex-col items-center py-0 sm:py-10" v-if="!auction_sections.length">
			<loader object="#1C3FAA" color1="#1C3FAA" color2="#1C3FAA" size="9" speed="2" bg="#1E1E1E" objectbg="#999793" opacity="0" name="dots"></loader>
		</div>
		<div class="px-5 sm:px-16 py-10 sm:py-20">
			<div class="max-w-7xl mx-auto flex flex-col items-center py-0 sm:py-10">
				<div class="flex flex-wrap w-full">
					<div class="grid px-1 mb-2 relative item-section-container"  :key="index" v-for="(auction_section, index) in auction_sections"
						:class="{
							'w-full': getProperties(auction_section.properties).per_row == '1',
							'w-1/2': getProperties(auction_section.properties).per_row == '2',
							'w-1/3': getProperties(auction_section.properties).per_row == '3',
						}"
						:style="'height:' + getHeight(getProperties(auction_section.properties).per_row, getProperties(auction_section.properties).section_height) + 'px'"
						>
							<div class="border border-gray-200 relative bg-cover bg-bottom" :style="'background-image: url(images/auction_sections/'+ getProperties(auction_section.properties).image +');'">
								<div class="flex"
									:class="{
											'justify-start text-left': getProperties(auction_section.properties).position == 'left',
											'justify-center text-center': getProperties(auction_section.properties).position == 'center',
											'justify-end text-left': getProperties(auction_section.properties).position == 'right'
									}"
									>
									<div class="flex flex-col leading-loose w-2/5" 
										:class="{
												'mt-10': getProperties(auction_section.properties).per_row == '1',
												'ml-10': getProperties(auction_section.properties).per_row == '1' && getProperties(auction_section.properties).position == 'left',
												'mr-10': getProperties(auction_section.properties).per_row == '1' && getProperties(auction_section.properties).position == 'right',
												'mt-4': getProperties(auction_section.properties).per_row >= '2',
												'ml-4': getProperties(auction_section.properties).per_row >= '2' && getProperties(auction_section.properties).position == 'left',
												'mr-4': getProperties(auction_section.properties).per_row >= '2' && getProperties(auction_section.properties).position == 'right',
												'bg-semi-65 p-4 rounded text-white' : getProperties(auction_section.properties).caption_style == 'overlay-caption',
												'hidden' : getProperties(auction_section.properties).caption_style == 'no-caption',
												'text-gray-100' : getProperties(auction_section.properties).text_color == 'light',
												'text-gray-800' : getProperties(auction_section.properties).text_color == 'dark'
											}"
										>
										<!-- 'w-3/5' : (getProperties(auction_section.properties).caption_style == 'plain-caption')  || (getProperties(auction_section.properties).caption_style == 'plain-caption'  && getProperties(auction_section.properties).per_row >= '2'),
										'w-2/5' : getProperties(auction_section.properties).caption_style == 'overlay-caption' && getProperties(auction_section.properties).per_row == '1', -->
										<h2 class="uppercase mb-2 leading-normal" 
											:class="{
												'text-2xl font-black': getProperties(auction_section.properties).per_row == '1',
												'text-sm font-bold': getProperties(auction_section.properties).per_row >= '2'
											}"
										>
											{{ getProperties(auction_section.properties).title }}</h2>
										<span class="mb-1" v-if="getProperties(auction_section.properties).event_details">{{ getProperties(auction_section.properties).event_details }}</span>
										<a class="flex items-center justify-center bg-yellow-400 text-white transition-all shadow-sm hover:shadow-lg hover:bg-yellow-500 py-3 w-36 rounded mb-1" 
											v-if="getProperties(auction_section.properties).button && getProperties(auction_section.properties).button_label">
											<span class="mr-1">{{ getProperties(auction_section.properties).button_label }}</span>
											<i class="fa-light fa-arrow-right"></i>
										</a>
									</div>
								</div>
								<div class="bg-semi-65 absolute bottom-0 py-4 text-white grid grid-cols-9 w-full px-3">
									<div class="col-span-6">
										<span class="font-semibold text-sm italic uppercase">{{ getProperties(auction_section.properties).label }}</span>
									</div>
									<div class="col-span-3 flex items-center justify-end">
										<a :href="getProperties(auction_section.properties).event_link" class="flex items-center hover:underline cursor-pointer">
											<span class="mr-1">View All</span>
											<i class="fa-light fa-arrow-right"></i>
										</a>
									</div>
								</div>
							</div>
							<div class="item-section-overlay flex items-center justify-center">
								<a href="#" @click.prevent="deleteSection(auction_section.id)" v-can="'delete.auction-section'"  class="btn bg-theme-6 text-white w-32 mr-2 mb-2">
									Delete
								</a>
								<a href="#"  @click.prevent="setAuctionSection(auction_section)" data-toggle="modal" data-target="#large-slide-over-size-preview" class="btn btn-primary w-32 mr-2 mb-2">
									Edit
								</a>
							</div>
					</div>
				</div>
			</div>
				<create @created="refreshData"/>
				<edit v-if="auction_section" :auction_section="auction_section" @updated="refreshData"/>
		</div>
	</div>
</div>
</template>
<style scoped>
	.bg-semi-65 {
		background-color: rgba(0,0,0,0.65);
	}
	.item-section-overlay {
		position: absolute;
		top: 0;
		bottom:  0;
		left: 0;
		right: 0;
		height: 100%;
		width: 100%;
		opacity: 0;
		transition:  .5s ease;
		background-color: rgba(255,255,255,0.8);
	}

	.item-section-container:hover .item-section-overlay {
		opacity: 1;
	}
</style>
<script>
import Create from "./create";
import Edit from "./edit";
export default {
	components: {Create, Edit },
	data() {
		return {
			auction_section : null,
			auction_sections: [],
			index: 0,
		}
	},
	created() {
        this.getAuctionSection();
	},	
	
	methods: {
		
		getAuctionSection() {
			axios.get('/auction-sections')
				.then(({ data }) => {
					if(data) {
						this.auction_sections = data;
					}
					
				}).catch({});
		},

		setAuctionSection(auction_sections) {
			this.auction_section = auction_sections;
		},

		clearAuctionSection() {
			this.auction_section = null;
			this.index = 0;
		},

		getProperties(properties) {
			if(properties)
				return JSON.parse(properties)
		},

		refreshData() {
			 this.getAuctionSection();
		},

		deleteSection(id) {
			this.$modal.dialog({
				message: 'Are you sure you want to delete this Section?',
				confirmButton: 'Okay',
				cancelButton: 'Cancel',
				title: 'Delete'
			})
			.then(confirmed => {
				axios.delete(`/auction-sections/${id}`)
				.then(()=>{
					this.$modal.success({
						message: 'Section Successfully Deleted',
						title: 'Success'
						});
					// Reload page
					this.refreshData();
				})
				.catch();
			}).catch();
		},

		getHeight(per_row, og_height) {
			console.log(per_row + ' ' + og_height)
			var og_width, new_width, new_height = 0

			if(per_row == 1) {
				og_width = 1253
				new_width = 1038
			} else if(per_row == 2) {
				og_width = 623
				new_width = 511
			} else if(per_row == 3) {
				og_width = 413
				new_width = 338
			}

			new_height = (og_height / og_width) * new_width
			return og_height


		}


    }
}
</script>
