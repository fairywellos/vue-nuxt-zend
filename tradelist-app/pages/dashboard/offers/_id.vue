<template>
<div class="my_listings__page dashboard">
					<Header v-if="$device.isDesktop" />
					<HeaderMobile v-if="$device.isMobileOrTablet" />
					<div class="container">
						<div class="dashboard_content">
							<DashboardAside />
							<div class="fieldset_wrapper fieldset_offer">
								<fieldset>
									<div class="breadcrumb">
										<ul>
											<li>
												<nuxt-link :to="{name: 'dashboard-offers'}">Offers I've Made</nuxt-link>
											</li>
											<li v-if="loggedInUser.id == currentTrade.listing.user.id">
												<div class="product_image" :style="{ backgroundImage: 'url('+ listingMainPhoto(currentTrade.listing) + ')' }"></div>
												{{currentTrade.listing.title}}
											</li>
											<li v-else-if="tradePhoto(currentTrade)">
												<div class="product_image" :style="{ backgroundImage: 'url('+ tradePhoto(currentTrade) + ')' }"></div>
												{{tradeItemTitle(currentTrade, "title")}}
											</li>
											<li v-else>
												<div class="product_image" :style="{ backgroundImage: 'url('+ cashTradeImg + ')' }"></div>
												{{tradeItemTitle(currentTrade, "title")}}
											</li>
										</ul>
									</div>
									<table class="table table_offers">
										<thead>
											<tr>
												<th>image</th>
												<th>description</th>
												<th>&nbsp;</th>
												<th>&nbsp;</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="(data, index) in trades" :key="index">
												<td>
													<div class="product_image" v-if="loggedInUser.id == data.listingUser" :style="{ backgroundImage: 'url('+ data.listingPhotoUrl  + ')' }"></div>
													<div class="product_image" v-else-if="data.itemPhoto" :style="{ backgroundImage: 'url('+ data.itemPhotoUrl  + ')' }"></div>
													<div class="product_image" v-else :style="{ backgroundImage: 'url('+ cashTradeImg  + ')' }"></div>
												</td>
												<td v-if="data.id !== parseInt(selectedTradeId)">
													<nuxt-link :to="{name: 'dashboard-offers-id', params:{id:data.id} }">
														<div class="desc desc_sm">
															<p v-if="loggedInUser.id == data.listingUser">{{ data.listingTitle }}</p>
															<p v-else-if="data.itemTitle">{{ data.itemTitle }}</p>
															<p v-else>Cash trade offer</p>
														</div>
													</nuxt-link>
												</td>
												<td>
													<div class="value value__lg">${{ data.tradeOfferValue }}</div>
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
											Status: <div class="status" :class="tradeStatusColor[currentTrade.status]">{{ tradeStatus[currentTrade.status]}}</div>
										</div>
									</div>
									<div class="fieldset_hero">
										<div class="trade_value">
											<h4>Your trade value</h4>
											<div class="chart">
												<img src="~/assets/img/dashboard/chart.png" alt="chart">
												<p>{{tradeValueProcent(tradeValue(currentTrade))}}%</p>
											</div>
											<div class="price_val">
												<p>
													$ {{tradeValueProfit(tradeValue(currentTrade))}}
													<span class="arrow_up"></span>
												</p>
											</div>
										</div>
										<div class="attachments">
											<h4>Attachments</h4>
											<dropzone id="foo" ref="el" :options="dropzoneOptions" :destroyDropzone="true" :useCustomSlot="true"></dropzone>
										</div>
									</div>
									<div class="card_wrapper">
										<div class="card">
											<header v-if="loggedInUser.id == currentTrade.listing.user.id">YOU GIVE</header>
											<header v-else>YOU RECIEVE</header>
											<ul>
												<li>
													<nuxt-link :to="{name: 'listing-details-id',params: {id:currentTrade.listing.id} }" target="_blank">
														<div class="product_image" :style="{ backgroundImage: 'url('+ listingMainPhoto(currentTrade.listing) + ')' }"></div>
														<p>
															{{currentTrade.listing.title}}
															<strong>${{currentTrade.listing.price}}</strong>
														</p>
													</nuxt-link>
												</li>
											</ul>
											<footer>
												<p>total</p>
												<div class="price">${{currentTrade.listing.price}}</div>
											</footer>
										</div>
										<div class="card">
											<header v-if="loggedInUser.id == currentTrade.listing.user.id">YOU RECIEVE</header>
											<header v-else>YOU GIVE</header>
											<ul>
												<li v-for="data in currentTrade.tradeOffers">
													<nuxt-link :to="{name: 'listing-details-id',params: {id:data.listing.id} }" v-if="data.listing" target="_blank">
														<div class="product_image" :style="{ backgroundImage: 'url('+ listingMainPhoto(data.listing) + ')' }"></div>
														<p>
															{{data.listing.title}}
															<strong>${{data.listing.price}}</strong>
														</p>
													</nuxt-link>
													<br>
													<a href="javaScript:" v-if="parseInt(data.cashValue) !== 0">
														<div class="product_image" :style="{ backgroundImage: 'url('+ cashTradeImg + ')' }"></div>
														<p>
															Cash offer
															<strong>${{data.cashValue}}</strong>
														</p>
													</a>
												</li>
											</ul>
											<footer>
												<p>total</p>
												<div class="price">${{tradeValue(currentTrade)}}</div>
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
				import multiTradeImg from "~/assets/img/dashboard/multi_item.jpg";
				import cashTradeImg from "~/assets/img/dashboard/cash_trade.jpg";
				import placeholderImg from "~/assets/img/dashboard/sport.jpg";
				import {
					mapGetters
				} from "vuex";

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
							selectedTradeId: this.$route.params.id,
							tradeStatus: {
								1: "Pending",
								2: "Accepted",
								3: "Rejected",
								4: "Unavailable"
							},
							tradeStatusColor: {
								1: "pending",
								2: "success",
								3: "danger",
								4: "danger"
							}
						};
					},
					async asyncData({
						store,
						params
					}) {
						let trades = await store.dispatch("apiCalls/getUserTradeOffers");
						let currentTrade = await store.dispatch("apiCalls/getTrade", params.id);
						return {
							trades: trades,
							currentTrade: currentTrade
						};
					},
					computed: {
						...mapGetters("auth", ["loggedInUser"])
					},
					methods: {
						tradeValue: function(trade) {
							let sum = 0;

							for (let tradeOffer in trade.tradeOffers) {
								if (!trade.tradeOffers.hasOwnProperty(tradeOffer)) continue;

								sum += parseInt(trade.tradeOffers[tradeOffer].cashValue);
								if (trade.tradeOffers[tradeOffer].listing) {
									sum += parseInt(
										trade.tradeOffers[tradeOffer].listing.price
									);
								}
							}

							return sum;
						},
						tradePhoto: function(trade) {
							if (Object.keys(trade.tradeOffers).length > 1) {
								return multiTradeImg;
							} else {
								if (trade.tradeType == 2) {
									return cashTradeImg;
								} else {
									let listing =
										trade.tradeOffers[Object.keys(trade.tradeOffers)[0]]
										.listing;
									if (listing) {
										if (Object.keys(listing.photos).length > 0) {
											let mainPhoroUrl = "";
											for (let photo in listing.photos) {
												if (!listing.photos.hasOwnProperty(photo))
													continue;

												mainPhoroUrl = listing.photos[photo].url;
												if (listing.photos[photo].main == 1) {
													break;
												}
											}
											return mainPhoroUrl;
										} else {
											return placeholderImg;
										}
									} else {
										return false;
									}
								}
							}
						},
						tradeItemTitle: function(trade, infoReq) {
							if (trade.tradeType == 2) {
								if (infoReq === "title") {
									return "Cash trade offer";
								} else {
									return "";
								}
							} else {
								if (Object.keys(trade.tradeOffers).length > 1) {
									let resultListing =
										trade.tradeOffers[Object.keys(trade.tradeOffers)[0]]
										.listing;
									let maxPrice = resultListing.price;
									for (let tradeOffer in trade.tradeOffers) {
										if (!trade.tradeOffers.hasOwnProperty(tradeOffer))
											continue;
										let listing = trade.tradeOffers[tradeOffer].listing;
										if (listing && listing.price > maxPrice) {
											resultListing = listing;
											maxPrice = listing.price;
										}
									}
									if (infoReq === "title") {
										return resultListing.title;
									} else {
										return resultListing.description;
									}
								} else {
									let listing =
										trade.tradeOffers[Object.keys(trade.tradeOffers)[0]]
										.listing;
									if (listing) {
										if (infoReq === "title") {
											return listing.title;
										} else {
											return listing.description;
										}
									}
									return "";
								}
							}
						},
						tradeValueProcent: function(tradeValue) {
							let percentage =
								(this.currentTrade.listing.price / tradeValue) * 100;
							let round = percentage.toFixed(2);

							if (this.$auth.user.id == this.currentTrade.listing.user.id) {
								percentage =
									(tradeValue / this.currentTrade.listing.price) * 100;
								round = percentage.toFixed(2);
							}

							return round;
						},
						tradeValueProfit: function(tradeValue) {
							let profit = this.currentTrade.listing.price - tradeValue;
							if (this.$auth.user.id == this.currentTrade.listing.user.id) {
								profit = tradeValue - this.currentTrade.listing.price;
							}

							return profit;
						},
						listingMainPhoto: function(listing) {
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
				};
</script>

<style src="vue2-dropzone/dist/vue2Dropzone.min.css">
</style>
<style lang="scss"scoped>
@import "~assets/scss/abstracts/_abstracts.scss";

.dashboard_content {
    .breadcrumb {
        padding-right: 25px;

        .action_buttons {
            font-size: 20px;
            line-height: normal;
            color: $gray_aluminium;
        }

        .status {
            margin-left: 5px;
        }
    }
}
</style>
