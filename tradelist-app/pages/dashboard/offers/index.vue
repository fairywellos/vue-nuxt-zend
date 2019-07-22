<template>
<div class="offers__page dashboard">
					<Header v-if="$device.isDesktop" />
					<HeaderMobile v-if="$device.isMobileOrTablet" />
					<div class="container">
						<div class="dashboard_content">
							<DashboardAside v-if="$device.isDesktop" />
							<fieldset>
								<div class="breadcrumb">
									<ul>
										<li>Offers I've Made</li>
									</ul>
								</div>

					<table class="table table_offers_made" v-if="tradeOffers.length !== 0">
						<thead>
							<tr>
								<th>Item</th>
								<th style="max-width: 540px">&nbsp;</th>
								<th>Category</th>
								<th>Value</th>
								<th>For</th>
								<th>Status</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(data, index) in tradeOffers" :key="index">
								<td>
									<div
										v-if="data.itemTitle && data.itemPhotoUrl"
										class="product_image"
										:style="{ backgroundImage: 'url('+ data.itemPhotoUrl + ')' }"
									></div>
									<div v-else-if="data.itemTitle" class="product_image placeholder_image"></div>
									<div v-else class="product_image" :style="{ backgroundImage: 'url('+ cashTradeImg + ')' }"></div>
								</td>
								<td>
									<div class="desc" style="max-width: 540px">
										<nuxt-link :to="{name: 'dashboard-offers-id', params:{id:data.id} }"
												   class="desc desc__lg">
										<p v-if="data.itemTitle">{{ data.itemTitle }}</p>
										<p v-else>Cash trade offer</p>
										</nuxt-link>
									</div>
								</td>
								<td v-if="data.categoryName">
									<div class="category" :style="{color:data.categoryColor}">{{ data.categoryName }}</div>
								</td>
								<td v-else>
									<div class="category">N.A</div>
								</td>
								<td>
									<div class="value">${{ data.tradeOfferValue }}</div>
								</td>
								<td style="max-width: 80px;">
									<nuxt-link
										v-if="data.listingPhotoUrl"
										class="product_image"
										:to="{path: '/listing-details/' + data.listingID}"
										:style="{ backgroundImage: 'url('+ data.listingPhotoUrl + ')' } "
									></nuxt-link>
									<nuxt-link
										v-else
										class="product_image placeholder_image"
										:to="{path: '/listing-details/' + data.listingID}"
									></nuxt-link>
								</td>
								<td>
									<div class="status" :class="tradeStatusColor[data.status]">{{ tradeStatus[data.status]}}</div>
								</td>
								<td>
									<div class="actions">
										<button class="btn btn__icon btn__close" type="button" role="button">
											<i class="icon-close"></i>
										</button>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="table table_no-rows" v-else>
						<thead>
							<tr>
								<th>Item</th>
								<th style="max-width: 540px">&nbsp;</th>
								<th>Category</th>
								<th>Value</th>
								<th>For</th>
								<th>Status</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="7">
									<p>No Offers to display. Find a listing now.</p>
									<nuxt-link to="/browse-local" class="btn btn__primary">Browse Local</nuxt-link>
								</td>
							</tr>
						</tbody>
					</table>
				</fieldset>
			</div>
		</div>
	</div>
</template>

<script>
import Header from "~/components/Header";
				import HeaderMobile from "~/components/HeaderMobile";
				import DashboardAside from "~/components/DashboardAside";
				import cashTradeImg from "~/assets/img/dashboard/cash_trade.jpg";

				export default {
					middleware: "auth",
					components: {
						Header,
						HeaderMobile,
						DashboardAside
					},
					head: {
						title: "Offers i've made"
					},
					data() {
						return {
							cashTradeImg,
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
						let tradeOffers = await store.dispatch("apiCalls/getUserTradeOffers");
						return {
							tradeOffers: tradeOffers
						};
					},
					mounted() {}
				};
</script>
