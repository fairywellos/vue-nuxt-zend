<template>
	<div class="settings__page dashboard">
		<Header v-if="$device.isDesktop"/>
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<div class="container">
			<no-ssr>
				<modal v-if="showModalMessage" class="modal_message" @close="closeModalMessage()">
					<div slot="body">
						<form method="post" @submit.prevent="sendMessage()">
							<div class="message_box_modal">
								<header>
									<div class="user_image sdasdas" v-if="userProfile.photo"
									     :style="{ backgroundImage: 'url('+ userProfile.photo + ')' }"></div>
									<div v-else class="user_image"></div>
									<div class="message_box__to">
										<p>
											<strong>To:</strong>
											<span>{{ userProfile.name }}</span>
										</p>
										<p>
											<strong>Subject:</strong>
											<span>Regarding this product</span>
										</p>
									</div>
									<button class="btn btn__primary">Send Message</button>
								</header>
								<textarea name="sendMessage" v-model="textMessage"
								          placeholder="Enter message here.."></textarea>
							</div>
						</form>
					</div>
				</modal>
			</no-ssr>
			<div class="dashboard_content">
				<fieldset>
					<div class="fieldset__container">
						<div class="fieldset__col">
							<div class="fieldset__box_user">
								<div class="user">
									<div v-if="parseInt(userProfile.sample_account) === 1" class="sample_tag" >
										Sample account
									</div>
									<div v-if="userProfile.photo" class="user_image"
									     :style="{ backgroundImage: 'url('+ userProfile.photo + ')' }"></div>
									<div v-else class="user_image"></div>

									<p>
										{{ userProfile.name}}
										<span class="location">
														<i class="icon-location"></i> {{userProfile.locationText}}
											<!--<small>- local time <span-->
											<!--id="currentTime"></span></small>-->
													</span>
									</p>
									<button v-if="isAuthenticated && loggedInUser.id != userProfile.id"
									        @click="openModalMessage" class="btn btn__primary">
										Send Message
									</button>
								</div>
								<ul class="extra">
									<li>
										<i class="icon-location"></i>
										<p>
											{{userProfile.trades}}
											<small>Trades</small>
										</p>
									</li>
									<li>
										<i class="icon-eye"></i>
										<p>
											{{userProfile.followers}}
											<small>Followers</small>
										</p>
									</li>
									<li>
										<i class="icon-save"></i>
										<p v-if="userProfile.rating">
											{{userProfile.rating}}
											<small>Star Reviews</small>
										</p>
										<p v-else>
											N.A
											<small>Star Reviews</small>
										</p>
									</li>
									<li>
										<i class="icon-calendar"></i>
										<p>
											{{formatDate(userListings.date,"user")}}
											<small>Member since</small>
										</p>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="fieldset__container">
						<div class="fieldset__col">
							<div class="fieldset__box" v-if="userProfile.isEtsyUser">
								<header>
									<h4>
										Affiliate Link
									</h4>
								</header>

								<div class="box__inner">
									<a :href="'https://www.etsy.com/people/' + userProfile.username + '?utm_source=tradelist&utm_medium=api&utm_campaign=api'" target="_blank" rel="nofollow">https://www.etsy.com/people/{{userProfile.username}}?utm_source=tradelist&utm_medium=api&utm_campaign=api</a>
								</div>
							</div>
							<div class="fieldset__box">
								<header>
									<h4>
										Profile Description
									</h4>
								</header>

								<div class="box__inner">
									<p>{{userProfile.description}}</p>
								</div>
							</div>
							<!--<div class="fieldset__box">-->
							<!--<div class="box__inner">-->
							<!--<div class="service">-->
							<!--<h4>Service</h4>-->
							<!--<img src="~/assets/img/dashboard/service.png" alt="yellow lightning icon">-->
							<!--<p>-->
							<!--Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod-->
							<!--tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,-->
							<!--quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo-->
							<!--consequat.Lorem ipsum dolor sit amet-->
							<!--</p>-->
							<!--</div>-->
							<!--<div class="products">-->
							<!--<h4>Products</h4>-->
							<!--<img src="~/assets/img/dashboard/products.png" alt="blue box icon">-->
							<!--<ul>-->
							<!--<li>-->
							<!--Lorem ipsum dolor sit amet, consectetur adipisicing-->
							<!--</li>-->
							<!--<li>-->
							<!--Elit, sed do eiusmod tempor incididunt ut labore et-->
							<!--</li>-->
							<!--<li>-->
							<!--Dolore magna aliqua. Ut enim ad minim veniam, quis-->
							<!--</li>-->
							<!--<li>-->
							<!--Nostrud exercitation ullamco laboris nisi ut aliquip-->
							<!--</li>-->
							<!--</ul>-->
							<!--</div>-->
							<!--</div>-->
							<!--</div>-->
							<!--<div class="fieldset__box">-->
							<!--<header>-->
							<!--<h4>Interested in trading for…</h4>-->
							<!--</header>-->
							<!--<ul class="trade_items_list">-->
							<!--<li class="sporting_goods">-->
							<!--<div class="cat_bg">-->
							<!--<img src="~/assets/img/illustrations/sporting-goods.svg"-->
							<!--alt="sporting goods">-->
							<!--</div>-->
							<!--<p>-->
							<!--<strong>Slingshot Asylum Kiteboard 2018</strong>-->
							<!--</p>-->
							<!--</li>-->
							<!--<li class="experiences">-->
							<!--<div class="cat_bg">-->
							<!--<img src="~/assets/img/illustrations/experiences.svg" alt="experiences">-->
							<!--</div>-->
							<!--<p>-->
							<!--<strong>Tickets to Hamilton</strong> in <strong>Seattle, WA</strong>-->
							<!--</p>-->
							<!--</li>-->
							<!--<li class="services">-->
							<!--<div class="cat_bg">-->
							<!--<img src="~/assets/img/illustrations/services.svg" alt="services">-->
							<!--</div>-->
							<!--<p>-->
							<!--Lanscaping services-->
							<!--</p>-->
							<!--</li>-->
							<!--<li class="homes_housing">-->
							<!--<div class="cat_bg">-->
							<!--<img src="~/assets/img/illustrations/homes-housing.svg" alt="homes housing">-->
							<!--</div>-->
							<!--<p>-->
							<!--<strong>Vacation homes</strong> in <strong>Hood River, OR</strong>-->
							<!--</p>-->
							<!--</li>-->
							<!--<li class="sporting_goods">-->
							<!--<div class="cat_bg">-->
							<!--<img src="~/assets/img/illustrations/sporting-goods.svg"-->
							<!--alt="sporting goods">-->
							<!--</div>-->
							<!--<p>-->
							<!--<strong>Santa Cruz Mountain Bikes</strong>-->
							<!--</p>-->
							<!--</li>-->
							<!--</ul>-->
							<!--</div>-->
							<div class="fieldset__box">
								<header>
									<h4>Listings for trade ({{userListings.length}}) </h4>
								</header>
								<ul class="trade_listings_profile">
									<li v-for="listing in userListings">
										<div class="product_image" v-if="listing.photoName"
										     :style="{ backgroundImage: 'url('+ listing.mainPhotoUrl+ ')' }"></div>
										<div class="product_image placeholder_image" v-else></div>
										<div class="details">
											<p class="title">
												{{listing.title}}
												<span class="price">${{listing.price}}</span>
											</p>
											<p>Creation Date: {{formatDate(listing.dateAdded.date,"listing")}}</p>
											<p>Expiration Date: {{formatDate(listing.availability.date,"listing")}}</p>
											<p>
												Status:
												<span v-if="listingAvailable(listing.availability)"
												      :class="listingStatusColor[listing.status]">{{listingStatus[listing.status]}}</span>
												<span v-else :class="listingStatusColor[0]">Expired</span>
											</p>
										</div>
										<div class="listing_actions">
											<button type="button" @click="goToListing(listing.id)" role="button"
											        class="btn btn__primary">
												Make offer
											</button>
											<button
												v-if="isAuthenticated && loggedInUser.id != userProfile.id && !listingSaved(listing)"
												@click="saveListing(listing,true)" type="button" role="button"
												class="btn btn__secondary">
												Save Listing
											</button>
											<button
												v-if="isAuthenticated && loggedInUser.id != userProfile.id && listingSaved(listing)"
												@click="saveListing(listing,false)" type="button" role="button"
												class="btn btn__primary">
												Remove Listing
											</button>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</fieldset>
			</div>
		</div>
	</div>
