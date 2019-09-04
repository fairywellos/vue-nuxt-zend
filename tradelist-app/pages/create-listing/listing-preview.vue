<template>
	<div class="product_page product_page--preview">
		<div class="container">
			<Header/>
		</div>
		<div class="post_listing_alert">
			<div class="container">
				<div class="alert">
					<p>
						<strong>Preview mode:</strong> This is how others will see your listing
					</p>
					<div class="actions">
						<n-link to="/create-listing" class="btn btn_publish">Back</n-link>
						<button type="button" role="button" class="btn btn_publish" @click="submitListing">Publish</button>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-lg-8">
					<ProductGallery :galleryImages="listingData.photos" v-if="listingData.photos.length !== 0"/>

					<div class="product_details">
						<div
							class="category"
							:style="{color:category(listingData.main_category_id).colorCode}"
						>{{category(listingData.main_category_id).name}}</div>
						<div class="product_title_wrapper">
							<div class="product_title">
								<h1>{{listingData.title}}</h1>
								<div class="owner_info">
									<div v-if="loggedInUser.photo" class="owner_thumbnail"
									     :style="{ backgroundImage: 'url('+ loggedInUser.photo + ')' }"></div>
									<div v-else class="owner_thumbnail"></div>
									<h4 v-if="loggedInUser.publicName">{{loggedInUser.publicName}}</h4>
									<h4 v-else>Unknown</h4>
									<div class="rating">
										<span v-if="loggedInUser.rating">
											<i class="icon-star"></i>
											{{loggedInUser.rating}}
										</span>
										<span v-else>
											<i class="icon-star"></i> N.A
										</span>
										<span>
											<a>Follow</a>
										</span>
									</div>
								</div>
							</div>
							<div class="location_views">
								<span class="location">
									<i class="icon-location"></i>
									{{listingData.locationText}}
								</span>
							</div>
						</div>
					</div>
					<div class="product_details">
						<div class="product_highlights details_section">
							<div class="title">
								<h2>Highlights</h2>
							</div>
							<ul>
								<li>Category: {{category(listingData.main_category_id).name}}</li>
								<li v-for="tag in listingData.listing_tags">Tag: {{tag}}</li>
								<li v-if="listingData.meta_tags">Meta tags: {{listingData.meta_tags}}</li>
							</ul>
						</div>
						<div class="specs details_section">
							<ul class="product_nav_tab">
								<li class="active">
									<a href="#tab1" v-on:click="onTabclick">Summary</a>
								</li>
								<li>
									<a href="#tab2" v-on:click="onTabclick">Specification</a>
								</li>
							</ul>
							<div class="tab_content">
								<div class="container">
									<div class="tab_pane active" id="tab1">{{listingData.description}}</div>
									<div class="tab_pane" id="tab2">
										<p v-if="listingData.brand">Brand: {{listingData.brand}}</p>
										<p v-if="listingData.color">Color: {{listingData.color}}</p>
										<p v-if="listingData.material">Material: {{listingData.material}}</p>
										<p v-if="listingData.year">Year: {{listingData.year}}</p>
										<p v-if="listingData.qty">QTY: {{listingData.qty}}</p>
										<p v-if="listingData.notes">Notes: {{listingData.notes}}</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<aside class="product_offer">
						<header>
							<h3>Make an offer</h3>
						</header>
						<div class="price">
							${{ listingData.price }}
							<small>Listed value</small>
						</div>
						<div class="trader_rating">
							<i class="icon-save"></i>
							<i class="icon-save"></i>
							<i class="icon-save"></i>
							<i class="icon-save"></i>
							<i class="icon-save"></i>
							{{loggedInUser.rating}} Trader rating
						</div>
						<hr>
						<h4>Select item(s)</h4>
						<div class="listing_list">
							<div class="item_wrapper" v-for="(checkedName, index) in checkedNames" :key="index">
								<div class="item">
									<span
										class="listing_img"
										:style="{ backgroundImage: 'url('+ allListings[checkedName].thumbnail + ')'
                                                  }"
									></span>
									<span class="listing_info">
										<p>{{ allListings[checkedName].name }}</p>
										<span>${{ allListings[checkedName].price }}</span>
									</span>
								</div>
							</div>
							<button type="button" class="btn_select" role="button">
								<i class="icon-add"></i>
							</button>
						</div>
						<hr>
						<h4>Cash offer</h4>
						<ul class="cash_offer">
							<li>
								<p>
									Listed value:
									<strong>${{listingData.price}}</strong>
								</p>
							</li>
							<li>
								<p>Other</p>
							</li>
							<li>
								<input type="text" placeholder="$0.00" disabled>
							</li>
						</ul>
						<h4>Your trade value
							<div
								v-if="tradeValue > 0"
								class="trade_val float_right"
							>{{ getPercentageChange(listingData.price, tradeValue) }}%</div>
						</h4>
						<button type="button" class="btn btn__primary" role="button" aria-label="Make offer">
							Make
							offer
						</button>
						<nuxt-link
							to="/dashboard/messages/message-details"
							class="btn btn__secondary"
							title="Message"
						>
							<i class="icon-message"></i> Message
						</nuxt-link>
					</aside>
				</div>
			</div>
		</div>
		<no-ssr>
			<modal
				v-if="showModalListingSuccess"
				class="modal_listing_success success_modal"
				@close="closeModalListingSuccess()"
			>
				<div slot="body">
					<div class="success_icon">
						<i class="icon-success-hand"></i>
					</div>
					<h3>Congratulations!</h3>
					<div
						class="product_image"
						v-if="listingData.photos.length >0"
						:style="{ backgroundImage: 'url('+ listingData.photos[0].url + ')' }"
					></div>
					<p>Your {{listingData.title}} just posted.</p>
					<div class="extra_option">
						<nuxt-link to="/dashboard/offers/my-listings" class="btn btn__primary">Continue</nuxt-link>
					</div>
				</div>
			</modal>
		</no-ssr>
	</div>
