<template>
	<div class="saved__page dashboard">
		<Header v-if="$device.isDesktop"/>
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<div class="container">
			<div class="dashboard_content">
				<DashboardAside/>
				<fieldset>
					<div class="breadcrumb">
						<ul>
							<li>Trade Partners</li>
						</ul>
					</div>
					<div class="trader_grid">
						<TraderCard
							v-for="traderData in tradersData"
							:key="traderData.id"
							:id="traderData.id"
							:followed="traderData.followed"
							:name="traderData.name"
							:privacy="traderData.privacy"
							:username="traderData.username"
							:location="traderData.location"
							:thumbnail="traderData.photo_url"
							:trades="traderData.trades"
							:followers="traderData.followers"
							:rating="traderData.rating"
							:badge="traderData.badge"
						/>
					</div>
				</fieldset>
			</div>
		</div>
	</div>
</template>

<script>
	import Header from "~/components/Header";
	import HeaderMobile from "~/components/HeaderMobile";
	import DashboardAside from "~/components/DashboardAside";
	import TraderCard from "~/components/TraderCard";
	import image1 from "~/assets/img/card-item/Image.png";
	import image2 from "~/assets/img/card-item/Image2.png";
	import image3 from "~/assets/img/card-item/Image3.png";
	import image4 from "~/assets/img/card-item/Image4.png";
	import image5 from "~/assets/img/card-item/Image.jpg";
	import ownerImage from "~/assets/img/card-item/user_image.png";

	export default {
		middleware: "auth",
		components: {
			Header,
			HeaderMobile,
			DashboardAside,
			TraderCard
		},
		head: {
			title: "Saved searches"
		},
		data() {
			return {
				image1,
				image2,
				image3,
				image4,
				image5,
				ownerImage,
			};
		},
		async asyncData({store}) {
			let tradersData = await store.dispatch('apiCalls/getUserTradePartners');
			return {tradersData: tradersData}
		},
	};
</script>

<style lang="scss" scoped>
	.create_search {
		margin: 26px 0 0 30px;
	}

	.trader_grid {
		padding: 30px;
	}
</style>
