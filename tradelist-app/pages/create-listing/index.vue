<template>
	<div class="page_create_listing">
		<Header v-if="$device.isDesktop"/>
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<div class="create_listing_page_tile" v-if="$device.isDesktop">
			<div class="container">
				<h1>Create a new listing</h1>
			</div>
		</div>
		<div class="container">
			<StepNavigation :mainListingData="listingData" @submit="submitListing" @update="updateListing"
			                @reset="resetListing"/>
		</div>
		<no-ssr>
			<modal v-if="showModalListingSuccess" class="modal_listing_success success_modal"
			       @close="closeModalListingSuccess()">
				<div slot="body">
					<div class="success_icon">
						<i class="icon-success-hand"></i>
					</div>
					<h3>Congratulations!</h3>
					<div class="product_image"  v-if="listingData.photos.length >0"
					     :style="{ backgroundImage: 'url('+ listingData.photos[0].url + ')' }"></div>
					<p>Your {{listingData.title}} just posted.</p>
					<div class="extra_option">
						<n-link
							to="/"
							class="btn btn__primary"
							aria-label="Continue"
						>Continue
						</n-link>
					</div>
				</div>
			</modal>
		</no-ssr>
	</div>
</template>


<script>
	import Header from '~/components/Header';
	import HeaderMobile from "~/components/HeaderMobile";
	import StepNavigation from '~/components/listing/StepNavigation';
	import Modal from "~/components/ModalTemplate.vue";
	import ModalPI from "~/assets/img/create-listing/create-success.jpg";

	export default {
		components: {
			Header,
			HeaderMobile,
			StepNavigation,
			Modal
		},
		middleware: 'auth',
		head() {
			return {
				title: 'Create Listing',
				meta: [
					{hid: 'description', name: 'description', content: 'Create listing description'}
				]
			}
		},
		data() {
			return {
				listingData: this.$store.getters["newListing/getData"],
				showModalListingSuccess: false,
				ModalPI
			};
		},
		methods: {
			openModalListingSuccess() {
				this.showModalListingSuccess = true;
			},
			closeModalListingSuccess() {
				this.showModalListingSuccess = false;
			},
			async submitListing(data) {
				let parent = this;
				return await this.$store.dispatch("newListing/sendListing")
					.then(response => {
						parent.$store.commit("newListing/resetStateData");
						this.openModalListingSuccess();
						// parent.$router.push({ name: "index" });
					}).catch(serverError => {
						serverError = serverError.response.data.result;

						for (let fieldName in serverError) {
							this.errors.add({
								field: fieldName,
								msg: Object.values(
									serverError[fieldName]
								).join("; "),
								scope: "server"
							});
						}
					});
			},
			async updateListing(data) {
				let parent = this;
				this.listingData = await this.$store.dispatch("newListing/updateListing", data)
					.then(response => {
						return parent.$store.getters["newListing/getData"];
					});
			},
			resetListing() {
				this.$store.commit("newListing/resetStateData");
				this.listingData = this.$store.getters["newListing/getData"];
			},
		},
	}
</script>

<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";

	.page_create_listing {
		
		h1 {
			@include open_sans(300);
			font-size: 18px;
			line-height: 24px;
			color: $gray_tuna;
			padding: 30px 0;
		}
	}

	.create_listing_page_tile {
		background: $white;
		margin-bottom: 45px;
	}
	
	.categories_list {
		li {
			label {
				img {
					max-width: 45px;
				}
			}
		}
	}
	
	.is_mobile {
		.page_create_listing {
			background: $white;
		}
	}
</style>
