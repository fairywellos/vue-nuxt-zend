<template>
	<header class="main_header" role="banner" id="mainHeader">
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-WDP5VK2');</script>
	<!-- End Google Tag Manager -->

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-143236797-1"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', 'UA-143236797-1');
	gtag('config', 'AW-729655576');
	</script>

	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WDP5VK2"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

	<!-- Facebook Pixel Code -->
	<script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];
	s.parentNode.insertBefore(t,s)}(window, document,'script',
	'https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '2362390287178459');
	fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none"
	src="https://www.facebook.com/tr?id=2362390287178459&ev=PageView&noscript=1"
	/></noscript>
	<!-- End Facebook Pixel Code -->

		<div class="main_header__main">
			<div class="container">
				<div class="main_header__main_inner">
					<div class="main_header__left">
						<nuxt-link to="/" class="main_header__logo main_logo" title="Homepage">
							<img src="~/assets/img/logo-svg.svg" alt="main logo"><i>Tradelist</i>
						</nuxt-link>
						<SearchBar/>
					</div>
					<div class="main_header__right" v-if="isAuthenticated">
						<nuxt-link to="/find-trade-partners" class="saved_products" title="Find trade partners">
							<i class="icon-find-partners"></i> Find Trade Partners
						</nuxt-link>
						<nuxt-link to="/create-listing" class="btn btn__secondary create_listing_btn"
								   title="Create a Listing">
							<span>Create a listing</span>
						</nuxt-link>
						<div class="nav_user_account">
							<div class="user_account_info">
								<div class="user_image" v-if="loggedInUser.photo"
									 :style="{ backgroundImage: 'url('+ loggedInUser.photo + ')' }"></div>
								<div class="user_image" v-else></div>
								<p>
									{{ loggedInUser.publicName}}
									<br>
									<span class="credits">{{ loggedInUser.credits}} credit left</span>
								</p>
								<ul class="dropdown_items">
									<li>
										<nuxt-link to="/dashboard/offers">Dashboard</nuxt-link>
									</li>
									<li>
										<button @click="logout" type="button">Logout</button>
									</li>
								</ul>
							</div>
						</div>
						<div class="nav_notifications" v-on:mouseenter="changeHover()" @click="detectClickOutside()">
							<ul role="notifications">
								<li :class="{ 'is_active' : activeNotification === 1 }">
									<a href="#" class="nav_icon" @click.prevent="toggleActiveNotification(1)">
										<span v-if="getConversations.new_conversations" class="counter">{{ getConversations.new_conversations}}</span>
										<i class="icon-message"></i>
									</a>
									<div class="list_sub">
										<div class="list_head">
											<h3>Messages</h3>
										</div>
										<no-ssr>
											<div id="cS1" class="list_body list_body_messages">
												<InboxMessage wait-for="async-data"
															  v-for="(conversation, key) in getConversations.conversations"
															  :status="conversation.status" :convID="conversation.id"
															  :key="key" :conversationPhoto="conversation.photo"
															  :firstName="conversation.first_name"
															  :name="conversation.name" :text="conversation.text"
															  :dateAdded="conversation.date_added"/>
												<div class="empty">No messages yet</div>
											</div>
										</no-ssr>

										<nuxt-link to="/dashboard/messages" class="see_all" title="see all">
											See all
										</nuxt-link>
									</div>
								</li>
								<li :class="{ 'is_active' : activeNotification === 2 }">
									<a href="#" class="nav_icon" @click.prevent="toggleActiveNotification(2)">
										<span v-if="getNotifications.unseen" class="counter">{{ getNotifications.unseen }}</span>
										<i class="icon-notifications"></i>
									</a>
									<div class="list_sub">
										<div class="list_head">
											<h3>Notifications</h3>
										</div>
										<no-ssr>
											<div id="cS2" class="list_body list_body_notifications">
												<div class="offers">
													<nuxt-link title="Notifications"
															   :to="notification.link"
															   v-for="(notification, key) in getNotifications.notifications"
															   :class="[notification.status != 1 ? 'unread' : '']"
															   :key="key" @click.native="markAsRead(notification)">
														<span v-if="notification.photo" class="user_image"
															  :style="{ backgroundImage: 'url('+ notification.photo + ')' }"></span>
														<span v-else class="user_image"></span>
														<span class="info">
																		<h4>{{ notification.title }}</h4>
																		<p>{{ notification.description }}</p>
																	</span>
														<span
															class="date_time"><!--{{ notification.dateAdded }}--></span>
													</nuxt-link>
													<div class="empty">No notifications yet</div>
												</div>
											</div>
										</no-ssr>

										<button v-if="seeAllNotifications" class="see_all"
												@click="toggleSeeAllNotifications()" title="see less">See less
										</button>
										<button v-else="seeAllNotifications" class="see_all"
												@click="toggleSeeAllNotifications()" title="see all">See all
										</button>
									</div>
								</li>
							</ul>
						</div>
					</div>
					<div class="main_header__right not_logged" v-else>
						<div class="nav_user_account">
							<ul role="group">
								<li>
									<button role="button" type="button" aria-label="Sign Up" class="btn btn__secondary"
											@click="openModalSignUp()">Sign Up
									</button>
								</li>
								<li>
									<button role="button" type="button" aria-label="Sign In" class="btn btn__secondary"
											@click="openModalLogin()">Sign In
									</button>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<no-ssr>
			<modal v-if="showModalSignUp" class="modal_sign_up user_modal" @close="closeModalSignUp()">
				<div slot="body">
					<div class="main_logo">
						<img src="~/assets/img/logo-svg.svg" alt="main logo"><i>Tradelist</i>
					</div>
					<p>Welcome, let’s get started</p>
					<form method="post" @submit.prevent="register">
						<div class="form_group">
							<input type="text" placeholder="First Name" required name="first_name" v-model="first_name">
							<i class="icon-user"></i>
						</div>
						<div class="form_group">
							<input type="text" placeholder="Last Name" required name="name" v-model="name">
							<i class="icon-user"></i>
						</div>
						<div class="form_group">
							<input type="text" placeholder="Display Name" required name="display_name"
								   v-model="display_name">
							<i class="icon-user"></i>
						</div>
						<div class="form_group">
							<input type="email" placeholder="Email Address" required name="email" v-model="email"
								   :class="{ 'validation_error': errorEmailAuth }">
							<i class="icon-envelope"></i>
						</div>
						<div class="form_group">
							<input type="password" placeholder="Password" required name="password" v-model="password">
							<i class="icon-lock"></i>
						</div>
						<div v-if="errorEmailAuth" class="help is_danger">{{ errorEmailAuth }}</div>
						<button type="submit" class="btn btn__primary" role="button" aria-label="Create an account">
							<span>Create an account</span>
						</button>
					</form>
					<div class="terms">
						<div class="custom-checkbox">
							<input type="checkbox" checked id="terms">
							<label for="terms">
								<span class="checkmark"></span>
								<n-link to="/terms-and-contions">Terms and Condition</n-link>
							</label>
						</div>
					</div>
					<div class="extra_option">
						<p>
							Aready have an account?
							<button @click="switchModalLogin()" type="button" role="button" aria-label="Log in">Log in
							</button>
						</p>
					</div>
				</div>
			</modal>
			<modal v-if="showModalLogin" class="modal_login user_modal" @close="closeModalLogin()">
				<div slot="body">
					
					
					<p>Welcome back!</p>
					<form method="post" @submit.prevent="login" :class="{'form_invalid' : errorEmailLog}">
						<div class="form_group">
							<input type="email" placeholder="Email Adress" required name="email" v-model="email"
								   :class="{ 'validation_error': errorEmailAuth }">
							<i class="icon-envelope"></i>
						</div>
						<div class="form_group">
							<input type="password" placeholder="Password" required name="password" v-model="password">
							<i class="icon-lock"></i>
						</div>
						<div v-if="errorEmailLog" class="help is_danger">{{ errorEmailLog[0] }}</div>
						<button type="submit" class="btn btn__primary" role="button">Sign in</button>
					</form>
					<div class="extra_option">
						<p>
							Forgot password?
							<button type="button" role="button">Reset</button>
						</p>
						<p>
							Don't have an account?
							<button @click="switchModalSignUp()" type="button" role="button">Signup</button>
						</p>
					</div>
				</div>
			</modal>
			<modal v-if="showModalEmailCode" class="modal_login user_modal modal_email_code"
				   @close="closeModalEmailCode()">
				<div slot="body">
					<div class="main_logo">
						<img src="~/assets/img/logo-svg.svg" alt="main logo"><i>Tradelist</i>
					</div>
					<p>Enter Validation Code</p>
					<form method="post" @submit.prevent="activate" class="email_code"
						  :class="{'form_invalid' : errorActivateCode && !resendActivationEmail}">
						<div class="form_group">
							<input type="text" required v-model="digit1" name="digit1" maxlength="1"
								   @paste="splitPaste($event)" v-on:keyup="focusSiblings($event)">
							<input type="text" required v-model="digit2" name="digit2" maxlength="1"
								   @paste="splitPaste($event)" v-on:keyup="focusSiblings($event)">
							<input type="text" required v-model="digit3" name="digit3" maxlength="1"
								   @paste="splitPaste($event)" v-on:keyup="focusSiblings($event)">
							<input type="text" required v-model="digit4" name="digit4" maxlength="1"
								   @paste="splitPaste($event)" v-on:keyup="focusSiblings($event)">
							<input type="text" required v-model="digit5" name="digit5" maxlength="1"
								   @paste="splitPaste($event)" v-on:keyup="focusSiblings($event)">
							<input type="text" required v-model="digit6" name="digit6" maxlength="1"
								   @paste="splitPaste($event)" v-on:keyup="focusSiblings($event)">
						</div>
						<div v-if="errorActivateCode" class="is_danger help">
							{{ errorActivateCode }}
						</div>
						<p v-if="resendActivationEmail" class="is_sent help">
							{{ resendActivationEmail }}
						</p>
						<input type="submit" class="btn btn__primary" value="Submit">
					</form>
					<div class="extra_option">
						<p>
							Didn’t get validation code?
							<button type="button" @click="resendActivationCode" role="button"
									aria-label="Resend email code">Resend
							</button>
						</p>
					</div>
				</div>
			</modal>
		</no-ssr>
	</header>
</template>

<script>
	import Modal from "~/components/ModalTemplate.vue";
	import SearchBar from "~/components/SearchBar.vue";
	import InboxMessage from "~/components/InboxMessage.vue";
	import bgUser from "~/assets/img/user.png";
	import headerJs from "~/assets/js/header.js";
	import {
		mapGetters
	} from "vuex";

export default {
	components: {
		Modal,
		SearchBar,
		InboxMessage
	},
	data() {
		return {
			activeNotification: false,
			max: 1,
			limitText: "",
			wasHovered: false,
			categories: {
				artCollectibles: "art & collectibles",
				artsCrafts: "arts & crafts",
				experiences: "experiences",
				motors: "motors",
				clothingAccessories: "clothing & accessories",
				homesHousing: "homes & housing",
				electronics: "electronics",
				services: "services",
				portingGoods: "porting goods",
				businessIndustrial: "business & industrial",
				other: "other"
			},
			bgUser,
			showModalSignUp: false,
			showModalLogin: false,
			showModalEmailCode: false,
			showNotifications: false,
			first_name: "",
			display_name: "",
			name: "",
			password: "",
			email: "",
			digit1: "",
			digit2: "",
			digit3: "",
			digit4: "",
			digit5: "",
			digit6: "",
			error: "null",
			errorEmailAuth: false,
			errorEmailLog: false,
			errorActivateCode: false,
			resendActivationEmail: false,
			seeAllNotifications: false,
		};
	},
	mounted() {
		this.$nuxt.$on('toSignUp', (bool) => {
			this.showModalSignUp = bool;
		})
	},
	computed: {
		...mapGetters("auth", ["isAuthenticated", "loggedInUser"]),
		...mapGetters("messages", ["getConversations"]),
		...mapGetters("notifications", ["getNotifications"])
	},
	extends: headerJs,
};
</script>

<style lang="scss">
	@import "~assets/scss/abstracts/_abstracts.scss";

	.main_header {
		background: $gray_albaster;
		border-bottom: 1px solid $gray_aluminium2;
		position: fixed;
		top: 0;
		right: 0;
		left: 0;
		z-index: 999;

		&.main_header__user_logged {
			justify-content: space-between;
		}

		.form_search {
			.input_icon_wrap {
				@media only screen and (max-width: 1659px) {
					width: 340px;

					& + .input_icon_wrap {
						width: 160px;
					}
				}

				@media only screen and (max-width: 1599px) {
					width: 240px;

					& + .input_icon_wrap {
						width: 140px;
					}
				}
			}
		}

		.select-target {
			@include source_sans_pro(400);
			font-size: 12px;
			color: $gray_aluminium;
			border-radius: 100px;
			width: 259px;
			text-transform: capitalize;
			height: 41px;
			line-height: 39px;
			padding-left: 46px;
			border: 1px solid #e6e5e5;
			display: block;
			background: transparent;

			b {
				right: 0;
				height: 41px;
				width: 48px;
				background: #e5ebf2;

				&:after {
					display: none;
				}

				&:before {
					top: 50%;
					left: 50%;
					right: auto;
					border: none;
					margin: 0;
					transform: translate(-50%, -50%);
					width: 5px;
					height: 5px;
					background: $blue_picton;
				}
			}
		}

		.currency_switcher,
		.language_switcher {
			margin-left: 24px;

			a {
				display: block;
				font-size: 12px;
				line-height: 16px;
				color: $gray_aluminium;
			}

			.switcher_sub {
				display: none;
			}
		}

		.saved_products {
			font-size: 12px;
			line-height: 16px;
			letter-spacing: 0.5px;
			color: $gray_aluminium;
			text-transform: uppercase;
			margin-right: 20px;
			position: relative;
			padding: 31px 0 31px 25px;
			border-bottom: 3px solid transparent;
			transition: 0.2s $bezier_ease_in_out;

			&.nuxt-link-exact-active {
				border-bottom-color: $blue_picton;
				color: $gray_tuna;
			}

			&:hover {
				color: $gray_tuna;
			}

			i {
				font-size: 20px;
				position: absolute;
				top: 50%;
				left: 0;
				transform: translateY(-50%);
			}

			@media only screen and (max-width: 1399px) {
				max-width: 130px;
				margin-left: 15px;
			}
		}

		.nav_user_account {
			position: relative;
			margin-right: 14px;
			padding: 11px 16px 11px 0;

			.user_account_info {
				font-size: 12px;
				line-height: 16px;
				color: $gray_aluminium;
				text-decoration: none;
				display: flex;
				justify-content: space-between;
				align-items: center;
				transition: 0.2s $bezier_ease_in_out;

				&:after {
					content: "";
					position: absolute;
					top: 44%;
					right: 0;
					width: 7px;
					height: 7px;
					transform: translateY(-50%) rotate(-45deg);
					border-left: 2px solid $gray_gallery;
					border-bottom: 2px solid $gray_gallery;
					transition: 0.2s $bezier_ease_in_out;
				}

				.dropdown_items {
					position: absolute;
					top: 100%;
					width: 100%;
					background: $gray_albaster;
					border-radius: 4px;
					padding: 0 15px;
					border: 1px solid $gray_aluminium2;
					opacity: 0;
					height: 0;
					overflow: hidden;
					transition: 0.2s $bezier_ease_in_out;

					li {
						margin-bottom: 5px;

						a,
						button {
							text-transform: capitalize;
							color: $gray_tuna;
							font-size: 12px;
							line-height: 21px;
							display: block;
							padding: 0;
							transition: 0.2s $bezier_ease_in_out;

							&:hover {
								color: $blue_scooter;
							}
						}

						&:last-child {
							margin-bottom: 0;
						}
					}
				}
			}

			&:hover {
				.user_account_info {
					color: $gray_tuna;

					.dropdown_items {
						opacity: 1;
						height: auto;
						max-height: 500px;
						overflow: visible;
						padding: 10px 15px;
					}

					&:after {
						transform: translateY(-50%) rotate(130deg);
					}
				}
			}
		}

		.credits {
			font-size: 10px;
			line-height: 18px;
			padding: 0 10px;
			margin-top: 1px;
			color: $white;
			background: $saffron;
			border-radius: 10px;
			display: inline-block;

			@media only screen and (max-width: 1299px) {
				padding: 0 9px;
				line-height: 14px;
			}
		}

		.user_image {
			width: 36px;
			height: 36px;
			border-radius: 50%;
			background-position: center;
			background-size: cover;
			background-repeat: no-repeat;
			margin-right: 8px;
			display: block;

			@media only screen and (max-width: 1599px) {
				display: none;
			}
		}

		.product_image {
			width: 49px;
			height: 49px;
			border-radius: 3px;
			background-size: cover;
			background-repeat: no-repeat;
			background-position: center;
			margin-right: 8px;
			display: block;
		}

		.nav_notifications {
			ul {
				display: flex;
				align-self: center;
			}

			li {
				position: relative;
				margin-left: 17px;

				&.is_active {
					.list_sub {
						opacity: 1;
						visibility: visible;
						z-index: 9;
						display: flex;
					}

					.nav_icon {
						color: $blue_scooter;
					}
				}

				&:hover {
					.nav_icon {
						color: $blue_scooter;
					}
				}

				&:first-child {
					margin-left: 0;
				}
			}

			.nav_icon {
				color: $gray_aluminium;
				display: inline-block;
				position: relative;
				transition: 0.2s $bezier-1;

				i {
					font-size: 22px;
					transition: 0.2s $bezier_ease_in_out;
				}
			}

			.info {
				display: block;
			}

			> ul {
				> li {
					a {
						.counter {
							position: absolute;
							top: -8px;
							right: -8px;
							z-index: 9;
						}
					}
				}
			}

			.counter {
				display: inline-block;
				font-size: 10px;
				line-height: 17px;
				color: $white;
				background: $carnation;
				text-align: center;
				width: 17px;
				height: 17px;
				border-radius: 50%;
			}

			.list_sub {
				position: absolute;
				top: 100%;
				right: -20px;
				min-width: 362px;
				background: $white;
				box-shadow: 0 0 6px 0 rgba(37, 36, 36, 0.3);
				border-radius: 3px;
				z-index: -1;
				display: none;
				visibility: hidden;
				flex-direction: column;
				opacity: 0;
				transition: 0.2s $bezier-1;

				.message_preview,
				a {
					margin-left: 0;
					transition: 0.2s $bezier-1;

					&:hover {
						background: darken($white, 2%);
					}
				}

				.list_body {
					max-height: 585px;
					overflow: hidden;
					padding: 0 0 33px;
				}

				.list_body_title {
					padding: 5px 21px;
					font-size: 12px;
					line-height: 16px;
					text-align: center;
					color: $gray_aluminium;
					background: rgba($gray_aluminium, 0.13);
					border-bottom: 1px solid $gray_aluminium2;
					display: flex;
					justify-content: center;
					align-items: center;

					div {
						font-size: inherit;
						color: inherit;
						position: relative;
					}

					.counter {
						margin-left: 6px;
					}
				}

				.list_body_notifications {
					a {
						&.unread {
							background: rgba($gray_aluminium, 0.08);
						}
					}
				}

				.user_image {
					margin-right: 16px;
				}

				.list_head {
					position: relative;
					display: flex;
					justify-content: space-between;
					align-items: center;
					padding: 36px 30px;

					&:after {
						content: "";
						position: absolute;
						left: 0;
						top: 100%;
						width: 100%;
						height: 26px;
						background: -moz-linear-gradient(bottom, rgba(255, 255, 255, 0.06) 0%, rgba(0, 0, 0, 0.06) 100%);
						background: -webkit-linear-gradient(bottom, rgba(255, 255, 255, 0.06) 0%, rgba(0, 0, 0, 0.06) 100%);
						background: linear-gradient(to top, rgba(255, 255, 255, 0.06) 0%, rgba(0, 0, 0, 0.06) 100%);
						z-index: 9;
					}
				}

				.see_all {
					background: $white;
					text-align: center;
					font-size: 12px;
					line-height: 16px;
					color: $blue_scooter;
					display: block;
					padding: 8px;
					position: absolute;
					width: 100%;
					left: 0;
					bottom: 0;
					z-index: 10;
					border-bottom-left-radius: 3px;
					border-bottom-right-radius: 3px;

					&:before {
						content: "";
						display: block;
						position: absolute;
						top: -26px;
						left: 0;
						width: 100%;
						height: 26px;
						background: -moz-linear-gradient(top, rgba(255, 255, 255, 0.06) 0%, rgba(0, 0, 0, 0.06) 100%);
						background: -webkit-linear-gradient(top, rgba(255, 255, 255, 0.06) 0%, rgba(0, 0, 0, 0.06) 100%);
						background: linear-gradient(to bottom, rgba(255, 255, 255, 0.06) 0%, rgba(0, 0, 0, 0.06) 100%);
						z-index: 9;
					}
				}
			}
		}

		.list_body {
			padding: 0 0 33px;
		}

		.ss-container {
			height: 585px;
		}

		.not_logged {
			.nav_user_account {
				margin-right: 0;
				padding: 0;

				ul {
					display: flex;
					align-items: center;

					li {
						margin-right: 26px;

						&:last-child {
							margin-right: 0;
						}
					}
				}
			}
		}
	}

	.main_header__main_inner,
	.main_header__top_inner {
		display: flex;
		align-items: center;
	}

	.main_header__top {
		padding: 8px 0;
		background: rgba($gray_iron, 0.17);
	}

	.main_header__top_inner {
		justify-content: flex-end;
	}

	.main_header__main {
		transition: 0.2s $bezier_ease_in_out;
	}

	.main_header__main_inner {
		display: flex;
		justify-content: space-between;

		.create_listing_btn {
			margin-right: 37px;
			padding: 12px 19px;
		}

		.btn__secondary {
			padding: 12px 30px;

			@media only screen and (max-width: 1599px) {
				padding: 12px 15px;
			}
		}
	}

	.main_header__left,
	.main_header__right {
		display: flex;
		align-items: center;
	}

	.main_header__right {
		&.not_logged {
			margin-right: 0;
			padding: 21px 0;

			.nav_user_account {
				li {
					margin-right: 10px;
				}
			}
		}
	}

	.main_header__logo {
		align-self: flex-start;
		margin-top: 5px;
		margin-right: 35px;

		&.main_logo {
			i {
				@media only screen and (max-width: 1599px) {
					font-size: 23px;
					line-height: 31px;
				}
			}
		}

		img {
			max-width: 48px;

			@media only screen and (max-width: 1599px) {
				width: 40px;
			}
		}
	}
</style>
