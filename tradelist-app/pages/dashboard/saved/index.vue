<template>
	<div class="watchlist dashboard">
		<Header v-if="$device.isDesktop"/>
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<div class="container">
			<div class="dashboard_content">
				<DashboardAside/>
				<fieldset>
					<div class="breadcrumb">
						<ul>
							<li>Watchlist</li>
						</ul>
					</div>
					<div class="cards_grid">
						<CardItem
							v-for="(savedListing, i) in savedListings"
							:key="`${i}-${savedListing.id}`"
							:id="savedListing.id"
							:listing="savedListing"
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
	import CardItem from "~/components/CardItem";

	export default {
		middleware: "auth",
		components: {
			Header,
			HeaderMobile,
			DashboardAside,
			CardItem
		},
		head: {
			title: "Watchlist"
		},
		data() {
			return {
				savedListings: this.$store.getters["watchList/getListings"]
			};
		}
	};
</script>

<style lang="scss" scoped>
	.cards_grid {
		padding: 31px;

		.card_item {
			&:nth-child(4n) {
				margin-right: 19px;
			}
		}
	}
</style>

