<template>
	<div class="section_add_listing">
		<ul class="step_nav" v-if="$device.isDesktop">
			<li v-for="item, ndx in stepList" :key="item.id" :active="ndx === activeStep">{{ item.text }}</li>
		</ul>
		<ul class="step_nav" v-else>
			<li v-for="item, ndx in stepList" :key="item.id" :active="ndx === activeStep"
			    :class="{'was_active' : ndx <= activeStep}"></li>
		</ul>
		<form>
			<div class="step_content">
				<fieldset v-show="activeStep === 0" transition="fade" mode="out-in">
					<div class="fieldset_inner">
						<header>
							<h2>What would you like to list?</h2>
						</header>
						<CategoryStep v-if="$device.isDesktop" ref="CategoryStep" :listingData="mainListingData"
						              @update="updateListing"/>
						<CategoryStepMobile v-else ref="CategoryStep" :listingData="mainListingData"
						                    @update="updateListing"/>
					</div>
					
					<aside class="tips" v-if="$device.isDesktop">
						<i class="icon-idea"></i>
						<p>
							Select the category that best fits your listing. You’ll be able to get more specific with
							sub-categories next.
						</p>
					</aside>
				</fieldset>
				
				<fieldset v-show="activeStep === 1" transition="fade">
					<div class="fieldset_inner">
						<header>
							<h2>Give us an overview of your trade</h2>
						</header>
						
						<OverviewStep
							v-if="$device.isDesktop"
							ref="OverviewStep"
							data-vv-scope="OverviewStep"
							:listingData="mainListingData"
							@update="updateListing"
						/>
						
						<OverviewStepMobile
							v-else
							ref="OverviewStep"
							data-vv-scope="OverviewStep"
							:listingData="mainListingData"
							@update="updateListing"
						/>
					</div>
					<aside class="tips" v-if="$device.isDesktop">
						<i class="icon-idea"></i>
						<p>
							This description will be the most visible overview of your trade, use keywords that will
							ensure your trade shows up in searches.
						</p>
					</aside>
				</fieldset>
				
				<fieldset v-show="activeStep === 2" transition="fade">
					<div class="fieldset_inner add_photos_mobile">
						<header>
							<h2>Uploads some pictures</h2>
						</header>
						<PhotosStep
							v-if="$device.isDesktop"
							ref="PhotosStep"
							data-vv-scope="PhotosStep"
							:listingData="mainListingData"
							@update="updateListing"
						/>
						<PhotosStepMobile
							v-else
							ref="PhotosStep"
							data-vv-scope="PhotosStep"
							:listingData="mainListingData"
							@update="updateListing"
						/>
					</div>
					<aside class="tips" v-if="$device.isDesktop">
						<i class="icon-idea"></i>
						<p>
							This description will be the most visible overview of your trade, use keywords that will
							ensure your trade shows up in searches.
						</p>
					</aside>
				</fieldset>
				
				<fieldset v-show="activeStep === 3" transition="fade">
					<div class="fieldset_inner">
						<header>
							<h2>Give us some more details</h2>
						</header>
						
						<DetailsStep
							v-if="$device.isDesktop"
							ref="DetailsStep"
							data-vv-scope="DetailsStep"
							:listingData="mainListingData"
							@update="updateListing"
						/>
						<DetailsStepMobile
							v-else
							ref="DetailsStep"
							data-vv-scope="DetailsStep"
							:listingData="mainListingData"
							@update="updateListing"
						/>
					</div>
					<aside class="tips" v-if="$device.isDesktop">
						<i class="icon-idea"></i>
						<p>
							This description will be the most visible overview of your trade, use keywords that will
							ensure your trade shows up in searches.
						</p>
					</aside>
				</fieldset>
				
				<fieldset v-show="activeStep === 4" transition="fade">
					<div class="fieldset_inner">
						<header v-if="$device.isDesktop">
							<h2>Please review the listing details.</h2>
						</header>
						<div class="fieldset_container last_step">
							<SubmitStep v-if="$device.isDesktop" ref="SubmitStep" :listingData="mainListingData" @update="updateListing"/>
							<SubmitStepMobile v-else ref="SubmitStep" :listingData="mainListingData" @update="updateListing"/>
							<div class="actions">
								<button
									type="button"
									role="button"
									class="btn btn__secondary"
									v-if="activeStep > 0"
									@click="prevStep"
									:disabled="activeStep === 0"
								>Back
								</button>
								<button
									type="button"
									role="button"
									class="btn btn__primary"
									@click="submitListing"
									v-if="activeStep === stepList.length - 1 "
								>Submit
								</button>
							</div>
							
							<div class="tips" v-if="$device.isMobileOrTablet">
								<i class="icon-idea"></i>
								<p>Preview your listing before you submit.</p>
								<div class="listing_list" @click.middle="prevent" @click.right="prevent">
									<nuxt-link to="/create-listing/listing-preview" class="listing_item"  >
										<div class="product_image" v-if="mainListingData.photos.length >0"
										     :style="{ backgroundImage: 'url('+ mainListingData.photos[0].url + ')'}"
										     style="background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
										<div class="listing_info">
											<p>
												{{ mainListingData.title }}
												<small>${{ mainListingData.price }}</small>
											</p>
										</div>
									</nuxt-link>
								</div>
							</div>
						</div>
					</div>
					<aside class="tips" v-if="$device.isDesktop">
						<i class="icon-idea"></i>
						<p>Preview your listing before you submit.</p>
						<div class="listing_list" @click.middle="prevent" @click.right="prevent">
							<nuxt-link v-if="mainListingData.id" :to="{name: 'edit-listing-listing-preview-id',params:{id:mainListingData.id }}"
									   class="listing_item"  >
								<div class="product_image" v-if="mainListingData.photos.length >0"
								     :style="{ backgroundImage: 'url('+ mainListingData.photos[0].url + ')'}"
								     style="background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
								<div class="listing_info">
									<p>
										{{ mainListingData.title }}
										<small>${{ mainListingData.price }}</small>
									</p>
								</div>
							</nuxt-link>
							<nuxt-link v-else to="/create-listing/listing-preview" class="listing_item"  >
								<div class="product_image" v-if="mainListingData.photos.length >0"
									 :style="{ backgroundImage: 'url('+ mainListingData.photos[0].url + ')'}"
									 style="background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
								<div class="listing_info">
									<p>
										{{ mainListingData.title }}
										<small>${{ mainListingData.price }}</small>
									</p>
								</div>
							</nuxt-link>
						</div>
					</aside>
				</fieldset>
			</div>
			
			<div class="button_group" v-if="activeStep < stepList.length -1">
				<button
					type="button"
					role="button"
					class="btn btn__secondary"
					v-if="activeStep === 0 && $device.isDesktop"
					@click="cancelSelections()"
				>Cancel
				</button>
				<button
					type="button"
					role="button"
					class="btn btn__secondary"
					v-if="activeStep < stepList.length - 1 && activeStep !== 0 && $device.isDesktop"
					@click="prevStep"
					:disabled="activeStep === 0"
				>Back
				</button>
				<n-link
					to="/"
					class="btn btn__back"
					v-if="activeStep === 0 && $device.isMobileOrTablet"
					:disabled="activeStep === 0"
				>< Back
				</n-link>
				<button
					type="button"
					role="button"
					class="btn btn__back"
					v-if="activeStep < stepList.length - 1 && $device.isMobileOrTablet && activeStep !== 0"
					@click="prevStep"
					:disabled="activeStep === 0"
				>< Back
				</button>
				<button
					type="button"
					role="button"
					class="btn btn__primary"
					@click="nextStep"
					v-if="activeStep < stepList.length - 1"
				>Next
				</button>
			</div>
		</form>
	</div>
