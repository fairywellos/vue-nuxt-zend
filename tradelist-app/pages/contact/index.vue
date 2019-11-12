<template>
	<div class="partner_relations informational_page">
		<Header v-if="$device.isDesktop"/>
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<div
			class="hero_section hero_section__text_center"
			:style="{ backgroundImage: 'url('+ bgSeattle + ')' }"
		>
			<div class="hero_inner">
				<h1>Contact us</h1>

				<p>Tradelist is here to answer questions regarding your personal <br> account, trades, partnerships, investor relations and more.</p>
			</div>
		</div>

		<div class="container">
			<div class="col_three col_three__centered">
				<i class="icon-red-line"></i>
				<div class="row">
					<div class="col-sm-6 col-md-4">
						<i class="icon-headset"></i>
						<h2>Customer Service</h2>
						<p>Reach out to a member of our team to get help with your account or for more information about our services.</p>
						<n-link class="btn btn__secondary" to="/help">Customer Service</n-link>
					</div>
					<div class="col-sm-6 col-md-4">
						<i class="icon-handshake"></i>
						<h2>Partner Relations</h2>
						<p>
							Interested in partnering with Tradelist? <br> Connect with us to learn more.
						</p>
						<n-link class="btn btn__secondary" to="/partner-relations">Partner Relations</n-link>
					</div>
					<div class="col-sm-6 col-md-4">
						<i class="icon-info-message"></i>
						<h2>Product Feedback</h2>
						<p>
							Here at Tradelist we are constantly looking for ways to improve our product and create value for our users. Click here to tell us what you think!
						</p>
						<n-link class="btn btn__secondary" to="/feedback">Provide Feedback</n-link>
					</div>
				</div>
			</div>
		</div>

		<div class="location_details">
			<div class="container">
				<div class="location_details__text">
					<div class="col">
						<p>
							<strong>Corporate Headquarters</strong>
							Tradelist
							<br>360 Ewing Street Seattle, WA
							<br>98119
						</p>
						<p>
							<strong>General Inquiries</strong>
							info@tradelist.net
						</p>
						<p>
							<strong>Support</strong>
							support@tradelist.net
						</p>
					</div>
					<div class="col">
						<p>
							<strong>Public Relations</strong>
							PR@tradelist.net
						</p>
						<p>
							<strong>Partnerships</strong>
							partners@tradelist.net
						</p>
						<p>
							<strong>Investor Relations</strong>
							investors@tradelist.net
						</p>
					</div>
				</div>
			</div>
			<div class="map">
				<img src="~assets/img/informational-pages/map.jpg" alt="map">
			</div>
		</div>
	</div>
</template>

<script>
	import Header from "~/components/Header.vue";
	import HeaderMobile from "~/components/HeaderMobile.vue";
	import bgSeattle from "~/assets/img/browse-local/browse-local.jpg";

	export default {
		components: {
			Header,
			HeaderMobile
		},
		head() {
			return {
				title: "Contact",
				meta: [
					{
						hid: "description",
						name: "description",
						content: "Contact description"
					}
				]
			};
		},
		data() {
			return {
				bgSeattle
			};
		}
	};
</script>

<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";
	@import "~assets/scss/components/_informational-pages.scss";

	.hero_section__text_center {
		min-height: 549px;
		display: flex;
		justify-content: center;
		align-items: center;
		text-align: center;
		margin-bottom: 97px;

		@media only screen and (max-width: 576px) {
			min-height: auto;
			padding: 35px 15px;
		}

		h1,
		p {
			width: 100%;
			max-width: 625px;
			margin: 0 auto;
			color: $white;
		}

		h1 {
			margin-bottom: 58px;
		}
	}

	.hero_inner {
		align-items: center;
		position: relative;
		z-index: 5;
	}
</style>
