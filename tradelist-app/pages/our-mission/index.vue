<template>
	<div class="our_mission informational_page">
		<Header v-if="$device.isDesktop"/>
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<div class="hero_section"
		     :style="{ backgroundImage: 'url('+ bgTomatoes + ')' }">
			<div class="hero_inner">
				<div class="container">
					<h1>Our Mission</h1>
					<p>
						Tradelist was created as a network that empowers people. A network that supports and improves human relations, without relying on money. It’s a trading and bartering platform that enables people to create value, network, solve problems and to ultimately be successful.
					</p>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="centered_preview">
				<div class="logo">
					<i class="icon-red-line"></i>
				</div>
				<h2>
					Money is just a means of exchange. <br> What if you could trade your services or <br> products without a dollar sign?
				</h2>
			</div>
			<div class="text_photo">
				<div class="text_photo_row">
					<div class="text">
						<h2>
							Well with Tradelist, you can.
						</h2>
						<p>
							Tradelist is where Etsy, Craigslist, Airbnb, VRBO, Poshmark, Facebook Marketplace and more become one. Anything you search for on those sites, you can find here. Except instead of purchasing with money, you have the ability to trade for it!
						</p>
					</div>
					<div class="photo">
						<img src="~assets/img/informational-pages/hand-worker.jpg" alt="image">
					</div>
				</div>
				<div class="text_photo_row text_photo_row__reverse">
					<div class="text">
						<h2>
							The world is your market place.
						</h2>
						<p>
							With Tradelist you can get a hold of the right circle of people that have what you need and want what you offer in exchange. Tradelist values your services and goods more than the dollar, so you can make trades and exchanges for free. Tradelist leverages technology to support and improve human relations through the process of trading and exchanging goods and services online.
						</p>
					</div>
					<div class="photo">
						<img src="~assets/img/informational-pages/market.jpg" alt="image">
					</div>
				</div>
			</div>
			<div class="categories_preview">
				<i class="icon-red-line"></i>
				<h3>
					Start today by making a cash offer or trading on thousands of goods & services including:
				</h3>
				
				<ul class="categories_list" role="list">
					<li>
						<a href="#" class="art_collectibles">
							<img src="~/assets/img/illustrations/art-collectibles.svg" alt="art & collectibles">
							<p>
								Art &amp; <br>
								Collectibles
							</p>
						</a>
					</li>
					<li>
						<a href="#" class="arts_crafts">
							<img src="~/assets/img/illustrations/arts-crafts.svg" alt="arts crafts">
							<p>
								Arts &amp; Crafts
							</p>
						</a>
					</li>
					<li>
						<a href="#" class="experiences">
							<img src="~/assets/img/illustrations/experiences.svg" alt="experiences">
							<p>
								Experiences
							</p>
						</a>
					</li>
					<li>
						<a href="#" class="motors">
							<img src="~/assets/img/illustrations/motors.svg" alt="motors">
							<p>
								Motors
							</p>
						</a>
					</li>
					<li>
						<a href="#" class="clothing_accessories">
							<img src="~/assets/img/illustrations/clothing-accessories.svg" alt="clothing accessories">
							<p>
								Clothing &amp; <br> Accessories
							</p>
						</a>
					</li>
					<li>
						<a href="#" class="homes_housing">
							<img src="~/assets/img/illustrations/homes-housing.svg" alt="homes housing">
							<p>
								Homes &amp; <br> Housing
							</p>
						</a>
					</li>
					<li>
						<a href="#" class="electronics">
							<img src="~/assets/img/illustrations/electronics.svg" alt="electronincs">
							<p>
								Electronics
							</p>
						</a>
					</li>
					<li>
						<a href="#" class="services">
							<img src="~/assets/img/illustrations/services.svg" alt="services">
							<p>
								Services
							</p>
						</a>
					</li>
					<li>
						<a href="#" class="sporting_goods">
							<img src="~/assets/img/illustrations/sporting-goods.svg" alt="sporting goods">
							<p>
								Sporting Goods
							</p>
						</a>
					</li>
					<li>
						<a href="#" class="business_industrial">
							<img src="~/assets/img/illustrations/business-industrial.svg" alt="business industrial">
							<p>
								Business &amp; <br> Industrial
							</p>
						</a>
					</li>
				</ul>
			</div>
			<div class="section_full section_full__center"
			     :style="{ backgroundImage: 'url('+ bgSurf + ')' }">
				<div class="text_center color_light">
					<h2>Get started</h2>
					<button role="button" type="button" aria-label="Sign Up" class="btn btn__primary"
					        @click="homeModal()">Get Started
					</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import Header from "~/components/Header";
	import HeaderMobile from "~/components/HeaderMobile";
	import bgTomatoes from "~/assets/img/informational-pages/bg-tomatoes.jpg";
	import bgSurf from "~/assets/img/informational-pages/surf-boards.jpg";
	import Modal from "~/components/ModalTemplate.vue";
	
	export default {
		components: {
			Header, HeaderMobile, Modal
		},
		head() {
			return {
				title: "Our Mission",
				meta: [
					{
						hid: "description",
						name: "description",
						content: "Our Mission description"
					}
				]
			};
		},
		methods: {
			homeModal() {
				this.$nuxt.$emit('toSignUp', true);
			}
		},
		data() {
			return {
				bgTomatoes, bgSurf
			}
		}
	};