</template>

<script>
	import Header from "~/components/Header.vue";
	import ProductGallery from "~/components/ProductGallery.vue";
	import Modal from "~/components/ModalTemplate.vue";
	import { mapGetters } from "vuex";

	export default {
		components: {
			Header,
			ProductGallery,
			Modal
		},
		head() {
			return {
				title: "Listing Details",
				meta: [
					{
						hid: "description",
						name: "description",
						content: "Listing details description"
					}
				]
			};
		},

		data() {
			return {
				listingData: this.$store.getters["newListing/getData"],
				showModalListings: false,
				checkedNames: [],
				tradeValue: [],
				showModalListingSuccess: false
			};
		},
		methods: {
			onTabclick(event) {
				event.preventDefault();

				var actives = document.querySelectorAll(".active");

				// deactivate existing active tab and panel
				for (var i = 0; i < actives.length; i++) {
					actives[i].className = actives[i].className.replace(
						"active",
						""
					);
				}

				// activate new tab and panel
				event.target.parentElement.className += " active";
				document.getElementById(
					event.target.href.split("#")[1]
				).className += " active";
			},
			openModalListings() {
				this.showModalListings = true;
			},
			closeModalListings() {
				this.showModalListings = false;
			},
			openModalListingSuccess() {
				this.showModalListingSuccess = true;
			},
			closeModalListingSuccess() {
				this.showModalListingSuccess = false;
			},
			category(id) {
				if (id) {
					return this.$store.getters["mainCategories/getCategory"](id);
				} else {
					return { name: "", slug: "" };
				}
			},
			async submitListing(data) {
				let parent = this;
				return await this.$store
					.dispatch("newListing/sendListing")
					.then(response => {
						parent.$store.commit("newListing/resetStateData");
						this.openModalListingSuccess();
						this.$gtag('event', 'conversion', {'send_to': 'AW-729655576/PKo5CMmt3qYBEJjS9tsC'});
						this.$gtag('event', 'generate_lead');  
						this.$fb('track', 'Lead', { value: 10, currency: 'USD'   });
						this.$fb('track', 'SubmitApplication');
						// parent.$router.push({ name: "index" });
					})
					.catch(serverError => {
						serverError = serverError.response.data.result;

						for (let fieldName in serverError) {
							this.errors.add({
								field: fieldName,
								msg: Object.values(serverError[fieldName]).join(
									"; "
								),
								scope: "server"
							});
						}
					});
			}
		},
		computed: {
			...mapGetters("auth", ["loggedInUser"])
		}
	};
