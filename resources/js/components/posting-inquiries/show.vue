<template>

	<div class="">
		<div class="intro-y overflow-hidden">
			<div class="mt-12">
				<div class="intro-y box p-5">
					<div class="border border-slate-200/60 dark:border-darkmode-400 rounded-md p-5">
						<div
							class="font-medium text-base flex items-center border-b border-slate-200/60 dark:border-darkmode-400 pb-5">
							<a v-on:click="$router.go(-2)" class="flex cursor-pointer">
								<svg class="fill-current w-5 h-5" viewBox="0 0 448 512">
									<path
										d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z" />
								</svg>
							</a>
						</div>
						<div class="" v-if="posting_inquiry.account_executive_id == account_executive">
							<!-- SubTask of each Status -->
							<div v-if="posting_inquiry.account_executive_id"
								class="form-inline items-start flex-col xl:flex-row mt-2 pt-2 first:mt-0 first:pt-0">
								<div class="form-label xl:w-64 xl:!mr-10">
									<div class="text-left">
										<div class="flex items-center">
											<div class="font-medium">SUB TASKS</div>
										</div>
										<div class="leading-relaxed text-slate-500 text-xs mt-3">
											Create a SubTasks for each Status.
										</div>
									</div>
								</div>
								<div class="w-full xl:mt-0 flex-1">
									<div
										class="relative pl-5 pr-5 xl:pr-10 py-10 bg-slate-50 dark:bg-transparent dark:border rounded-md">
										<div class="form-inline mt-5 items-start first:mt-0">
											<label class="form-label mt-2 sm:w-10">Status</label>
										</div>
										<div class="pr-10 pl-24 mt-5">
											<div id="faq-accordion-2" class="accordion accordion-boxed">
												<div class="accordion-item">
													<div id="faq-accordion-content-5" class="accordion-header">
														<button class="accordion-button" type="button"
															data-tw-toggle="collapse"
															data-tw-target="#faq-accordion-collapse-5"
															aria-expanded="true"
															aria-controls="faq-accordion-collapse-5">
															<span class="text-theme-9">IN PROGRESS</span>
														</button>
													</div>
													<div id="faq-accordion-collapse-5"
														class="accordion-collapse collapse show"
														aria-labelledby="faq-accordion-content-5"
														data-tw-parent="#faq-accordion-2">
														<in-progress :account_executive="account_executive"
															:developer="developer" :posting_inquiry="posting_inquiry" />
													</div>
												</div>
												<div class="accordion-item">
													<div id="faq-accordion-content-6" class="accordion-header">
														<button class="accordion-button collapsed" type="button"
															data-tw-toggle="collapse"
															data-tw-target="#faq-accordion-collapse-6"
															aria-expanded="false"
															aria-controls="faq-accordion-collapse-6">
															<span class="text-theme-7">ANSWERED</span>
														</button>
													</div>
													<div id="faq-accordion-collapse-6"
														class="accordion-collapse collapse"
														aria-labelledby="faq-accordion-content-6"
														data-tw-parent="#faq-accordion-2">
														<answered :account_executive="account_executive"
															:developer="developer" :posting_inquiry="posting_inquiry" />
													</div>
												</div>
												<div class="accordion-item">
													<div id="faq-accordion-content-7" class="accordion-header">
														<button class="accordion-button collapsed" type="button"
															data-tw-toggle="collapse"
															data-tw-target="#faq-accordion-collapse-7"
															aria-expanded="false"
															aria-controls="faq-accordion-collapse-7">
															<span class="text-theme-12">ON HOLD</span>
														</button>
													</div>
													<div id="faq-accordion-collapse-7"
														class="accordion-collapse collapse"
														aria-labelledby="faq-accordion-content-7"
														data-tw-parent="#faq-accordion-2">
														<on-hold :account_executive="account_executive"
															:developer="developer" :posting_inquiry="posting_inquiry" />
													</div>
												</div>
												<div class="accordion-item">
													<div id="faq-accordion-content-8" class="accordion-header">
														<button class="accordion-button collapsed" type="button"
															data-tw-toggle="collapse"
															data-tw-target="#faq-accordion-collapse-8"
															aria-expanded="false"
															aria-controls="faq-accordion-collapse-8">
															<span class="text-theme-6">CANCELLED</span>
														</button>
													</div>
													<div id="faq-accordion-collapse-8"
														class="accordion-collapse collapse"
														aria-labelledby="faq-accordion-content-8"
														data-tw-parent="#faq-accordion-2">
														<cancelled :account_executive="account_executive"
															:developer="developer" :posting_inquiry="posting_inquiry" />
													</div>
												</div>
												<div class="accordion-item">
													<div id="faq-accordion-content-8" class="accordion-header">
														<button class="accordion-button collapsed" type="button"
															data-tw-toggle="collapse"
															data-tw-target="#faq-accordion-collapse-9"
															aria-expanded="false"
															aria-controls="faq-accordion-collapse-8">
															<span class="text-theme-10">CLOSED</span>
														</button>
													</div>
													<div id="faq-accordion-collapse-9"
														class="accordion-collapse collapse"
														aria-labelledby="faq-accordion-content-8"
														data-tw-parent="#faq-accordion-2">
														<closed :account_executive="account_executive"
															:developer="developer" :posting_inquiry="posting_inquiry" />
													</div>
												</div>
											</div>
										</div>

									</div>
								</div>
							</div>
							<!-- ACTIVITIES -->
							<div class="form-inline items-start flex-col xl:flex-row mt-2 pt-2 first:mt-0 first:pt-0">
								<div class="form-label xl:w-64 xl:!mr-10">
									<div class="text-left">
										<div class="flex items-center">
											<div class="font-medium">ACTIVITIES</div>
										</div>
										<div class="leading-relaxed text-slate-500 text-xs mt-3">
											Create a to-do list as simple or detailed as you need.

										</div>
									</div>
								</div>
								<div class="w-full xl:mt-0 flex-1">
									<div
										class="relative pl-5 pr-5 xl:pr-10 py-10 bg-slate-50 dark:bg-transparent dark:border rounded-md">
										<div class="form-inline mt-5 items-start first:mt-0">
											<label class="form-label mt-2 sm:w-10">Activity</label>
											<draggable v-model="form.checklist" @change="updateCheckList()"
												group="checklist"
												:options="{ handle: '.item', animation: 150, scrollSensitivity: 200, forceFallback: true }"
												class="w-full" style="max-height: 900px;">
												<div class="flex-1" v-for="(list, index) in form.checklist">
													<div class="xl:flex items-center mt-5 first:mt-0">

														<div class="input-group mr-2">
															<span>{{ index + 1 }}</span>
															<input v-model="list.finished" @change="updateCheckList()"
																class="form-check-input border border-slate-500 w-6 h-6 ml-2"
																type="checkbox">
														</div>
														<div class="input-group flex-1">
															<input v-if="!list.finished" v-model="list.item"
																@keyup.enter="updateCheckList()" type="text"
																class="form-control w-full"
																placeholder="Press Enter to save Activity">

															<input v-if="list.finished" v-model="list.item"
																@keyup.enter="updateCheckList()" type="text"
																class="form-control w-full" placeholder="" disabled>

															<span v-if="!list.finished" class="input-group-text">In
																Progress</span>
															<span v-if="list.finished"
																class="input-group-text">Completed</span>
															<span
																class="text-theme-6 my-2 flex items-center text-2xs ml-2"
																v-if="form.errors.has('checklist.' + index + '.item')">
																Activity Field is Required
															</span>
														</div>
														<div class="w-10 flex text-slate-500 mt-3 xl:mt-0">
															<a href="#" class="item xl:ml-5">
																<svg xmlns="http://www.w3.org/2000/svg" width="24"
																	height="24" viewBox="0 0 24 24" fill="none"
																	stroke="currentColor" stroke-width="2"
																	stroke-linecap="round" stroke-linejoin="round"
																	icon-name="move" data-lucide="move"
																	class="lucide lucide-move w-4 h-4">
																	<polyline points="5 9 2 12 5 15"></polyline>
																	<polyline points="9 5 12 2 15 5"></polyline>
																	<polyline points="15 19 12 22 9 19"></polyline>
																	<polyline points="19 9 22 12 19 15"></polyline>
																	<line x1="2" y1="12" x2="22" y2="12"></line>
																	<line x1="12" y1="2" x2="12" y2="22"></line>
																</svg>
															</a>
															<a href="#" v-if="!list.finished"
																@click.prevent="deleteRow(index)" class="ml-3 xl:ml-5">
																<svg xmlns="http://www.w3.org/2000/svg" width="24"
																	height="24" viewBox="0 0 24 24" fill="none"
																	stroke="currentColor" stroke-width="2"
																	stroke-linecap="round" stroke-linejoin="round"
																	icon-name="trash-2" data-lucide="trash-2"
																	class="lucide lucide-trash-2 w-4 h-4">
																	<polyline points="3 6 5 6 21 6"></polyline>
																	<path
																		d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2">
																	</path>
																	<line x1="10" y1="11" x2="10" y2="17"></line>
																	<line x1="14" y1="11" x2="14" y2="17"></line>
																</svg>
															</a>

															<a href="#" v-if="list.finished" class="ml-3 xl:ml-5"
																disabled>
																<svg xmlns="http://www.w3.org/2000/svg" width="24"
																	height="24" viewBox="0 0 24 24" fill="none"
																	stroke="currentColor" stroke-width="2"
																	stroke-linecap="round" stroke-linejoin="round"
																	icon-name="trash-2" data-lucide="trash-2"
																	class="lucide lucide-trash-2 w-4 h-4">
																	<polyline points="3 6 5 6 21 6"></polyline>
																	<path
																		d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2">
																	</path>
																	<line x1="10" y1="11" x2="10" y2="17"></line>
																	<line x1="14" y1="11" x2="14" y2="17"></line>
																</svg>
															</a>
														</div>
													</div>
												</div>
											</draggable>
										</div>
										<div class="pr-10 pl-24 mt-5">
											<button class="btn btn-outline-primary border-dashed w-full"
												@click.prevent="addRow()">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
													viewBox="0 0 24 24" fill="none" stroke="currentColor"
													stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
													icon-name="plus" data-lucide="plus"
													class="lucide lucide-plus w-4 h-4 mr-2">
													<line x1="12" y1="5" x2="12" y2="19"></line>
													<line x1="5" y1="12" x2="19" y2="12"></line>
												</svg> Add Activity
											</button>
										</div>

									</div>
								</div>
							</div>
							<!-- FORM UPLOAD -->
							<div class="form-inline items-start flex-col xl:flex-row mt-2 pt-2 first:mt-0 first:pt-0">
								<div class="form-label xl:w-64 xl:!mr-10">
									<div class="text-left">
										<div class="flex items-center">
											<div class="font-medium">FILE MANAGER</div>
										</div>
										<div class="leading-relaxed text-slate-500 text-xs mt-3">
											Upload your attachments here.
										</div>
									</div>
								</div>
								<div class="w-full mt-3 xl:mt-0 flex-1">
									<div
										class="relative pl-5 pr-5 xl:pr-10 py-10 bg-slate-50 dark:bg-transparent dark:border rounded-md">
										<div>
											<!-- FILE UPLOAD -->
											<div
												class="w-full mt-3 xl:mt-0 flex-1 dark:border-darkmode-400 rounded-md pt-4">
												<div class="grid grid-cols-10 gap-5 pl-4 pr-5">
													<div v-for="(file, index) in files"
														class="col-span-5 md:col-span-2 h-28 relative cursor-pointer rounded-md border-2">
														<a href="#" @click.prevent="downloadFile(file.file)">
															<div class="flex flex-col items-center justify-center">
																<div class="text-theme-13 mt-4"
																	v-html="getExt(file.file)">
																</div>
																<!-- <span class="break-all" v-text="file.file"></span> -->
															</div>
															<div
																class="block font-medium mt-4 text-center  items-center justify-center truncate">
																<span class="break-all"> {{
																	file.file |
																		limitDescription(20)
																}}</span>
															</div>
														</a>
														<a href="#" @click.prevent="deleteFile(file.id)">
															<div
																class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-theme-6 right-0 top-0 -mr-2 -mt-2">
																<svg xmlns="http://www.w3.org/2000/svg" width="24"
																	height="24" viewBox="0 0 24 24" fill="none"
																	stroke="currentColor" stroke-width="2"
																	stroke-linecap="round" stroke-linejoin="round"
																	icon-name="x" data-lucide="x"
																	class="lucide lucide-x w-4 h-4">
																	<line x1="18" y1="6" x2="6" y2="18"></line>
																	<line x1="6" y1="6" x2="18" y2="18"></line>
																</svg>
															</div>
														</a>
													</div>
												</div>
												<file-upload method="POST"
													:url="'/file/' + $route.params.id + '/upload'" />
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="" v-if="posting_inquiry.account_executive_id != account_executive">
							<!-- SubTask of each Status -->
							<div v-if="posting_inquiry.account_executive_id"
								class="form-inline items-start flex-col xl:flex-row mt-2 pt-2 first:mt-0 first:pt-0">
								<div class="form-label xl:w-64 xl:!mr-10">
									<div class="text-left">
										<div class="flex items-center">
											<div class="font-medium">SUB TASKS</div>
										</div>
										<div class="leading-relaxed text-slate-500 text-xs mt-3">
											Create a SubTasks for each Status.
										</div>
									</div>
								</div>
								<div class="w-full xl:mt-0 flex-1">
									<div
										class="relative pl-5 pr-5 xl:pr-10 py-10 bg-slate-50 dark:bg-transparent dark:border rounded-md">
										<div class="form-inline mt-5 items-start first:mt-0">
											<label class="form-label mt-2 sm:w-10">Status</label>
										</div>
										<div class="pr-10 pl-24 mt-5">
											<div id="faq-accordion-2" class="accordion accordion-boxed">
												<div class="accordion-item">
													<div id="faq-accordion-content-5" class="accordion-header">
														<button class="accordion-button" type="button"
															data-tw-toggle="collapse"
															data-tw-target="#faq-accordion-collapse-5"
															aria-expanded="true"
															aria-controls="faq-accordion-collapse-5">
															<span class="text-theme-9">IN PROGRESS</span>
														</button>
													</div>
													<div id="faq-accordion-collapse-5"
														class="accordion-collapse collapse show"
														aria-labelledby="faq-accordion-content-5"
														data-tw-parent="#faq-accordion-2">
														<in-progress :account_executive="account_executive"
															:developer="developer" :posting_inquiry="posting_inquiry" />
													</div>
												</div>
												<div class="accordion-item">
													<div id="faq-accordion-content-6" class="accordion-header">
														<button class="accordion-button collapsed" type="button"
															data-tw-toggle="collapse"
															data-tw-target="#faq-accordion-collapse-6"
															aria-expanded="false"
															aria-controls="faq-accordion-collapse-6">
															<span class="text-theme-7">ANSWERED</span>
														</button>
													</div>
													<div id="faq-accordion-collapse-6"
														class="accordion-collapse collapse"
														aria-labelledby="faq-accordion-content-6"
														data-tw-parent="#faq-accordion-2">
														<answered :account_executive="account_executive"
															:developer="developer" :posting_inquiry="posting_inquiry" />
													</div>
												</div>
												<div class="accordion-item">
													<div id="faq-accordion-content-7" class="accordion-header">
														<button class="accordion-button collapsed" type="button"
															data-tw-toggle="collapse"
															data-tw-target="#faq-accordion-collapse-7"
															aria-expanded="false"
															aria-controls="faq-accordion-collapse-7">
															<span class="text-theme-12">ON HOLD</span>
														</button>
													</div>
													<div id="faq-accordion-collapse-7"
														class="accordion-collapse collapse"
														aria-labelledby="faq-accordion-content-7"
														data-tw-parent="#faq-accordion-2">
														<on-hold :account_executive="account_executive"
															:developer="developer" :posting_inquiry="posting_inquiry" />
													</div>
												</div>
												<div class="accordion-item">
													<div id="faq-accordion-content-8" class="accordion-header">
														<button class="accordion-button collapsed" type="button"
															data-tw-toggle="collapse"
															data-tw-target="#faq-accordion-collapse-8"
															aria-expanded="false"
															aria-controls="faq-accordion-collapse-8">
															<span class="text-theme-6">CANCELLED</span>
														</button>
													</div>
													<div id="faq-accordion-collapse-8"
														class="accordion-collapse collapse"
														aria-labelledby="faq-accordion-content-8"
														data-tw-parent="#faq-accordion-2">
														<cancelled :account_executive="account_executive"
															:developer="developer" :posting_inquiry="posting_inquiry" />
													</div>
												</div>
												<div class="accordion-item">
													<div id="faq-accordion-content-8" class="accordion-header">
														<button class="accordion-button collapsed" type="button"
															data-tw-toggle="collapse"
															data-tw-target="#faq-accordion-collapse-9"
															aria-expanded="false"
															aria-controls="faq-accordion-collapse-8">
															<span class="text-theme-10">CLOSED</span>
														</button>
													</div>
													<div id="faq-accordion-collapse-9"
														class="accordion-collapse collapse"
														aria-labelledby="faq-accordion-content-8"
														data-tw-parent="#faq-accordion-2">
														<closed :account_executive="account_executive"
															:developer="developer" :posting_inquiry="posting_inquiry" />
													</div>
												</div>
											</div>
										</div>

									</div>
								</div>
							</div>
							<div class="form-inline items-start flex-col xl:flex-row mt-2 pt-2 first:mt-0 first:pt-0">
								<div class="form-label xl:w-64 xl:!mr-10">
									<div class="text-left">
										<div class="flex items-center">
											<div class="font-medium">Tasks</div>
										</div>
										<div class="leading-relaxed text-slate-500 text-xs mt-3">
											Create a to-do list as simple or detailed as you need.
										</div>
									</div>
								</div>
								<div class="w-full xl:mt-0 flex-1">
									<div
										class="relative pl-5 pr-5 xl:pr-10 py-10 bg-slate-50 dark:bg-transparent dark:border rounded-md">
										<div class="form-inline mt-5 items-start first:mt-0">
											<label class="form-label mt-2 sm:w-10">Activity</label>
											<draggable v-model="form.checklist" group="checklist"
												:options="{ handle: '.item', animation: 150, scrollSensitivity: 200, forceFallback: true }"
												class="w-full" style="max-height: 900px;">
												<div class="flex-1" v-for="(list, index) in form.checklist">
													<div class="xl:flex items-center mt-5 first:mt-0">

														<div class="input-group mr-2">
															<span>{{ index + 1 }}</span>
															<input v-model="list.finished"
																class="form-check-input border border-slate-500 w-6 h-6 ml-2"
																type="checkbox" disabled>
														</div>
														<div class="input-group flex-1">
															<input v-model="list.item" type="text"
																class="form-control w-full" placeholder="" disabled>
															<span v-if="!list.finished" class="input-group-text">In
																Progress</span>
															<span v-if="list.finished"
																class="input-group-text">Completed</span>
															<span
																class="text-theme-6 my-2 flex items-center text-2xs ml-2"
																v-if="form.errors.has('checklist.' + index + '.item')">
																Activity Field is Required
															</span>
														</div>
														<div class="w-10 flex text-slate-500 mt-3 xl:mt-0">
															<a href="javascript:;" class="xl:ml-5">
																<svg xmlns="http://www.w3.org/2000/svg" width="24"
																	height="24" viewBox="0 0 24 24" fill="none"
																	stroke="currentColor" stroke-width="2"
																	stroke-linecap="round" stroke-linejoin="round"
																	icon-name="move" data-lucide="move"
																	class="lucide lucide-move w-4 h-4">
																	<polyline points="5 9 2 12 5 15"></polyline>
																	<polyline points="9 5 12 2 15 5"></polyline>
																	<polyline points="15 19 12 22 9 19"></polyline>
																	<polyline points="19 9 22 12 19 15"></polyline>
																	<line x1="2" y1="12" x2="22" y2="12"></line>
																	<line x1="12" y1="2" x2="12" y2="22"></line>
																</svg>
															</a>

															<a href="javascript:;" class="ml-3 xl:ml-5">
																<svg xmlns="http://www.w3.org/2000/svg" width="24"
																	height="24" viewBox="0 0 24 24" fill="none"
																	stroke="currentColor" stroke-width="2"
																	stroke-linecap="round" stroke-linejoin="round"
																	icon-name="trash-2" data-lucide="trash-2"
																	class="lucide lucide-trash-2 w-4 h-4">
																	<polyline points="3 6 5 6 21 6"></polyline>
																	<path
																		d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2">
																	</path>
																	<line x1="10" y1="11" x2="10" y2="17"></line>
																	<line x1="14" y1="11" x2="14" y2="17"></line>
																</svg>
															</a>
														</div>
													</div>
												</div>
											</draggable>
										</div>
										<div class="pr-10 pl-24 mt-5">
											<button class="btn btn-outline-primary border-dashed w-full" disabled
												@click.prevent="addRow()">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
													viewBox="0 0 24 24" fill="none" stroke="currentColor"
													stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
													icon-name="plus" data-lucide="plus"
													class="lucide lucide-plus w-4 h-4 mr-2">
													<line x1="12" y1="5" x2="12" y2="19"></line>
													<line x1="5" y1="12" x2="19" y2="12"></line>
												</svg> Add Activity
											</button>
										</div>

									</div>
								</div>
							</div>
							<div class="form-inline items-start flex-col xl:flex-row mt-2 pt-2 first:mt-0 first:pt-0">
								<div class="form-label xl:w-64 xl:!mr-10">
									<div class="text-left">
										<div class="flex items-center">
											<div class="font-medium">File Manager</div>
										</div>
										<div class="leading-relaxed text-slate-500 text-xs mt-3">
											Upload your attachments here.
										</div>
									</div>
								</div>
								<div class="w-full mt-3 xl:mt-0 flex-1">
									<div
										class="relative pl-5 pr-5 xl:pr-10 py-10 bg-slate-50 dark:bg-transparent dark:border rounded-md">
										<div>
											<!-- FILE UPLOAD -->
											<div
												class="w-full mt-3 xl:mt-0 flex-1 dark:border-darkmode-400 rounded-md pt-4">
												<div class="grid grid-cols-10 gap-5 pl-4 pr-5">
													<div v-for="(file, index) in files"
														class="col-span-5 md:col-span-2 h-28 relative cursor-pointer rounded-md border-2">
														<a href="javascript:;">
															<div class="flex flex-col items-center justify-center">
																<div class="text-theme-13 mt-4"
																	v-html="getExt(file.file)">
																</div>
																<!-- <span class="break-all" v-text="file.file"></span> -->
															</div>
															<div
																class="block font-medium mt-4 text-center  items-center justify-center truncate">
																<span class="break-all"> {{
																	file.file |
																		limitDescription(20)
																}}</span>
															</div>
														</a>
														<a href="javascript:;">
															<div
																class="tooltip w-5 h-5 flex items-center justify-center absolute rounded-full text-white bg-theme-6 right-0 top-0 -mr-2 -mt-2">
																<svg xmlns="http://www.w3.org/2000/svg" width="24"
																	height="24" viewBox="0 0 24 24" fill="none"
																	stroke="currentColor" stroke-width="2"
																	stroke-linecap="round" stroke-linejoin="round"
																	icon-name="x" data-lucide="x"
																	class="lucide lucide-x w-4 h-4">
																	<line x1="18" y1="6" x2="6" y2="18"></line>
																	<line x1="6" y1="6" x2="18" y2="18"></line>
																</svg>
															</div>
														</a>
													</div>
												</div>
												<!-- <file-upload method="POST"
																			:url="'/file/' + $route.params.id + '/upload'" /> -->
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<activity-log :posting_inquiry="posting_inquiry" />
	</div>