</script>

<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";
	@import "~assets/scss/components/_informational-pages.scss";
	
	.hero_section {
		min-height: 746px;
		display: flex;
		flex-wrap: wrap;
		justify-content: center;
		align-items: center;
		margin-bottom: 57px;
		
		@media only screen and (max-width: 1260px) {
			min-height: auto;
			padding: 35px 0;
			margin-bottom: 30px;
		}
	}
	
	.hero_inner {
		text-align: center;
		position: relative;
		z-index: 5;
		
		h1, p {
			max-width: 585px;
			margin: 0 auto;
			color: $white;
			letter-spacing: 0.75px;
		}
		
		h1 {
			font-size: 48px;
			line-height: 60px;
			margin-bottom: 35px;
		}
		
		p {
			font-size: 24px;
			line-height: 52px;
			
			@media only screen and (max-width: 1260px) {
				font-size: 18px;
				line-height: 40px;
			}
		}
	}
	
	.hero_section {
		.text_left {
			max-width: 625px;
			position: relative;
			z-index: 5;
		}
	}
	
	.centered_preview {
		text-align: center;
		
		h2 {
			width: 100%;
			margin: 0 auto 50px;
			
			@media only screen and (max-width: 576px) {
				margin: 0 auto 30px;
			}
		}
		
		img {
			display: block;
			margin: 0 auto 21px;
		}
	}
	
	.text_photo {
		padding-top: 60px;
		margin-bottom: 131px;
		
		@media only screen and (max-width: 1260px) {
			margin-bottom: 50px;
		}
		
		&_row {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 33px;
			
			@media only screen and (max-width: 576px) {
				display: block;
				margin-bottom: 35px;
			}
			
			&__reverse {
				flex-direction: row-reverse;
				
				.text {
					margin-right: 0;
					margin-left: 40px;
					
					@media only screen and (max-width: 576px) {
						margin-left: 0;
					}
				}
			}
		}
		
		.text {
			width: 100%;
			max-width: 583px;
			margin-right: 40px;
		}
		
		.photo {
			width: 100%;
			max-width: 718px;
		}
		
		img {
			display: block;
			max-width: 100%;
			height: auto;
		}
		
		h2, p {
			letter-spacing: 0.75px;
		}
		
		h2 {
			font-size: 48px;
			line-height: 60px;
		}
		
		p {
			font-size: 24px;
			line-height: 52px;
		}
	}
	
	.categories_preview {
		text-align: center;
		
		.icon-red-line {
			display: inline-block;
			margin-bottom: 28px;
			
			@media only screen and (max-width: 1260px) {
				margin-bottom: 18px;
			}
		}
		
		h3 {
			font-size: 24px;
			line-height: 52px;
			letter-spacing: 0.02px;
			color: $gray_tuna;
			width: 100%;
			max-width: 574px;
			margin: 0 auto 48px;
			
			@media only screen and (max-width: 1260px) {
				font-size: 18px;
				line-height: 40px;
				margin: 0 auto 30px;
				padding: 0 15px;
			}
		}
	}
	
	.categories_list {
		width: 100%;
		max-width: 680px;
		margin: 0 auto 114px;
		flex-wrap: wrap;
		
		@media only screen and (max-width: 1260px) {
			margin: 0 auto 30px;
		}
		
		li {
			margin-bottom: 11px;
		}
		
		p {
			font-size: 12px;
			line-height: 17px;
			text-transform: uppercase;
			font-weight: 700;
		}
	}
	
	.section_full {
		min-height: 485px;
		display: flex;
		align-items: center;
		justify-content: center;
		
		@media only screen and (max-width: 1260px) {
			min-height: auto;
			padding: 35px 15px;
			margin-bottom: 20px;
		}
		
		.text_center {
			padding: 30px;
		}
		
		h2 {
			color: inherit;
			font-size: 48px;
			line-height: 60px;
			letter-spacing: 0.04px;
			margin-bottom: 21px;
			
			@media only screen and (max-width: 1260px) {
				font-size: 40px;
				line-height: 50px;
			}
		}
	}
</style>
