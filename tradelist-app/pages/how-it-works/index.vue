<template>
	<div class="how-it-works informational_page">
		<Header v-if="$device.isDesktop"/>
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<div class="hero_section"
		     :style="{ backgroundImage: 'url('+ bgGirl + ')' }">
			<div class="container">
				<div class="hero_inner">
					<h1>Turn what you have, into what you want.</h1>
					<button type="button" role="button" class="btn btn__border btn__border--blue"
					        @click="homeModal()">Get Started</button>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="section_full"
			     :style="{ backgroundImage: 'url('+ bgWatch + ')' }">
				<div class="text_left">
					<h2>
						Tradelist is a trade and networking platform.
					</h2>
					<p>
						Simply create a listing, browse for other user's listings, and make
						a trade or cash offer.
					</p>
				</div>
			</div>
			<div class="col_three">
				<div class="row">
					<div class="col-sm-6 col-md-4">
						<i class="icon-list-present"></i>
						<h2>
							List what you have
						</h2>
						<p>
							Create an account and list any services or goods you are willing to sell or trade.
						</p>
					</div>
					<div class="col-sm-6 col-md-4">
						<i class="icon-binoculars"></i>
						<h2>
							Discover trades
						</h2>
						<p>
							Select categories you’re interested in trading for, search for items you need or want and start negotiating with other sellers.
						</p>
					</div>
					<div class="col-sm-6 col-md-4">
						<i class="icon-trade"></i>
						<h2>
							Make the trade
						</h2>
						<p>
							Message sellers directly to negotiate a trade or to purchase outright. Once you’ve negotiated, complete the transaction when you make the offer.
						</p>
					</div>
				</div>
				<div class="icon-red-line"></div>
			</div>
		</div>
		
		<div class="trade_networks">
			<div class="container">
				<div class="trade_networks__content float_right">
					<h2>
						Build trade networks
					</h2>
					<p>
						The easiest way to build trade networks with multiple sellers is with our trade circles feature. When you select something you like, Tradelist will match you with potential trade partners and suggest a trade circle. You can choose to be included in a trade circle with up to 6 people who can trade within the group.
					</p>
				</div>
			</div>
		</div>
		
		<div class="container">
			<div class="section_full section_full__center"
			     :style="{ backgroundImage: 'url('+ bgThread + ')' }">
				<div class="text_center">
					<h2>
						With Tradelist, you can find people offering services you need and they can find you.
					</h2>
					<button role="button" type="button" aria-label="Sign Up" class="btn btn__primary"
					        @click="homeModal()">Get Started
					</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import Header from "~/components/Header.vue";
	import HeaderMobile from "~/components/HeaderMobile.vue";
	import bgGirl from "~/assets/img/informational-pages/bg-girl.jpg";
	import bgWatch from "~/assets/img/informational-pages/bg-watch.jpg";
	import bgThread from "~/assets/img/informational-pages/thread-ball.png";
	
	export default {
		components: {
			Header, HeaderMobile
		},
		head() {
			return {
				title: "How it works",
				meta: [
					{
						hid: "description",
						name: "description",
						content: "How it works description"
					}
				]
			};
		},
		data() {
			return {
				bgGirl, bgWatch, bgThread
			}
		},
		methods: {
			homeModal() {
				this.$nuxt.$emit('toSignUp', true);
			}
		},
	};
</script>

<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";
	@import "~assets/scss/components/_informational-pages.scss";
	
	.hero_inner {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		min-height: 746px;
		margin-bottom: 35px;
		padding: 20px 0;
		position: relative;
		z-index: 5;
		
		@media only screen and (max-width: 1260px) {
			font-size: 40px;
			line-height: 50px;
			min-height: auto;
			padding: 35px 30px;
		}
		
		h1 {
			text-align: center;
		}
	}
	
	.btn__border {
		font-size: 15px;
		letter-spacing: 1.5px;
		padding: 22px 15px;
		width: 178px;
		
		@media only screen and (max-width: 1260px) {
			font-size: 12px;
			letter-spacing: 1px;
			padding: 15px 10px;
		}
	}
	
	.section_full {
		background-size: cover;
		background-position: center;
		background-repeat: no-repeat;
		min-height: 434px;
		padding: 0 86px 0;
		margin-bottom: 66px;
		
		@media only screen and (max-width: 1260px) {
			padding: 0 30px;
			min-height: auto;
		}
		
		@media only screen and (max-width: 576px) {
			padding: 0 15px;
		}
		
		&__center {
			display: flex;
			flex-wrap: wrap;
			align-items: center;
			justify-content: center;
			text-align: center;
			
			.text_center {
				width: 100%;
				max-width: 642px;
				
				@media only screen and (max-width: 1260px) {
					padding: 30px;
				}
				
				@media only screen and (max-width: 576px) {
					padding: 30px 15px;
				}
			}
			
			h2 {
				margin-bottom: 20px;
			}
		}
	}
	
	.text_left {
		color: $gray_tuna;
		width: 100%;
		max-width: 605px;
		display: flex;
		padding: 76px 0 66px;
		flex-direction: column;
		justify-content: flex-start;
		
		@media only screen and (max-width: 1260px) {
			padding: 56px 0 46px;
		}
	}
	
	
	.trade_networks {
		@include clearfix;
		min-height: 800px;
		background: transparent;
		padding: 0 196px 0 86px;
		overflow: hidden;
		
		@media only screen and (max-width: 1260px) {
			padding: 0 30px;
			min-height: auto;
		}
		
		@media only screen and (max-width: 576px) {
			padding: 0 15px;
		}
		
		&__content {
			width: 100%;
			max-width: 569px;
			position: relative;
			padding-top: 176px;
			
			@media only screen and (max-width: 1260px) {
				max-width: 100%;
				padding: 50px 0;
			}
			
			&:before {
				content: '';
				position: absolute;
				right: calc(100% + 163px);
				top: 0;
				width: 829px;
				height: 831px;
				display: block;
				background: url("~assets/img/informational-pages/trade-networks1.png") no-repeat center left;
				background-size: contain;
				
				@media only screen and (max-width: 968px) {
					display: none;
				}
			}
		}
		
		h2 {
			font-size: 42px;
			line-height: 64px;
			font-weight: 700;
			margin-bottom: 35px;
			
			@media only screen and (max-width: 1260px) {
				font-size: 32px;
				line-height: 42px;
			}
		}
	}
</style>
