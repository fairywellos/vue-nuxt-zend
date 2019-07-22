<template>
	<div class="my_listings__page dashboard">
		<Header v-if="$device.isDesktop" />
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<div class="container">
			<div class="dashboard_content">
				<DashboardAside v-if="$device.isDesktop" />
				<div class="fieldset_wrapper fieldset_offer">
					<fieldset>
						<div class="breadcrumb">
							<ul>
								<li>
									<nuxt-link :to="{path: '/dashboard/offers/my-listings/' + currnetListing.id}">My
										listing offers
									</nuxt-link>
								</li>
								<li>
									<div class="product_image"
									     v-if="currnetListing.mainPhotoUrl"
										 :style="{ backgroundImage: 'url('+ currnetListing.mainPhotoUrl + ')' }"></div>
									<div class="product_image placeholder_image" v-else></div>
									{{currnetListing.title}}
								</li>
							</ul>
						</div>
						<table class="table table_offers">
							<thead>
							<tr>
								<th>image</th>
								<th>description</th>
								<th>&nbsp;</th>
							</tr>
							</thead>
							<tbody>
							<tr v-for="(data, index) in currentListingTrades" :key="index">
								<td>
									<div class="product_image"
									     v-if="tradePhoto(data)"
										 :style="{ backgroundImage: 'url('+ tradePhoto(data)  + ')' }"></div>
									<div class="product_image placeholder_image" v-else></div>
								</td>
								<td v-if="data.id === parseInt(selectedTradeId)">
									<div class="desc desc_sm">
										<p>
											{{ tradeItemTitle(data,"title") }}
											<small>{{ tradeItemTitle(data,"description") }}</small>
										</p>
									</div>
								</td>
								<td v-if="data.id !== parseInt(selectedTradeId)">
									<nuxt-link
										:to="{path: '/dashboard/offers/my-listings/' + currnetListing.id + '/offer/' + index}">
										<div class="desc desc_sm">
											<p>
												{{ tradeItemTitle(data,"title") }}
												<small>{{ tradeItemTitle(data,"description") }}</small>
											</p>

										</div>
									</nuxt-link>
								</td>
								<td>
									<div class="value value__lg">
										${{ tradeValue(data) }}
										<div class="trade_value">
											<div class="price_val price_val--small price_val--left">
												<p>
													{{tradeValueProcent(tradeValue(data))}}% Trade Value
													<span class="arrow_down"></span>
												</p>
											</div>
										</div>
									</div>
								</td>
							</tr>
							</tbody>
						</table>
					</fieldset>
					<fieldset>
						<div class="breadcrumb">
							<ul>
								<li>Offer details</li>
							</ul>
							<div class="action_buttons">
								<button class="btn btn__primary" type="button" role="button" @click="updateTradeOfferStatus('accept',selectedTradeId)">Accept</button>
								<button class="btn btn__secondary" type="button" role="button"
										@click="updateTradeOfferStatus('reject',selectedTradeId)">
									Reject
								</button>
							</div>
						</div>
						<div class="fieldset_hero">
							<div class="trade_value">
								<h4>Your trade value</h4>
								<div class="chart">
									<img src="~/assets/img/dashboard/chart.png" alt="chart">
									<p>{{tradeValueProcent(tradeValue(currentListingTrades[this.selectedTradeId]))}}%</p>
								</div>
								<div class="price_val">
									<p>
										$ {{tradeValueProfit(tradeValue(currentListingTrades[this.selectedTradeId]))}}
										<span class="arrow_up"></span>
									</p>
								</div>
							</div>
							<!--<div class="attachments">-->
								<!--<h4>Attachments</h4>-->
								<!--<dropzone-->
									<!--id="foo"-->
									<!--ref="el"-->
									<!--:options="dropzoneOptions"-->
									<!--:destroyDropzone="true"-->
									<!--:useCustomSlot="true"-->
								<!--&gt;</dropzone>-->
							<!--</div>-->
						</div>
						<div class="card_wrapper">
							<div class="card">
								<header>You give</header>
								<ul>
									<li>
										<nuxt-link to="/">
											<div class="product_image"
											     v-if="currnetListing.mainPhotoUrl"
												 :style="{ backgroundImage: 'url('+ currnetListing.mainPhotoUrl + ')' }"></div>
											<div class="product_image placeholder_image"
											     v-else></div>
											<p>
												{{currnetListing.title}}
												<strong>${{currnetListing.price}}</strong>
											</p>
										</nuxt-link>
									</li>

								</ul>
								<footer>
									<p>total</p>
									<div class="price">${{currnetListing.price}}</div>
								</footer>
							</div>
							<div class="card">
								<header>YOU RECIEVE</header>
								<ul>
									<li v-for="data in currentListingTrades[this.selectedTradeId].tradeOffers">
										<nuxt-link :to="{path: '/listing-details/' + data.listing.id}" v-if="data.listing"  target="_blank">
											<div class="product_image"
											     v-if="listingMainPhoto(data.listing)"
												 :style="{ backgroundImage: 'url('+ listingMainPhoto(data.listing) + ')' }"></div>
											<div class="product_image placeholder_image" v-else></div>
											<p>
												{{data.listing.title}}
												<strong>${{data.listing.price}}</strong>
											</p>
										</nuxt-link>
										<a href="javaScript:"  v-if="parseInt(data.cashValue) !== 0"  >
											<div class="product_image"
												 :style="{ backgroundImage: 'url('+ cashTradeImg + ')' }"></div>
											<p>
												Cash offer
												<strong>${{data.cashValue}}</strong>
											</p>
										</a>
									</li>
								</ul>
								<footer>
									<p>total</p>
									<div class="price">${{tradeValue(currentListingTrades[this.selectedTradeId])}}</div>
								</footer>
							</div>
						</div>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import Header from "~/components/Header";
	import HeaderMobile from "~/components/HeaderMobile";
	import Dropzone from "nuxt-dropzone";
	import DashboardAside from "~/components/DashboardAside";
	import multiTradeImg from "~/assets/img/dashboard/multi_item.jpg"
	import cashTradeImg from "~/assets/img/dashboard/cash_trade.jpg"
	import placeholderImg from "~/assets/img/dashboard/sport.jpg"
	import {mapGetters} from "vuex";

	export default {
		middleware: "auth",
		components: {
			Header,
			HeaderMobile,
			Dropzone,
			DashboardAside
		},
		head: {
			title: "My listings offers"
		},
		data() {
			return {
				dropzoneOptions: {
					url: "https://httpbin.org/post",
					addRemoveLinks: true,
					dictDefaultMessage: "<i class='icon-add'></i>",
					acceptedFiles: ".jpeg,.jpg,.png,.gif"
				},
				multiTradeImg,
				cashTradeImg,
				placeholderImg,
				selectedTradeId: this.$route.params.offerId
			};
		},
		async asyncData({store, params,redirect}) {
			let currentListingTrades = await store.dispatch('auth/getUserCurrentListingTradesAsync',params.id);
			if(currentListingTrades.length === 0){
				redirect('301', '/dashboard/trade-history');
			}
			return {currentListingTrades: currentListingTrades}
		},
		computed: {
			...mapGetters("auth", ["currnetListing"])
		},
		methods: {
			tradeValue: function (trade) {
				let sum = 0;

				for (let tradeOffer in trade.tradeOffers) {
					if (!trade.tradeOffers.hasOwnProperty(tradeOffer)) continue;

					sum += parseInt(trade.tradeOffers[tradeOffer].cashValue);
					if (trade.tradeOffers[tradeOffer].listing) {
						sum += parseInt(trade.tradeOffers[tradeOffer].listing.price)
					}
				}

				return sum;
			},
			tradePhoto: function (trade) {
				if (Object.keys(trade.tradeOffers).length > 1) {
					return multiTradeImg;
				} else {
					if (trade.tradeType == 2) {
						return cashTradeImg;
					} else {
						let listing = trade.tradeOffers[Object.keys(trade.tradeOffers)[0]].listing;
						if (listing) {
							if (Object.keys(listing.photos).length > 0) {
								let mainPhoroUrl = "";
								for (let photo in listing.photos) {
									if (!listing.photos.hasOwnProperty(photo)) continue;

									mainPhoroUrl = listing.photos[photo].url;
									if (listing.photos[photo].main == 1) {
										break;
									}
								}
								return mainPhoroUrl;
							} else {
								return placeholderImg;
							}
						}
					}
				}
			},
			tradeItemTitle: function (trade,infoReq) {
				if(trade.tradeType == 2){
					if(infoReq === "title"){

						return "Cash trade offer";
					}else {
						return "";
					}
				}else {
					if(Object.keys(trade.tradeOffers).length > 1){
						let resultListing = trade.tradeOffers[Object.keys(trade.tradeOffers)[0]].listing;
						let maxPrice = resultListing.price;
						for(let tradeOffer in trade.tradeOffers){
							if(!trade.tradeOffers.hasOwnProperty(tradeOffer)) continue;
							let listing  = trade.tradeOffers[tradeOffer].listing;
							if(listing && listing.price > maxPrice){
								resultListing = listing;
								maxPrice = listing.price;
							}
						}
						if(infoReq === "title"){

							return resultListing.title;
						}else {
							return resultListing.description
						}

					}else {
						let listing = trade.tradeOffers[Object.keys(trade.tradeOffers)[0]].listing;
						if(listing){
							if(infoReq === "title"){

								return listing.title;
							}else {
								return listing.description
							}
						}
						return "";
					}
				}
			},
			tradeValueProcent: function (tradeValue) {
				var percentage = (tradeValue / this.currnetListing.price) * 100;
				var round = percentage.toFixed(2);

				return round;
			},
			tradeValueProfit: function (tradeValue) {
				return tradeValue-this.currnetListing.price;
			},
			listingMainPhoto: function (listing) {
				if (Object.keys(listing.photos).length > 0) {
					let mainPhoroUrl = "";
					for (let photo in listing.photos) {
						if (!listing.photos.hasOwnProperty(photo)) continue;

						mainPhoroUrl = listing.photos[photo].url;
						if (listing.photos[photo].main == 1) {
							break;
						}
					}
					return mainPhoroUrl;
				} else {
					return placeholderImg;
				}
			},
			updateTradeOfferStatus(status,tradeId) {
				try {
					this.$axios.post("listing/trade-offer/change-status", {
						status: status,
						tradeId: tradeId,
						XDEBUG_SESSION_START: 1,

					}).then((response) => {
						this.$router.push({ name: "dashboard-offers-my-listings-id",params: {id: this.$route.params.id} });
					});
				} catch (e) {
					this.error = e.response.data.result.message;

				}

			},

		}
	};
</script>

<style src="vue2-dropzone/dist/vue2Dropzone.min.css"></style>