</template>
<script>
import draggable from 'vuedraggable';
import ActivityLog from "./activity-log";
import InProgress from "../posting-inquiry-task/in-progress.vue";
import Answered from "../posting-inquiry-task/answered.vue";
import OnHold from "../posting-inquiry-task/on-hold.vue";
import Cancelled from "../posting-inquiry-task/cancelled.vue";
import Closed from "../posting-inquiry-task/closed.vue";
export default {
	props: {
		account_executive: {
			type: String,
			default: null
		},
		developer: {
			type: String,
			default: null
		},
	},
	components: { draggable, ActivityLog, InProgress, Answered, OnHold, Cancelled, Closed },
	data() {
		return {
			posting_inquiry: [],
			posting_inquiry_task: [],
			files: {},
			form: new Form({
				checklist: [],
			}, false),

		}
	},
	created() {
		this.show()
		this.getTask()
	},

	methods: {
		show() {
			axios.get('/posting-inquiries/' + this.$route.params.id)
				.then((response) => {
					this.posting_inquiry = response.data[0];
					this.form.checklist = JSON.parse(response.data.checklist)
					this.showFile();
				})
				.catch((error) => {
					console.log(error);
				});
		},

		showFile() {
			axios.get(`/file/${this.$route.params.id}/upload`, {
				params: Object.assign({}, this.params, {
					subject_id: this.$route.params.id,
					subject_type: 'Inquiry',
				})
			})
				.then((response) => {
					this.files = response.data;
				})
				.catch((error) => {
					console.log(error);
				});
		},

		downloadFile(filename) {

			let link = `download/${filename}`

			window.open(link, "_blank")
		},

		updateCheckList() {
			this.form.patch(`/posting-inquiries/${this.posting_inquiry.id}/checklist`)
				.then((data) => {
					this.form.checklist = JSON.parse(data.checklist)
				});
		},

		addRow() {
			this.form.checklist.push({
				finished: null,
				item: null,
			})
		},

		deleteRow(index) {
			this.form.checklist.splice(index, 1)
			this.updateCheckList()
		},

		getTask() {
			axios.get('/posting-inquiries/' + this.$route.params.id + '/tasks')
				.then((response) => {
					this.posting_inquiry_task = response.data
				})
				.catch((error) => {
					console.log(error);
				});
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
				return "<svg class='fill-current w-10 h-10' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><path d='M464 64H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V112c0-26.51-21.49-48-48-48zm16 336c0 8.822-7.178 16-16 16H48c-8.822 0-16-7.178-16-16V112c0-8.822 7.178-16 16-16h416c8.822 0 16 7.178 16 16v288zM112 232c30.928 0 56-25.072 56-56s-25.072-56-56-56-56 25.072-56 56 25.072 56 56 56zm0-80c13.234 0 24 10.766 24 24s-10.766 24-24 24-24-10.766-24-24 10.766-24 24-24zm207.029 23.029L224 270.059l-31.029-31.029c-9.373-9.373-24.569-9.373-33.941 0l-88 88A23.998 23.998 0 0 0 64 344v28c0 6.627 5.373 12 12 12h360c6.627 0 12-5.373 12-12v-92c0-6.365-2.529-12.47-7.029-16.971l-88-88c-9.373-9.372-24.569-9.372-33.942 0zM416 352H96v-4.686l80-80 48 48 112-112 80 80V352z'/></svg>";

			if (excel.includes(extension))
				return "<svg class='fill-current w-10 h-10' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 384 512'><path d='M369.9 97.9L286 14C277 5 264.8-.1 252.1-.1H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V131.9c0-12.7-5.1-25-14.1-34zm-22.6 22.7c2.1 2.1 3.5 4.6 4.2 7.4H256V32.5c2.8.7 5.3 2.1 7.4 4.2l83.9 83.9zM336 480H48c-8.8 0-16-7.2-16-16V48c0-8.8 7.2-16 16-16h176v104c0 13.3 10.7 24 24 24h104v304c0 8.8-7.2 16-16 16zM211.7 308l50.5-81.8c4.8-8-.9-18.2-10.3-18.2h-4.1c-4.1 0-7.9 2.1-10.1 5.5-31 48.5-36.4 53.5-45.7 74.5-17.2-32.2-8.4-16-45.8-74.5-2.2-3.4-6-5.5-10.1-5.5H132c-9.4 0-15.1 10.3-10.2 18.2L173 308l-59.1 89.5c-5.1 8 .6 18.5 10.1 18.5h3.5c4.1 0 7.9-2.1 10.1-5.5 37.2-58 45.3-62.5 54.4-82.5 31.5 56.7 44.3 67.2 54.4 82.6 2.2 3.4 6 5.4 10 5.4h3.6c9.5 0 15.2-10.4 10.1-18.4L211.7 308z'/></svg>";

			if (word.includes(extension))
				return "<svg class='fill-current w-10 h-10' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 384 512'><path d='M369.9 97.9L286 14C277 5 264.8-.1 252.1-.1H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V131.9c0-12.7-5.1-25-14.1-34zm-22.6 22.7c2.1 2.1 3.5 4.6 4.2 7.4H256V32.5c2.8.7 5.3 2.1 7.4 4.2l83.9 83.9zM336 480H48c-8.8 0-16-7.2-16-16V48c0-8.8 7.2-16 16-16h176v104c0 13.3 10.7 24 24 24h104v304c0 8.8-7.2 16-16 16zm-53.6-246.5c-6.8 32.8-32.5 139.7-33.4 150.3-5.8-29.1-.7 1.6-41.8-150.9-1.4-5.2-6.2-8.9-11.6-8.9h-6.4c-5.4 0-10.2 3.6-11.6 8.9-38.3 142.3-37.4 140.6-39.4 154.7-4.1-23.9 2.1-2.9-34.4-154.4-1.3-5.4-6.1-9.2-11.7-9.2h-7.2c-7.8 0-13.5 7.3-11.6 14.9 9.5 38 34.5 137.4 42.2 168 1.3 5.3 6.1 9.1 11.6 9.1h17c5.4 0 10.2-3.7 11.6-8.9 34.2-127.7 33.5-123.4 36.7-142.9 6.5 31.1.2 7 36.7 142.9 1.4 5.2 6.2 8.9 11.6 8.9h14c5.5 0 13.7-3.7 15.1-9l42.8-168c1.9-7.6-3.8-15-11.6-15h-6.8c-5.7 0-10.6 4-11.8 9.5z'/></svg>";

			if (txt.includes(extension))
				return "<svg class='fill-current w-10 h-10' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 384 512'><path d='M369.9 97.9L286 14C277 5 264.8-.1 252.1-.1H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V131.9c0-12.7-5.1-25-14.1-34zm-22.6 22.7c2.1 2.1 3.5 4.6 4.2 7.4H256V32.5c2.8.7 5.3 2.1 7.4 4.2l83.9 83.9zM336 480H48c-8.8 0-16-7.2-16-16V48c0-8.8 7.2-16 16-16h176v104c0 13.3 10.7 24 24 24h104v304c0 8.8-7.2 16-16 16zm-48-244v8c0 6.6-5.4 12-12 12H108c-6.6 0-12-5.4-12-12v-8c0-6.6 5.4-12 12-12h168c6.6 0 12 5.4 12 12zm0 64v8c0 6.6-5.4 12-12 12H108c-6.6 0-12-5.4-12-12v-8c0-6.6 5.4-12 12-12h168c6.6 0 12 5.4 12 12zm0 64v8c0 6.6-5.4 12-12 12H108c-6.6 0-12-5.4-12-12v-8c0-6.6 5.4-12 12-12h168c6.6 0 12 5.4 12 12z'/></svg>";

			if (pdf.includes(extension))
				return "<svg class='fill-current w-10 h-10' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 384 512'><path d='M369.9 97.9L286 14C277 5 264.8-.1 252.1-.1H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V131.9c0-12.7-5.1-25-14.1-34zm-22.6 22.7c2.1 2.1 3.5 4.6 4.2 7.4H256V32.5c2.8.7 5.3 2.1 7.4 4.2l83.9 83.9zM336 480H48c-8.8 0-16-7.2-16-16V48c0-8.8 7.2-16 16-16h176v104c0 13.3 10.7 24 24 24h104v304c0 8.8-7.2 16-16 16zm-22-171.2c-13.5-13.3-55-9.2-73.7-6.7-21.2-12.8-35.2-30.4-45.1-56.6 4.3-18 12-47.2 6.4-64.9-4.4-28.1-39.7-24.7-44.6-6.8-5 18.3-.3 44.4 8.4 77.8-11.9 28.4-29.7 66.9-42.1 88.6-20.8 10.7-54.1 29.3-58.8 52.4-3.5 16.8 22.9 39.4 53.1 6.4 9.1-9.9 19.3-24.8 31.3-45.5 26.7-8.8 56.1-19.8 82-24 21.9 12 47.6 19.9 64.6 19.9 27.7.1 28.9-30.2 18.5-40.6zm-229.2 89c5.9-15.9 28.6-34.4 35.5-40.8-22.1 35.3-35.5 41.5-35.5 40.8zM180 175.5c8.7 0 7.8 37.5 2.1 47.6-5.2-16.3-5-47.6-2.1-47.6zm-28.4 159.3c11.3-19.8 21-43.2 28.8-63.7 9.7 17.7 22.1 31.7 35.1 41.5-24.3 4.7-45.4 15.1-63.9 22.2zm153.4-5.9s-5.8 7-43.5-9.1c41-3 47.7 6.4 43.5 9.1z'/></svg>";
		},

		deleteFile(id) {
			this.$modal.dialog({
				message: 'Are you sure you want to delete this File?',
				confirmButton: 'Okay',
				cancelButton: 'Cancel',
				title: 'Delete'
			})
				.then(confirmed => {
					axios.delete(`/files/${id}`)
						.then(() => {
							this.$modal.success({
								message: 'File Successfully Deleted',
								title: 'Success'
							});
							// Reload page
							this.showFile();
						})
						.catch();
				}).catch();
		},
	}
}
</script>