</template>

<script>
	import Header from "~/components/Header"
	import HeaderMobile from "~/components/HeaderMobile";
	import DashboardAside from "~/components/DashboardAside"
	import Modal from "~/components/ModalTemplate";
	import {
		mapGetters
	} from 'vuex'
	import moment from "moment";


	export default {
		components: {
			Header,
			HeaderMobile,
			DashboardAside,
			Modal
		},
		head: {
			title: "Edit my profile"
		},
		computed: {
			...mapGetters('auth', ['isAuthenticated', 'loggedInUser'])
		},
		data() {
			return {
				listingStatus: {
					1: "Active",
					2: "Inactive",
					3: "Traded"
				},
				listingStatusColor: {
					0: "status_danger",
					1: "status_success",
					2: "status_danger",
					3: "status_danger"
				},
				textMessage: "",
				showModalMessage: false
			}
		},
		async asyncData({
			                store,
			                params,
			                error
		                }) {
			let data = await store.dispatch('apiCalls/getUserProfile', params.id).catch(e => {
				return error({
					statusCode: 404,
					message: e.response.data.result.message
				});
			});
			return {
				userProfile: data.user,
				userListings: data.userListings
			}
		},
		methods: {
			currentTime: function () {
				var now = new Date();
				var TwentyFourHour = now.getHours();
				var hour = now.getHours();
				var min = now.getMinutes();
				var mid = 'pm';

				if (min < 10) {
					min = "0" + min;
				}
				if (hour > 12) {
					hour = hour - 12;
				}
				if (hour == 0) {
					hour = 12;
				}
				if (TwentyFourHour < 12) {
					mid = 'am';
				}

				document.getElementById('currentTime').innerHTML = hour + ':' + min + mid;

				setTimeout(this.currentTime, 1000);
			},
			formatDate: function (date, type) {
				if (type === "listing") {

					return moment(date).format("MM/DD/YYYY");
				} else {
					return moment(date).format("MMMM'YY")
				}
			},
			listingAvailable(date) {
				let currentDate = new Date();
				let newdate = new Date(date.date);
				return newdate > currentDate;
			},
			goToListing(id) {
				this.$router.push({
					name: "listing-details-id",
					params: {
						id: id
					}
				})
			},
			saveListing(listing, status) {
				this.$store.dispatch('watchList/setSaved', {
					listing: listing,
					status: status
				});
			},
			openModalMessage() {
				this.showModalMessage = true;
			},
			closeModalMessage() {
				this.showModalMessage = false;
			},
			sendMessage() {
				let self = this;
				try {
					this.$axios.post("conversation", {
						receiver_id: this.userProfile.id,
						message: this.textMessage,
					}).then((response) => {
						self.textMessage = "";
						self.closeModalMessage();
					});
				} catch (e) {
					alert('something went wrong')
				}
			},
			listingSaved(listing) {
				return this.$store.getters['watchList/getListing'](listing.id) !== undefined;
			}
		},
		mounted() {
			// this.currentTime();
		}
	}
