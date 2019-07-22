<template>
	<div class="settings__page dashboard">
		<Header v-if="$device.isDesktop" />
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<div class="container">
			<div class="dashboard_content">
				<DashboardAside/>
				<fieldset>
					<div class="breadcrumb">
						<ul>
							<li>Membership &amp; Credits</li>
						</ul>
					</div>
					<div class="fieldset__container">
						<div class="fieldset__col">
							<div class="fieldset__box">
								<h3>Membership type</h3>
								<p>
									<span v-if="loggedInUser.userType == 2">Basic</span>
								</p>
								<div class="button_group">
									<button type="button" role="button" class="btn btn__primary">Upgrade</button>
									<button type="button" role="button" class="btn btn__secondary">Manage
										Membership
									</button>
								</div>
							</div>
						</div>
						<div class="fieldset__col">
							<div class="fieldset__box">
								<h3>Avaliable credits</h3>
								<div class="rounded_progress">
									<div class="progress">
										<svg viewBox="0 0 36 36" class="circular-chart">
											<path class="circle"
											      :stroke-dasharray="(loggedInUser.credits / totalCredits * 100) + ', 100'"
											      d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831  a 15.9155 15.9155 0 0 1 0 -31.831"/>
										</svg>
										<span> {{ loggedInUser.credits }}</span>
									</div>
									<span>of 3</span>
								</div>
								<div class="button_group">
									<button type="button" role="button" class="btn btn__primary">View History</button>
								</div>
							</div>
						</div>
						<div class="fieldset__col">
							<div class="fieldset__box">
								<h3>Membership credits</h3>
								<p v-if="loggedInUser.userType == 2">
									3 per month
								</p>
							</div>
							<div class="fieldset__box">
								<h3>Membership fee</h3>
								<p v-if="loggedInUser.userType == 2">
									Free
								</p>
							</div>
							<div class="fieldset__box">
								<h3>Current billing cycle</h3>
								<p>
									{{ dateFrom }}
									to {{dateTo }}
								</p>
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
	import HeaderMobile from "~/components/HeaderMobile"
	import DashboardAside from "~/components/DashboardAside"
	import {mapGetters} from 'vuex'
	import moment from 'moment'

	export default {
		computed: {
			...mapGetters('auth', ['isAuthenticated', 'loggedInUser'])
		},
		middleware: 'auth',
		components: {
			Header,
			HeaderMobile,
			DashboardAside
		},
		head: {
			title: "Membership & Credits"
		},
		data() {
			return {
				dateFrom: moment(this.$auth.user.membershipExpires.date).subtract(1, 'months').format("MMM DD, YYYY"),
				dateTo: moment(this.$auth.user.membershipExpires.date).format("MMM DD, YYYY"),
				totalCredits: 3
			};
		},
		methods: {

		}
	};
</script>

<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";

	@keyframes progress {
		0% {
			stroke-dasharray: 0 100;
		}
	}

	.fieldset__container {
		.fieldset__col {
			width: 100%;
			max-width: 362px;
			margin-right: 22px;

			@media only screen and (max-width: 1659px) {
				max-width: 342px;
			}
		}
	}

	.button_group {
		display: flex;
		justify-content: space-between;
	}

	.rounded_progress {
		@include open_sans(300);
		text-align: center;
		margin-bottom: 35px;
		padding-top: 10px;

		.progress {
			position: relative;

			span {
				position: absolute;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
				font-size: 26px;
				line-height: 36px;
				color: $gray_tuna;
			}
		}

		span {
			@include open_sans(300);
			font-size: 14px;
			line-height: 19px;
			color: $gray_aluminium;
		}
	}

	.fieldset__box {
		padding: 31px 30px 33px;
		margin: 0 0 20px;

		h3 {
			margin-bottom: 18px;
		}

		p {
			font-size: 34px;
			line-height: 44px;
			color: $gray_tuna;

			span {
				display: block;
				font-size: 44px;
				line-height: 58px;
				margin-bottom: 28px;
			}
		}

		svg {
			display: block;
			width: 140px;
			height: 140px;
			margin: 0 auto 10px;

			.circle {
				stroke: $bright_sun;
				fill: none;
				stroke-width: 1px;
				stroke-linecap: round;
				animation: progress 1s ease-out forwards;
			}
		}
	}
</style>