</template>

<script>
	import Multiselect from "vue-multiselect";
	import DetailsStep from "~/components/listing/DetailsStep.vue";
	import DetailsStepMobile from "~/components/listing/DetailsStepMobile.vue";
	import SubmitStep from "~/components/listing/SubmitStep.vue";
	import SubmitStepMobile from "~/components/listing/SubmitStepMobile.vue";
	import PhotosStep from "~/components/listing/PhotosStep.vue";
	import PhotosStepMobile from "~/components/listing/PhotosStepMobile.vue";
	import OverviewStep from "~/components/listing/OverviewStep.vue";
	import OverviewStepMobile from "~/components/listing/OverviewStepMobile.vue";
	import CategoryStep from "~/components/listing/CategoryStep.vue";
	import CategoryStepMobile from "~/components/listing/CategoryStepMobile.vue";
	import stepNavigation from "~/assets/js/stepNavigation.js";
	import productImage from "~/assets/img/dashboard/drone.jpg";
	
	export default {
		components: {
			CategoryStep,
			CategoryStepMobile,
			OverviewStep,
			OverviewStepMobile,
			PhotosStep,
			PhotosStepMobile,
			Multiselect,
			DetailsStep,
			DetailsStepMobile,
			SubmitStep,
			SubmitStepMobile
		},
		head: {
			bodyAttrs: {
				class: 'create_listing_page'
			}
		},
		extends: stepNavigation,
		props: ['mainListingData'],
		data() {
			return {
				activeStep: 0,
				stepList: [
					{id: 0, ref: "CategoryStep", text: "Category"},
					{id: 1, ref: "OverviewStep", text: "Overview"},
					{id: 2, ref: "PhotosStep", text: "Photos"},
					{id: 3, ref: "DetailsStep", text: "Details"},
					{id: 4, ref: "SubmitStep", text: "Submit"}
				],
				productImage
			};
		},
		methods: {
			prevent(event) {
				event.preventDefault();
				event.stopPropagation();
				return false;
			},
			updateListing(data) {
				this.$emit('update', data);
			},
			nextStep() {
				this.$validator.validateAll(this.currentStep().ref).then(result => {
					if (result) {
						this.activeStep++;
						scroll(0, 0);
					}
				});
			},
			prevStep() {
				this.activeStep--;
				scroll(0, 0);
			},
			currentStep() {
				let parent = this;
				return this.stepList.find(function (element) {
					return element.id === parent.activeStep;
				});
			},
			cancelSelections() {
				this.$emit('reset');
			},
			async submitListing() {
				this.errors.clear("server");
				this.$validator
					.validateAll(
						this.stepList.map(step => {
							scope: step.ref;
						})
					)
					.then(result => {
						if (result) {
							this.$emit('submit');
						}
					});
			}
		}
	};