</script>

<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";

	.sample_tag {
		position: absolute;
		left: 14px;
		top: 5px;
		border-radius: 3px;
		border: 1px solid $gray_tuna;
		background: rgba($white, .6);
		text-align: center;
		padding: 4px 7px;
		font-size: 11px;
		line-height: 16px;
		color: $gray_tuna;
		text-transform: uppercase;
		z-index: 20;
	}

	.dashboard_aside {
		max-height: 150px;
	}

	.fieldset__container {
		justify-content: space-between;
		padding-top: 0;
	}

	.trade_listings_profile {
		padding: 24px 34px;

		.status_success {
			color: $shamrock_darker;
		}

		.status_danger {
			color: $valencia;
		}

		li {
			display: flex;
			align-items: center;
			background: $gray_albaster;
			border-radius: 3px;
			border: 1px solid rgba($gray_aluminium, .7);
			position: relative;
			padding-right: 310px;
			margin-bottom: 9px;

			.details {
				max-width: 390px;
			}

			.product_image {
				width: 134px;
				height: 134px;
				margin-right: 20px;
				background-size: cover;
				background-repeat: no-repeat;
				background-position: center;
			}
		}

		.price {
			display: block;
			width: 100%;
			font-weight: 700;
			margin-bottom: 7px;
		}

		.title {
			font-size: 14px;
			margin-bottom: 0;
			color: $black;
			letter-spacing: 0;
		}

		p {
			font-size: 12px;
			line-height: normal;
			margin: 0 0 2px;
			color: $gray_aluminium;
			letter-spacing: 0.9px;
		}

		.listing_actions {
			position: absolute;
			top: 17px;
			right: 18px;
			display: flex;
			align-items: center;

			.btn + .btn {
				margin-left: 25px;
			}
		}
	}

	.fieldset__box {
		position: relative;

		.btn_action {
			position: absolute;
			top: 17px;
			right: 18px;
			padding: 12px 25px 10px;
		}
	}

	.box__inner {
		@include clearfix;
		padding: 30px 35px;

		.service {
			padding-right: 20px;
		}

		.products,
		.service {
			@include clearfix;
			float: left;
			width: 50%;
			padding-top: 10px;

			img {
				float: left;
				margin-right: 10px;
			}

			p,
			ul {
				float: left;
				width: calc(100% - 55px);
			}

			ul {
				list-style: disc;
				padding-left: 25px;

				li {
					font-size: 12px;
					line-height: normal;
					color: $gray_aluminium;
					margin-bottom: 5px;
					padding-left: 10px;
				}
			}
		}

		h4 {
			margin-bottom: 18px;
			clear: both;
		}

		p {
			font-size: 12px;
			color: $gray_aluminium;
			line-height: normal;
		}
	}

	.fieldset__col {
		width: 80%;
		width: 100%;
		max-width: 943px;
		margin: 0 auto;
	}

	.fieldset__box {
		width: 100%;
		margin-bottom: 18px;

		header {
			font-size: 18px;
			line-height: 24px;
			color: $gray_aluminium;
			padding: 38px 30px 31px;
			border-bottom: 1px solid rgba($gray_aluminium, 0.25);
		}

		h4 {
			font-size: 18px;
			line-height: 24px;
			color: $gray_aluminium;
		}
	}

	.fieldset__box_user {
		background: $white;
		margin-bottom: 14px;
		margin-top: 25px;

		p {
			small {
				display: block;
			}
		}

		.user {
			display: flex;
			align-items: center;
			padding: 24px 160px 30px 25px;
			border-bottom: 1px solid rgba($gray_aluminium, 0.25);
			position: relative;

			p {
				font-size: 18px;
				line-height: 20px;
				color: $gray_tuna;

				small {
					font-size: 12px;
				}

				.location {
					display: block;
					font-size: 16px;
					color: $gray_aluminium;
					margin-top: 7px;

					i {
						font-size: 16px;
						color: $gray_tuna;
					}

					small {
						opacity: 0.48;
						display: inline-block;
					}
				}
			}

			.btn__primary {
				position: absolute;
				top: 13px;
				right: 14px;
				min-width: 156px;
			}
		}
	}

	.extra {
		display: flex;
		padding: 30px 55px 25px;
		background: $gray_albaster;

		li {
			display: flex;
			align-items: center;
			margin-right: 20px;
		}

		i {
			font-size: 25px;
			margin-right: 15px;
		}

		.icon-location {
			color: $blue_scooter;
			font-size: 44px;
		}

		.icon-eye {
			font-size: 40px;
			color: $shamrock_darker;
		}

		.icon-save {
			color: $saffron;
			font-size: 42px;
		}

		.icon-calendar {
			font-size: 35px;
			color: $valencia;
		}

		p {
			font-size: 26px;
			line-height: normal;
			color: $gray_tuna;
			min-width: 100px;

			small {
				font-size: 10px;
				display: block;
				color: $gray_aluminium;
				letter-spacing: 2px;
				text-transform: uppercase;
			}
		}
	}

	.user_image {
		width: 102px;
		height: 102px;
		border-radius: 50%;
		background-size: cover;
		background-repeat: no-repeat;
		background-position: center;
		margin-right: 24px;
	}

	.trade_items_list {
		padding: 32px 35px;
	}
</style>
