<template>
	<footer class="main_footer">
		<div class="container">
			<div class="main_footer_inner">
				<div class="main_footer_info">
					<div class="footer_logo">
						<img class="img_fluid" src="~/assets/img/logo-svg.svg" alt="tradelist logo">
						<i>Tradelist</i>
					</div>
					<p>&copy; Tradelist 2018</p>
				</div>
				<!--<ul v-if="$device.isMobileOrTablet" class="social_media" role="menu">-->
				<!--<li>-->
				<!--<a href="#">-->
				<!--<i class="icon-telegram"></i>-->
				<!--</a>-->
				<!--</li>-->
				<!--<li>-->
				<!--<a href="#">-->
				<!--<i class="icon-messenger"></i>-->
				<!--</a>-->
				<!--</li>-->
				<!--<li>-->
				<!--<a href="#">-->
				<!--<i class="icon-twitter"></i>-->
				<!--</a>-->
				<!--</li>-->
				<!--</ul>-->
				<div class="main_footer_navs">
					<div class="col">
						<ul role="menu">
							<li>
								<nuxt-link to="/our-mission">Mission</nuxt-link>
							</li>
							<li>
								<nuxt-link to="/how-it-works">How it works</nuxt-link>
							</li>
							<li>
								<nuxt-link to="/contact">Contact Us</nuxt-link>
							</li>
							<li>
								<nuxt-link to="/help">Customer Service</nuxt-link>
							</li>
							<li>
								<nuxt-link to="/feedback">Product Feedback</nuxt-link>
							</li>
							<li>
								<nuxt-link to="/partner-relations">Partner Relations</nuxt-link>
							</li>
						</ul>
					</div>
					<!--<div class="col" v-if="$device.isDesktop">-->
					<!--<ul role="menu">-->
					<!--<li>-->
					<!--<a href="#">Card</a>-->
					<!--</li>-->
					<!--<li>-->
					<!--<a href="#">Token Sale</a>-->
					<!--</li>-->
					<!--<li>-->
					<!--<a href="#">Whitepaper</a>-->
					<!--</li>-->
					<!--</ul>-->
					<!--<ul class="social_media" role="menu">-->
					<!--<li>-->
					<!--<a href="#">-->
					<!--<i class="icon-telegram"></i>-->
					<!--</a>-->
					<!--</li>-->
					<!--<li>-->
					<!--<a href="#">-->
					<!--<i class="icon-messenger"></i>-->
					<!--</a>-->
					<!--</li>-->
					<!--<li>-->
					<!--<a href="#">-->
					<!--<i class="icon-twitter"></i>-->
					<!--</a>-->
					<!--</li>-->
					<!--</ul>-->
					<!--</div>-->
					<!--<div class="col">-->
					<!--<div v-if="$device.isDesktop" class="mail-us">-->
					<!--<p>Contact us with any questions</p>-->
					<!--<a href="mailto:info@tradelist.com" title="email adress">info@tradelist.com</a>-->
					<!--</div>-->
					<!--<ul role="menu">-->
					<!--<li>-->
					<!--<a href="#">FAQ</a>-->
					<!--</li>-->
					<!--<li>-->
					<!--<a href="#">Privacy Policy</a>-->
					<!--</li>-->
					<!--<li>-->
					<!--<a href="#">Terms and Conditions</a>-->
					<!--</li>-->
					<!--</ul>-->
					<!--</div>-->
				</div>
			</div>
		</div>
	</footer>
</template>


<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";

	.main_footer {
		background: $shark;
		padding: 52px 0 63px;
		position: absolute;
		right: 0;
		bottom: 0;
		left: 0;

		@media only screen and (max-width: 1260px) {
			position: static;
		}

		p,
		a {
			font-size: 12px;
			line-height: 25px;
			font-weight: 700;
			color: rgba($white, 0.5);
		}

		p {
			margin: 0;
		}
	}

	.main_footer_inner {
		display: flex;
		justify-content: space-between;

		@media only screen and (max-width: 1260px) {
			flex-wrap: wrap;
		}
	}

	.main_footer_info {
		max-width: 177px;

		.footer_logo {
			margin-bottom: 8px;

			img {
				width: 38px;
				max-height: 37px;
			}

			i {
				@media only screen and (max-width: 1260px) {
					font-size: 19px;
					line-height: 26px;
					letter-spacing: 0.01px;
				}
			}
		}

		p {
			line-height: 25px;
		}
	}

	.social_media {
		display: flex;
		justify-content: space-between;
		align-items: center;
		width: 100px;

		@media only screen and (max-width: 1260px) {
			align-items: flex-start;
		}

		a {
			color: rgba($white, 0.3);
			transition: 0.2s $bezier-1;

			i:before {
				color: rgba($white, 0.3);
				transition: 0.2s $bezier-1;
			}

			&:hover {
				color: $white;

				i:before {
					color: $white;
				}
			}

			i {
				@media only screen and (max-width: 1260px) {
					font-size: 15.5px;
				}
			}
		}
	}

	.main_footer_navs {
		display: flex;
		flex-wrap: wrap;
		width: calc(100% - 171px);

		@media only screen and (max-width: 1260px) {
			justify-content: space-between;
			flex-wrap: nowrap;
			width: 100%;
		}

		.col {
			margin-left: 117px;
			padding-top: 14px;
			padding-top: 0;
			width: 100%;

			&:first-of-type {
				margin-left: 0;
			}
		}

		ul {
			padding-top: 10px;
			display: flex;
			justify-content: flex-end;
			padding-right: 70px;
			/*align-items: center;*/

			@media only screen and (max-width: 1260px) {
				flex-wrap: wrap;
				justify-content: flex-start;
			}

			li {
				/*width: 100%;*/
				margin: 0 40px;

				@media only screen and (max-width: 1260px) {
					margin: 0 10px;
				}

				@media only screen and (max-width: 576px) {
					margin: 0 5px;
					width: 100%;
				}
			}

			a {
				color: rgba($white, 0.3);
				line-height: 37px;
				display: inline-block;
				
				&.nuxt-link-exact-active {
					color: $white;
				}

				@media only screen and (max-width: 1260px) {
					font-size: 12px;
				}
			}
		}

		a {
			transition: 0.2s $bezier-1;

			&:hover {
				color: $white;
			}
		}

		.mail-us {
			margin-bottom: 32px;

			p,
			a {
				font-size: 14px;
				line-height: 25px;
			}

			& + ul {
				padding-top: 0;

				a {
					color: rgba($white, 0.5);

					&:hover {
						color: $white;
					}
				}
			}
		}
	}
</style>