</script>

<style lang="scss">
	@import "~assets/scss/abstracts/_abstracts.scss";
	
	.section_add_listing {
		margin-bottom: 46px;
		
		.button_group {
			padding: 28px 15px;
			display: flex;
			justify-content: flex-end;
			width: calc(100% - 400px);
			
			.btn {
				margin-left: 14px;
				padding: 19px 20px 16px;
				min-width: 136px;
			}
		}
		
		.multiselect {
			width: 100%;
			max-width: 480px;
			margin-bottom: 15px;
			height: 45px;
		}
	}
	
	.select_subcategory {
		padding-top: 40px;
		border-top: 1px solid $gray_aluminium;
	}
	
	.create_listing_page {
		@media only screen and (max-width: 968px) {
			background: $white;
		}
		
		.is_mobile {
			.main_footer {
				display: none;
			}
		}
	}
	
	.is_mobile {
		.section_add_listing {
			.button_group {
				width: 100%;
			}
			
			input[type=text]:not(.multiselect__input),
			input[type=email],
			input[type=tel],
			input[type=password],
			input[type=search],
			input[type=number] {
				padding: 0 15px;
			}
			
			textarea {
				padding: 15px;
			}
			
			h2 {
				font-size: 18px;
				line-height: 24px;
				letter-spacing: 0.17px;
				color: $gray_tuna;
			}
		}
		
		.step_content {
			.last_step {
				.tips {
					width: 100%;
					
					p {
						margin: 0;
						font-size: 16px;
						line-height: 42px;
					}
					
					i {
						margin-bottom: 15px;
					}
				}
				
				.listing_list {
					margin-top: 10px;
				}
				
				.actions {
					min-height: auto;
					margin-bottom: 19px;
				}
			}
			
			header {
				padding: 0;
				border-bottom: none;
				margin-bottom: 17px;
			}
			
			.fieldset_container {
				padding: 0;
			}
		}
		
		.actions {
			min-height: auto;
		}
		
		.button_group {
			background: $white;
			position: fixed;
			bottom: 0;
			left: 0;
			right: 0;
			padding: 15px 14px 13px 50px;
			box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.1);
			z-index: 98;
			
			.btn__back {
				position: absolute;
				left: 21px;
				top: 50%;
				transform: translateY(-50%);
				padding: 10px 0;
				font-size: 18px;
				line-height: 24px;
				text-transform: capitalize;
				color: $blue_scooter;
				min-width: auto;
				margin-left: 0;
			}
			
			.btn__primary {
				font-size: 10px;
				letter-spacing: 1px;
				min-width: 87px;
				padding: 18px 30px;
			}
		}
	}
	
	.step_nav {
		display: flex;
		align-items: center;
		justify-content: center;
		text-align: center;
		padding-top: 67px;
		margin-bottom: 27px;
		
		&:before {
			content: "";
			display: block;
			position: absolute;
			width: 606px;
			top: 30.5px;
			left: 50%;
			transform: translateX(-50%);
			height: 1px;
			background: $gray_gallery;
		}
		
		li {
			margin: 0 42px;
			font-size: 18px;
			line-height: 24px;
			color: $gray_aluminium;
			transition: 0.2s $bezier_ease_in_out;
			position: relative;
			
			&:before {
				content: "";
				display: block;
				position: absolute;
				left: 50%;
				transform: translateX(-50%) scale(1);
				top: -41px;
				width: 10px;
				height: 10px;
				border-radius: 50%;
				background: $gray_gallery;
				transition: 0.2s $bezier_ease_in_out;
			}
			
			&[active="true"] {
				color: $gray_tuna;
				
				&:before {
					transform: translateX(-50%) scale(2);
					background: $blue_scooter;
				}
			}
		}
	}
	
	.is_mobile {
		.step_nav {
			display: flex;
			background-color: #ececec;
			padding: 0;
			
			@media only screen and (max-width: 967px) {
				position: relative;
				left: -15px;
				width: calc(100% + 30px);
			}
			
			&:before {
				display: none;
			}
			
			li {
				margin: 0;
				flex: 1;
				background: transparent;
				height: 10px;
				
				&:before {
					display: none;
				}
				
				&.was_active {
					background: $blue_scooter;
					margin-left: -1px;
					
					&:first-child {
						margin-left: 0;
					}
				}
				
				&[active="true"] {
					background: $blue_scooter;
					border-top-right-radius: 100px;
					border-bottom-right-radius: 100px;
				}
			}
		}
		
		.multiselect__tags {
			display: flex;
			align-items: center;
			padding-top: 5px;
			padding-bottom: 2px;
		}
	}
	
	.step_content {
		padding: 0 15px;
		
		@media only screen and (max-width: 1659px) {
			padding: 0;
		}
		
		.select_subcategory {
			padding: 40px 31px 12px;
			border-top: 1px solid rgba(170, 174, 179, .17);
			
			h2 {
				margin-bottom: 15px;
			}
		}
		
		fieldset {
			@include clearfix;
			display: block;
			
			::-webkit-input-placeholder {
				opacity: 0.6;
			}
			
			::-moz-placeholder {
				opacity: 0.6;
			}
			
			:-ms-input-placeholder {
				opacity: 0.6;
			}
			
			:-moz-placeholder {
				opacity: 0.6;
			}
		}
		
		.actions {
			margin-top: 40px;
			min-height: 200px;
			
			.btn {
				display: inline-block;
				margin-right: 15px;
				min-width: 137px;
			}
		}
		
		.fieldset_inner {
			width: calc(100% - 400px);
			background: $white;
			border-radius: 2px;
			border: none;
			padding: 0 0 33px;
			float: left;
			
			@media only screen and (max-width: 1659px) {
				width: calc(100% - 310px);
			}
			
			@media only screen and (max-width: 1260px) {
				width: 100%;
			}
		}
		
		.fieldset_container {
			padding: 0 34px;
			
			label {
				font-size: 10px;
				letter-spacing: 2px;
				color: $gray_aluminium;
				margin-bottom: 7px;
				display: block;
				width: 100%;
			}
			
			.form_group {
				margin-bottom: 15px;
				display: flex;
				justify-content: space-between;
				flex-wrap: wrap;
				
				.trade_type {
					display: flex;
					flex-wrap: wrap;
					/*align-items: center;*/
					margin-bottom: 18px;
					
					label {
						width: auto;
					}
					
					> label {
						width: 100%;
					}
					
					.custom-checkbox {
						width: 50%;
					}
				}
			}
			
			button {
				label[for="switch2"] {
					display: flex;
				}
			}
			
			input[type="text"]:not(.multiselect__input),
			input[type="email"],
			input[type="email"],
			input[type="tel"],
			input[type="password"],
			input[type="search"],
			select,
			textarea {
				color: $gray_aluminium;
				margin-bottom: 15px;
				
				&:focus {
					color: $gray_shark;
				}
			}
			
			textarea {
				height: 178px;
			}
		}
		
		header {
			padding: 38px 31px 30px;
			border-bottom: 1px solid rgba($gray_aluminium, 0.17);
			margin-bottom: 38px;
		}
		
		h2 {
			font-size: 18px;
			line-height: 24px;
			color: $gray_aluminium;
		}
		
		.listing_type {
			margin-bottom: 15px;
		}
		
		.tips {
			width: 380px;
			background: $white;
			border-radius: 4px;
			border: 1px solid $gray_aluminium;
			padding: 34px 11px 34px 20px;
			float: right;
			
			@media only screen and (max-width: 1659px) {
				width: 290px;
				padding: 25px 15px;
			}
			
			p {
				font-size: 16px;
				line-height: 21px;
				color: $gray_aluminium;
				
				@media only screen and (max-width: 1659px) {
					font-size: 14px;
					line-height: 19px;
				}
			}
			
			i {
				font-size: 38px;
				color: $blue_scooter;
				margin-bottom: 35px;
				display: inline-block;
				
				@media only screen and (max-width: 1659px) {
					font-size: 33px;
					margin-bottom: 20px;
				}
			}
			
			.listing_list {
				border-radius: 3px;
				border: 1px solid $gray_gallery;
				margin-top: 40px;
			}
			
			.listing_item {
				display: flex;
				align-items: center;
			}
			
			.product_image {
				width: 91px;
				height: 91px;
				background-size: cover;
				background-repeat: no-repeat;
				background-position: center;
				background: $gray_aluminium;
			}
			
			.listing_info {
				p {
					font-size: 14px;
					line-height: 18px;
					color: $gray_tuna;
					padding: 5px 30px 5px 18px;
				}
				
				small {
					font-size: 12px;
					line-height: 20px;
					color: $gray_aluminium;
					font-weight: 700;
					letter-spacing: .9px;
					display: block;
					padding-top: 5px;
				}
			}
		}
	}
</style>
