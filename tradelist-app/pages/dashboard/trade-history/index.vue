<template>
	<div class="offers__page dashboard">
		<Header v-if="$device.isDesktop"/>
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<div class="container">
			<div class="dashboard_content">
				<DashboardAside/>
				<fieldset>
					<div class="breadcrumb">
						<ul>
							<li>
								Trade history
							</li>
						</ul>
					</div>

					<table class="table table_history">
						<thead>
						<tr>
							<th>Item</th>
							<th style="max-width: 540px">&nbsp;</th>
							<th>Category</th>
							<th>Value</th>
							<th>For</th>
							<th>Rating</th>
						</tr>
						</thead>
						<tbody>
						<tr v-for="(data, index) in trades" :key="index">
							<td>
								<div class="product_image" v-if="loggedInUser.id == data.listingUser"
									 :style="{ backgroundImage: 'url('+ data.listingPhotoUrl + ')' }">
								</div>
								<div class="product_image" v-else-if="data.itemPhoto"
									 :style="{ backgroundImage: 'url('+ data.itemPhotoUrl + ')' }">
								</div>
								<div class="product_image" v-else
									 :style="{ backgroundImage: 'url('+ cashTradeImg + ')' }">
								</div>
							</td>
							<td>
								<div class="desc" style="max-width: 540px">
									<nuxt-link :to="{name: 'dashboard-trade-history-id', params:{id:data.id} }"
											   class="desc desc__lg">
										<p v-if="loggedInUser.id == data.listingUser">
											{{ data.listingTitle }}
										</p>

										<p v-else-if="data.itemTitle">
											{{ data.itemTitle }}
										</p>

										<p v-else>
											Cash trade offer
										</p>
									</nuxt-link>
								</div>
							</td>
							<td>
								<div v-if="loggedInUser.id == data.listingUser" class="category"
									 :style="{color:data.listingCategoryColor}">
									{{ data.listingCategoryName }}
								</div>
								<div v-else-if="data.itemCategorySlug" class="category"
									 :style="{color:data.itemCategoryColor} ">
									{{ data.itemCategoryName }}
								</div>
								<div v-else class="category">
									NA
								</div>
							</td>
							<td>
								<div class="value">
									${{ data.tradeOfferValue }}
								</div>
							</td>
							<td style="max-width: 80px;">
								<div class="product_image"
									 v-if="loggedInUser.id == data.listingUser && data.itemPhotoUrl"
									 :style="{ backgroundImage: 'url('+ data.itemPhotoUrl + ')' } ">
								</div>
								<div class="product_image"
									 v-else-if="loggedInUser.id == data.listingUser && !data.itemPhotoUrl"
									 :style="{ backgroundImage: 'url('+ cashTradeImg + ')' } ">
								</div>
								<div class="product_image" v-else
									 :style="{ backgroundImage: 'url('+ data.listingPhotoUrl + ')' } ">
								</div>
							</td>
							<td>
								<div class="rating">
									<div class="star_rating" :data-rating="parseInt(data.rating)">
										<i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i
										class="icon-star"></i><i class="icon-star"></i>
									</div>
									<button type="button" role="button" class="btn btn__secondary" v-if="data.rating"
											@click="openModalRating(index,true)">
										Edit Rating
									</button>
									<button v-else type="button" role="button" class="btn btn__primary"
											@click="openModalRating(index,false)">
										Submit Rating
									</button>
								</div>
							</td>
						</tr>
						</tbody>
					</table>
				</fieldset>
			</div>
		</div>
		<no-ssr>
			<modal v-if="showModalRating" class="rating_modal" @close="closeModalRating()">
				<div slot="body">
					<button
						type="button"
						@click="closeModalRating()"
						role="button"
						class="close_modal"
						aria-label="Close modal"
					>
						<i class="icon-close"></i>
					</button>
					<div v-if="tradeRatingUser.photo" class="user_image"
						 :style="{ backgroundImage: 'url(' + tradeRatingUser.photo + ')' }"></div>
					<div v-else class="user_image"></div>
					<p>How was yout trade with {{tradeRatingUser.name}}?</p>
					<form method="post" @submit.prevent="sendReview">
						<div class="rating">
							<label>
								<input required type="radio" name="stars" value="1" v-model="checkedStar">
								<span class="star"></span>
							</label>
							<label>
								<input required type="radio" name="stars" value="2" v-model="checkedStar">
								<span class="star"></span>
								<span class="star"></span>
							</label>
							<label>
								<input required type="radio" name="stars" value="3" v-model="checkedStar">
								<span class="star"></span>
								<span class="star"></span>
								<span class="star"></span>
							</label>
							<label>
								<input required type="radio" name="stars" value="4" v-model="checkedStar">
								<span class="star"></span>
								<span class="star"></span>
								<span class="star"></span>
								<span class="star"></span>
							</label>
							<label>
								<input required type="radio" name="stars" value="5" v-model="checkedStar">
								<span class="star"></span>
								<span class="star"></span>
								<span class="star"></span>
								<span class="star"></span>
								<span class="star"></span>
							</label>
						</div>
						<div class="form_group">
							<label for="addReview">Add a review</label>
							<textarea required name="add-review" id="addReview" v-model="review"
									  placeholder="Type your message here..."></textarea>
						</div>
						<input type="submit" class="btn btn__primary float_right" value="Submit">
					</form>
				</div>
			</modal>
		</no-ssr>
	</div>
</template>

<script>
	import Header from "~/components/Header"
	import HeaderMobile from "~/components/HeaderMobile"
	import DashboardAside from "~/components/DashboardAside"
	import cashTradeImg from "~/assets/img/dashboard/cash_trade.jpg"
	import tradeHistoryIndex from '~/assets/js/tradeHistoryIndex.js'
	import Modal from "~/components/ModalTemplate.vue";
	import {mapGetters} from "vuex";

	export default {
		middleware: 'auth',
		components: {
			Header,
			HeaderMobile,
			DashboardAside,
			Modal
		},
		head: {
			title: "Trade history"
		},
		extends: tradeHistoryIndex,
		async asyncData({store, params}) {
			let trades = await store.dispatch('apiCalls/getUserAcceptedTrades');
			return {trades: trades}
		},
		data() {
			return {
				cashTradeImg,
				showModalRating: false,
				checkedStar: 0,
				tradeRatingUser: "",
				review: "",
				tradeRatingId: 0,

			}
		},
		computed: {
			...mapGetters("auth", ["loggedInUser"])
		},
	}
</script>