</script>


<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";

	.trade_items_list {
		padding: 0 0 19px;
	}

	.post_listing_alert {
		background: $saffron;

		p {
			font-size: 18px;
			line-height: 24px;
			font-weight: 400;
			color: $white;
		}

		.alert {
			padding: 25px 0;
			display: flex;
			align-items: center;
			justify-content: space-between;
		}

		.actions {
			display: flex;
			align-items: center;
		}

		button {
			margin-left: 25px;
		}

		.btn_publish {
			color: $white;
			border: 2px solid $white;
			min-width: 100px;
			font-weight: 700;
			padding: 10px 20px;
			font-size: 10px;
			line-height: 13px;
			letter-spacing: 1px;
		}
	}

	.product_gallery {
		margin-bottom: 21px;
		min-height: 300px;

		button[data-controls="prev"],
		button[data-controls="next"] {
			opacity: 0.8;
			border: none;

			&:hover {
				opacity: 1;
			}
		}
	}

	.product_offer {
		border: 1px solid $gray_aluminium;
		border-radius: 4px;
		background: $white;
		margin: 24px 0;
		padding: 0 12px 25px 27px;

		header {
			position: relative;
			margin-left: -27px;
			width: calc(100% + 40px);
			border-bottom: 1px solid $gray_aluminium;
			padding: 37px 31px 29px;
			margin-bottom: 19px;
		}

		h3 {
			font-size: 18px;
			line-height: 24px;
			color: $gray_tuna;
		}

		h4 {
			@include clearfix;
			font-size: 10px;
			line-height: 13px;
			text-transform: uppercase;
			color: $gray_aluminium;
			letter-spacing: 2px;
			margin-bottom: 12px;

			.trade_val {
				font-size: 18px;
				line-height: 24px;
				color: $gray_tuna;
				font-weight: 300;
				padding-right: 20px;
				position: relative;

				&:after {
					content: "";
					position: absolute;
					top: 50%;
					right: 0;
					transform: translateY(-50%);
					width: 0;
					height: 0;
					border-left: 7px solid transparent;
					border-right: 7px solid transparent;

					border-bottom: 8px solid $shamrock;
				}
			}
		}

		hr {
			margin: 0 0 15.5px;
			border: none;
			height: 1px;
			width: 100%;
			background: rgba($gray_aluminium, 0.23);
		}

		.price {
			font-size: 38px;
			line-height: 50px;
			color: $gray_tuna;
			font-weight: 700;

			small {
				font-size: 14px;
				color: $gray_tuna;
			}
		}

		.trader_rating {
			font-size: 10px;
			line-height: 20px;
			color: $gray_aluminium;
			margin-bottom: 14.5px;

			i {
				color: $saffron;
				font-size: 12px;
			}
		}

		.btn_select {
			width: 100%;
			max-width: 228px;
			height: 90px;
			border: 1px solid $gray_gallery;
			border-radius: 3px;
			background: $gray_albaster;
			line-height: 90px;
			display: flex;
			align-items: center;
			justify-content: center;
			margin-bottom: 17.5px;
			transition: 0.2s $bezier_ease_in_out;

			&:hover {
				box-shadow: 0 1px 7px 0 rgba(0, 0, 0, 0.3);
			}

			i {
				color: $blue_scooter;
				font-size: 16px;
			}
		}

		.cash_offer {
			padding-left: 27px;
			margin-bottom: 44px;

			li {
				margin-bottom: 3px;
			}

			p {
				font-size: 12px;
				line-height: 28px;
				color: $gray_aluminium;

				strong {
					color: #1b001c;
				}
			}

			input {
				margin-top: 4px;
				max-width: 146px;
				height: 50px;
			}
		}

		.btn {
			display: block;
			width: 100%;
		}

		.btn__primary {
			margin: 25px 0 17px;
		}

		.btn__secondary {
			border: none;
			color: $gray_tuna;
			padding: 6px 20px;
			text-transform: capitalize;
			font-size: 12px;

			i {
				color: $gray_tuna;
				margin-right: 10px;
				font-size: 16px;
				vertical-align: middle;
			}

			&:hover {
				box-shadow: none;
			}
		}
	}

	.product_page {
		&--preview {
			.product_offer {
				.btn {
					pointer-events: none;
				}
			}
		}
		.page_section {
			padding-top: 90px;
		}

		.listing_list {
			display: flex;
			flex-wrap: wrap;

			input {
				position: absolute;
				opacity: 0;
				visibility: hidden;
				z-index: -9;

				&:checked {
					& + label {
						border: 2px solid $shamrock_darker;
					}
				}
			}

			.item_wrapper {
				& + .btn_select {
					width: 121px;
				}

				a {
					text-decoration: none;
					border: 1px solid $gray_ghost;
					background: $white;
					padding: 38px 0 37px;
					font-size: 10px;
					letter-spacing: 1px;
					text-align: center;
					color: $gray_aluminium;
					text-transform: uppercase;
					line-height: 18px;
					width: 304px;
					display: block;
					border-radius: 5px;
					margin-bottom: 6px;

					i {
						margin-right: 10px;
						font-size: 16px;
						vertical-align: middle;
					}
				}
			}

			.item {
				display: flex;
				align-items: center;
				margin-right: 10px;
				border-radius: 3px;
				min-width: 297px;
				background: $gray_albaster;
				border: 1px solid $gray_gallery;
				transition: 0.2s $bezier_ease_in_out;
				margin-bottom: 6px;
			}

			label.item {
				cursor: pointer;
				min-width: 304px;
			}

			.listing_img {
				width: 90px;
				height: 90px;
				margin-right: 19px;
				background-color: $gray_aluminium;
			}

			.listing_info {
				width: calc(100% - 109px);
				padding-right: 15px;

				p {
					font-size: 14px;
					color: $gray_tuna;
					line-height: 18px;
					margin: 0 0 5px;
				}

				span {
					font-size: 12px;
					line-height: 16px;
					color: $gray_aluminium;
					letter-spacing: 0.9px;
					display: block;
				}
			}
		}
	}

	.product_details {
		padding-top: 25px;

		.details_section {
			background: $white;
			margin-bottom: 12px;
			padding: 0 31px;
		}

		.category {
			text-transform: uppercase;
			font-size: 18px;
			letter-spacing: 0;
		}

		.product_title_wrapper {
			margin-bottom: 47px;

			h1 {
				font-size: 38px;
				line-height: 50px;
				color: $black;
				font-weight: 400;
			}

			.product_title {
				display: flex;
				justify-content: space-between;
				margin-right: 20px;
			}

			.owner_info {
				text-align: center;

				h4 {
					font-size: 14px;
					line-height: 18px;
					color: $gray_tuna;
					margin-bottom: 6px;
				}

				.rating {
					font-size: 12px;
					line-height: 16px;

					span {
						color: $gray_aluminium;

						a {
							color: $blue_picton;
							text-decoration: none;
						}

						& + span {
							padding-left: 12px;
							position: relative;

							&:before {
								content: "";
								position: absolute;
								top: 50%;
								left: 2px;
								transform: translateY(-50%);
								width: 3px;
								height: 3px;
								border-radius: 50%;
								background: #aaaeb3;
							}
						}
					}
				}
			}

			.owner_thumbnail {
				width: 109px;
				height: 109px;
				border-radius: 50%;
				background-color: $gray_alto;
				margin-bottom: 13px;
			}

			.location_views {
				span {
					font-size: 18px;
					line-height: 24px;
					color: $gray_tuna;
					margin-right: 13px;
				}

				i {
					color: $gray_tuna;
				}

				.icon-location {
					font-size: 22px;
				}

				.icon-view {
					font-size: 22px;
				}
			}
		}

		.delivery_info {
			display: flex;
			justify-content: space-between;
			flex-wrap: wrap;
			align-items: center;
			margin-bottom: 44px;

			li {
				display: flex;
				align-items: center;
				width: 325px;
				margin-bottom: 15px;
			}

			.rounded_icon {
				display: block;
				width: 110px;
				height: 110px;
				background: $white;
				border-radius: 50%;
				margin-right: 19px;
			}

			p {
				font-size: 22px;
				line-height: 29px;
				color: $black;
				max-width: 195px;

				small {
					display: block;
					font-size: 16px;
					color: rgba($black, 0.4);
				}
			}
		}

		.title {
			position: relative;
			margin-left: -31px;
			width: calc(100% + 62px);
			padding: 38px 31px 30px;
			border-bottom: 1px solid rgba($gray_iron, 0.17);

			h2 {
				font-size: 18px;
				line-height: 24px;
				color: $gray_aluminium;
			}
		}

		.product_highlights {
			ul {
				padding: 27px 25px 29px;
				list-style: disc;

				li {
					font-size: 16px;
					line-height: 21px;
					color: #393c40;
				}
			}
		}

		.trades_interests {
			.categories {
				position: relative;
				margin-left: -31px;
				width: calc(100% + 62px);
				padding: 25px 34px 29px;
				display: flex;
				border-bottom: 1px solid rgba($gray_iron, 0.17);

				li {
					display: flex;
					flex-wrap: wrap;
					justify-content: center;
					align-items: center;
					margin-right: 30px;
					text-align: center;
				}

				p {
					width: 100%;
					font-size: 14px;
					line-height: 18px;
					font-weight: 700;
					margin-top: 8px;
					margin: 0;
				}

				img {
					margin-bottom: 8px;
				}
			}

			h4 {
				padding-top: 15px;
				font-size: 10px;
				line-height: 13px;
				letter-spacing: 2px;
				color: $gray_aluminium;
				margin-bottom: 20px;
				text-transform: uppercase;
			}
		}

		.tab_content {
			padding: 27px 0 33px;

			.tab_pane {
				position: absolute;
				opacity: 0;
				transition: 0.2s $bezier_ease_in_out;
				z-index: -9999;
			}

			.tab_pane {
				&.active {
					position: static;
					opacity: 1;
					z-index: auto;
				}
			}
		}

		.specs {
			.product_nav_tab {
				display: flex;
				position: relative;
				padding: 0 31px;
				margin-left: -31px;
				width: calc(100% + 62px);
				border-bottom: 1px solid rgba($gray_iron, 0.17);

				li {
					&.active {
						a:after {
							opacity: 1;
						}
					}
				}

				a {
					position: relative;
					display: block;
					font-size: 18px;
					line-height: 24px;
					color: $gray_tuna;
					padding: 40px 11px 28px;

					&:after {
						content: "";
						position: absolute;
						bottom: -1px;
						left: 50%;
						transform: translateX(-50%);
						width: 75%;
						height: 2px;
						background-color: $shamrock_darker;
						opacity: 0;
						transition: 0.2s $bezier_ease_in_out;
						border-radius: 3px;
					}
				}
			}

			p {
				font-size: 16px;
				line-height: 21px;
				color: $gray_tuna;
				margin-bottom: 30px;
			}
		}
	}
</style>
