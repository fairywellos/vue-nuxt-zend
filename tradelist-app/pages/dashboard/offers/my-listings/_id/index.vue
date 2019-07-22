<template>
	<div class="my_listings__page dashboard">
		<Header v-if="$device.isDesktop" />
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<div class="container">
			<div class="dashboard_content">
				<DashboardAside v-if="$device.isDesktop" />
				<div class="fieldset_wrapper">
					<fieldset>
						<div class="breadcrumb">
							<ul>
								<li>
									<nuxt-link to="/dashboard/offers/my-listings">
										My listings
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
								<th>user</th>
								<th>note</th>
								<th>date</th>
								<th>value</th>
								<th>&nbsp;</th>
							</tr>
							</thead>
							<tbody>
							<tr v-for="(data, index) in currentListingTrades" :key="index">
								<td>
									<div class="product_image"
									     v-if="tradePhoto(data)"
										 :style="{ backgroundImage: 'url('+ tradePhoto(data)  + ')' }">
									</div>
									<div class="product_image placeholder_image" v-else>
									</div>
								</td>
								<td>
									<nuxt-link  :to="{name: 'dashboard-offers-my-listings-id-offer-offerId', params: {id: currnetListing.id, offerId:data.id}}" class="desc desc__md">
										<p>
											{{ tradeItemTitle(data,"title") }}
											<small>{{ tradeItemTitle(data,"description") }}</small>
										</p>
									</nuxt-link>
								</td>
								<td>
									<div class="user">
										<div class="user_image" v-if="tradeUser(data).photoUrl"
											 :style="{ backgroundImage: 'url('+ tradeUser(data).photoUrl + ')' }"></div>
										<div class="user_image" v-else></div>
										{{ tradeUser(data).publicName }}
									</div>
								</td>
								<td>
									<div class="note" v-if="data.notes">
										<i class="icon-notes"></i>
									</div>
									<div v-else>&nbsp;</div>
								</td>
								<td>
									<div class="date">
										{{ formatDate(data.dateAdded.date) }}
									</div>
								</td>
								<td>
									<div class="value value__lg">
										${{ tradeValue(data) }}
										<div class="trade_value">
											{{tradeValueProcent(tradeValue(data))}}% Trade Value
										</div>
									</div>
								</td>
								<td>
									<div class="actions">
										<button class="btn btn__border btn__border--green"
												type="button"
												@click="updateTradeOfferStatus('accept',data.id)"
												role="button">Accept
										</button>
										<button class="btn btn__border btn__border--red"
												@click="updateTradeOfferStatus('reject',data.id)"
												type="button" role="button">Reject
										</button>
									</div>
								</td>
							</tr>
							</tbody>
						</table>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import Header from "~/components/Header"
	import HeaderMobile from "~/components/HeaderMobile";
	import DashboardAside from "~/components/DashboardAside"
	import multiTradeImg from "~/assets/img/dashboard/multi_item.jpg"
	import cashTradeImg from "~/assets/img/dashboard/cash_trade.jpg"
	import placeholderImg from "~/assets/img/dashboard/sport.jpg"
	import { mapGetters } from "vuex";
	import moment from 'moment'
	import Vue from 'vue'

	export default {
		middleware: 'auth',
		components: {
			Header,
			HeaderMobile,
			DashboardAside
		},
		head: {
			title: "My listings offers"
		},
		data() {
			return {
				offerDetails: false,
				multiTradeImg,
				cashTradeImg,
				placeholderImg
			}
		},
		async asyncData({store, params}) {
			let currentListingTrades = await store.dispatch('auth/getUserCurrentListingTradesAsync',params.id);
			return {currentListingTrades: currentListingTrades}
		},
		computed: {
			...mapGetters("auth", ["currnetListing"]),
		},
		methods: {
			tradeValue: function (trade) {
				let sum = 0;

				for(let tradeOffer in trade.tradeOffers){
					if(!trade.tradeOffers.hasOwnProperty(tradeOffer)) continue;

					sum += parseInt(trade.tradeOffers[tradeOffer].cashValue);
					if(trade.tradeOffers[tradeOffer].listing){
						sum += parseInt(trade.tradeOffers[tradeOffer].listing.price)
					}
				}

				return sum;
			},
			tradePhoto: function (trade) {
				if(Object.keys(trade.tradeOffers).length > 1){
					return multiTradeImg;
				}else {
					if(trade.tradeType == 2){
						return cashTradeImg;
					}else {
						let listing = trade.tradeOffers[Object.keys(trade.tradeOffers)[0]].listing;
						if(listing){
							if(Object.keys(listing.photos).length > 0){
								let mainPhoroUrl  = "";
								for (let photo in listing.photos){
									if(!listing.photos.hasOwnProperty(photo)) continue;

									mainPhoroUrl = listing.photos[photo].url;
									if(listing.photos[photo].main == 1){
										break;
									}
								}
								return mainPhoroUrl;
							}else {
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
			tradeUser: function (trade) {
				return trade.tradeOffers[Object.keys(trade.tradeOffers)[0]].user;
			},
			formatDate: function (date) {
				return moment(date).format("MMM DD, YYYY");
			},
			tradeValueProcent: function (tradeValue) {
				var percentage = (tradeValue / this.currnetListing.price) * 100;
				var round = percentage.toFixed(2);

				return round;
			},
			updateTradeOfferStatus(status,tradeId) {
				try {
					 this.$axios.post("listing/trade-offer/change-status", {
						status: status,
						tradeId: tradeId,

					});

					Vue.delete(this.currentListingTrades, tradeId);
				} catch (e) {
					this.error = e.response.data.result.message;

				}
			},

		}
	}
</script>
