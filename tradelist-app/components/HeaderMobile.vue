<template>
	<header
		class="mh_mobile"
		:class="{'main_visible' : menuVisible}"
		role="banner"
		id="mainHeaderMobile"
	>
		<div class="mh_mobile__inner" :class="{'user_is_logged' : isAuthenticated}">
			<div class="mh_mobile__menu" @click="toggleMenuMobile()">
				<div class="main_logo">
					<img src="~/assets/img/logo-svg.svg" alt="main logo">
				</div>
				<button type="button" class="menu_toggle">
					<span class="arrow"></span>
				</button>
			</div>
			<SearchBarMobile/>
			<div v-if="isAuthenticated" class="nav_notifications">
				<ul>
					<li :class="{ 'is_active' : activeNotification === 1 }">
						<div @click.prevent="toggleActiveNotification(1)" class="nav_icon">
							<span
								v-if="getConversations.new_conversations"
								class="counter"
							>{{ getConversations.new_conversations}}</span>
							<i class="icon-message"></i>
						</div>
						<div class="list_sub">
							<button
								type="button"
								role="button"
								class="close_notifications"
								@click="closeNotifications()"
							>
								<i class="icon-close"></i>
							</button>
							<div class="list_head">
								<h3>Messages</h3>
								<!-- <button class="btn_dots" role="button">
									<span></span>
									<span></span>
									<span></span>
								</button>-->
							</div>
							<no-ssr>
								<div id="cS1" class="list_body list_body_messages">
									<InboxMessage
										wait-for="async-data"
										v-for="(conversation, key) in getConversations.conversations"
										:status="conversation.status"
										:convID="conversation.id"
										:key="key"
										:conversationPhoto="conversation.photo"
										:firstName="conversation.first_name"
										:name="conversation.name"
										:text="conversation.text"
										:dateAdded="conversation.date_added"
									/>
									<div class="empty">No messages yet</div>
								</div>
							</no-ssr>

							<nuxt-link to="/dashboard/messages" class="see_all" title="see all">See all</nuxt-link>
						</div>
					</li>
					<li :class="{ 'is_active' : activeNotification === 2 }">
						<div class="nav_icon" @click.prevent="toggleActiveNotification(2)">
							<span v-if="getNotifications.unseen" class="counter">{{ getNotifications.unseen }}</span>
							<i class="icon-notifications"></i>
						</div>
						<div class="list_sub">
							<button
								type="button"
								role="button"
								class="close_notifications"
								@click="closeNotifications()"
							>
								<i class="icon-close"></i>
							</button>
							<div class="list_head">
								<h3>Notifications</h3>
								<!-- <button class="btn_dots" role="button">
												<span></span>
												<span></span>
												<span></span>
								</button>-->
							</div>
							<no-ssr>
								<div id="cS2" class="list_body list_body_notifications">
									<div class="offers">
										<nuxt-link
											title="Notifications"
											:to="notification.link"
											v-for="(notification, key) in getNotifications.notifications"
											:class="[notification.status != 1 ? 'unread' : '']"
											:key="key" @click.native="markAsRead(notification)"
										>
											<span
												v-if="notification.photo"
												class="user_image"
												:style="{ backgroundImage: 'url('+ notification.photo + ')' }"
											></span>
											<span v-else class="user_image"></span>
											<span class="info">
												<h4>{{ notification.title }}</h4>
												<p>{{ notification.description }}</p>
											</span>
											<span class="date_time">{{ notification.dateAdded }}</span>
										</nuxt-link>
										<div class="empty">No notifications yet</div>
									</div>
								</div>
							</no-ssr>

							<button
								v-if="seeAllNotifications"
								class="see_all"
								@click="toggleSeeAllNotifications()"
								title="see less"
							>See less</button>
							<button
								v-else="seeAllNotifications"
								class="see_all"
								@click="toggleSeeAllNotifications()"
								title="see all"
							>See all</button>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<div class="mh_mobile__drop_menu" :class="{'is_visible' : menuVisible}">
			<div class="main_menu">
				<n-link to="/" title="Homepage">Home</n-link>
				<n-link to="/browse-local" title="Browse Local">Browse Local</n-link>
				<n-link v-if="isAuthenticated" to="/find-trade-partners">
					<i class="icon-find-partners"></i> Find Trade Partners
				</n-link>
			</div>
			<div v-if="isAuthenticated" class="menu_toolbox logged_in">
				<div
					class="user_image"
					v-if="loggedInUser.photo"
					:style="{ backgroundImage: 'url('+ loggedInUser.photo + ')' }"
				></div>
				<div class="user_image" v-else></div>
				<p>
					{{ loggedInUser.publicName}}
				</p>
				<ul>
					<li>
						<n-link to="/dashboard/offers">
							<i class="icon-offers"></i> Offers
						</n-link>
					</li>
					<li>
						<n-link to="/dashboard/messages">
							<i class="icon-message"></i> Messages
						</n-link>
					</li>
					<li>
						<n-link to="/dashboard/saved">
							<i class="icon-save"></i> Saved
						</n-link>
					</li>
					<li>
						<n-link to="/dashboard/settings">
							<i class="icon-settings"></i>Settings
						</n-link>
					</li>
				</ul>
			</div>
			<div v-else class="menu_toolbox">
				<ul>
					<li @click="openModalSignUp()">Join</li>
					<li @click="openModalLogin()">
						<i class="icon-lock"></i>Sign in
					</li>
				</ul>
			</div>
			<nuxt-link
				v-if="isAuthenticated"
				to="/create-listing"
				class="btn btn__secondary"
			>Create a listing</nuxt-link>
			<div v-if="isAuthenticated" class="user_actions">
				<button @click="logout" type="button">Logout</button>
			</div>
		</div>
		<no-ssr>
			<modal v-if="showModalSignUp" class="modal_sign_up user_modal" @close="closeModalSignUp()">
				<div slot="body">
					<div class="main_logo">
						<img src="~/assets/img/logo-svg.svg" alt="main logo">
						<i>Tradelist</i>
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
							<input
								type="text"
								placeholder="Display Name"
								required
								name="display_name"
								v-model="display_name"
							>
							<i class="icon-user"></i>
						</div>
						<div class="form_group">
							<input
								type="email"
								placeholder="Email Address"
								required
								name="email"
								v-model="email"
								:class="{ 'validation_error': errorEmailAuth }"
							>
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
					<div class="extra_option">
						<p>
							Aready have an account?
							<button
								@click="switchModalLogin()"
								type="button"
								role="button"
								aria-label="Log in"
							>Log in</button>
						</p>
					</div>
				</div>
			</modal>
			<modal v-if="showModalLogin" class="modal_login user_modal" @close="closeModalLogin()">
				<div slot="body">
					<div class="main_logo">
						<img src="~/assets/img/logo-svg.svg" alt="main logo">
						<i>Tradelist</i>
					</div>
					<p>Welcome back!</p>
					<form method="post" @submit.prevent="login" :class="{'form_invalid' : errorEmailLog}">
						<div class="form_group">
							<input
								type="email"
								placeholder="Email Adress"
								required
								name="email"
								v-model="email"
								:class="{ 'validation_error': errorEmailAuth }"
							>
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
			<modal
				v-if="showModalEmailCode"
				class="modal_login user_modal modal_email_code"
				@close="closeModalEmailCode()"
			>
				<div slot="body">
					<div class="main_logo">
						<img src="~/assets/img/logo-svg.svg" alt="main logo">
						<i>Tradelist</i>
					</div>
					<p>Enter Validation Code</p>
					<form
						method="post"
						@submit.prevent="activate"
						class="email_code"
						:class="{'form_invalid' : errorActivateCode && !resendActivationEmail}"
					>
						<div class="form_group">
							<input
								type="text"
								required
								v-model="digit1"
								name="digit1"
								maxlength="1"
								@paste="splitPaste($event)"
								v-on:keyup="focusSiblings($event)"
							>
							<input
								type="text"
								required
								v-model="digit2"
								name="digit2"
								maxlength="1"
								@paste="splitPaste($event)"
								v-on:keyup="focusSiblings($event)"
							>
							<input
								type="text"
								required
								v-model="digit3"
								name="digit3"
								maxlength="1"
								@paste="splitPaste($event)"
								v-on:keyup="focusSiblings($event)"
							>
							<input
								type="text"
								required
								v-model="digit4"
								name="digit4"
								maxlength="1"
								@paste="splitPaste($event)"
								v-on:keyup="focusSiblings($event)"
							>
							<input
								type="text"
								required
								v-model="digit5"
								name="digit5"
								maxlength="1"
								@paste="splitPaste($event)"
								v-on:keyup="focusSiblings($event)"
							>
							<input
								type="text"
								required
								v-model="digit6"
								name="digit6"
								maxlength="1"
								@paste="splitPaste($event)"
								v-on:keyup="focusSiblings($event)"
							>
						</div>
						<div v-if="errorActivateCode" class="is_danger help">{{ errorActivateCode }}</div>
						<p v-if="resendActivationEmail" class="is_sent help">{{ resendActivationEmail }}</p>
						<input type="submit" class="btn btn__primary" value="Submit">
					</form>
					<div class="extra_option">
						<p>
							Didn’t get validation code?
							<button
								type="button"
								@click="resendActivationCode"
								role="button"
								aria-label="Resend email code"
							>Resend</button>
						</p>
					</div>
				</div>
			</modal>
		</no-ssr>
	</header>
</template>

<script>
	import Modal from "~/components/ModalTemplate.vue";
	import SearchBarMobile from "~/components/SearchBarMobile.vue";
	import InboxMessage from "~/components/InboxMessage.vue";
	import bgUser from "~/assets/img/user.png";
	import headerJs from "~/assets/js/header.js";
	import { mapGetters } from "vuex";

	export default {
		components: {
			Modal,
			SearchBarMobile,
			InboxMessage
		},
		extends: headerJs,
		data() {
			return {
				menuVisible: false,
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
				// allMessages: messages.allMsg,
				// allOffers: notifications.offers,
				// allWatchlist: notifications.watchlist,
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
				seeAllNotifications: false
			};
		},
		computed: {
			...mapGetters("auth", ["isAuthenticated", "loggedInUser"]),
			...mapGetters("messages", ["getConversations"]),
			...mapGetters("notifications", ["getNotifications"])
		},
		mounted() {
			if (this.$auth.loggedIn) {
				this.$pusher.subscribe("user-channel-" + this.$auth.user.id);
			} else {
				this.$pusher.disconnect();
			}
		}
	};
</script>

<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";

	.mh_mobile {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		z-index: 99;
		box-shadow: 0 2px 6px rgba($black, 0.1);
		background: $white;
		padding: 0 15px;

		&__menu {
			position: relative;
			width: 60px;
			margin-right: 10px;

			img {
				max-width: 33px;
				max-height: 32px;
			}
		}

		.menu_toggle {
			position: absolute;
			right: 0;
			top: 50%;
			transform: translateY(-50%);

			.arrow {
				position: relative;
				top: -4px;
				border: solid #353c43;
				border-width: 0 1px 1px 0;
				opacity: 0.6;
				display: inline-block;
				padding: 4px;
				transform: rotate(45deg);
			}
		}

		&__inner {
			background: $white;
			padding: 10px 0;
			position: relative;
			display: flex;
			align-items: center;
			z-index: 9;
		}

		&__drop_menu {
			background: $white;
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100vh;
			visibility: hidden;
			transform: translateY(-100%);
			transition: 0.25s $bezier_ease_in_out;
			padding: 71px 15px 20px;
			z-index: 8;

			& * {
				opacity: 0;
				transition: 0.6s $bezier_ease_in_out;
			}

			&.is_visible {
				transform: translateY(0%);
				opacity: 1;
				visibility: visible;
				bottom: 0;
				overflow-x: hidden;
				overflow-y: auto;

				& * {
					opacity: 1;
				}
			}

			.btn {
				padding: 10.5px 20px;
				margin-left: 3px;
			}
		}
	}

	.form_search {
		width: calc(100% - 80px);
	}

	.user_is_logged {
		.form_search {
			width: calc(100% - 131px);
			margin-right: 10px;
		}

		.counter {
			position: absolute;
			right: -7px;
			top: -7px;
			border-radius: 50%;
			background: #ef5362;
			width: 17px;
			height: 17px;
			font-size: 10px;
			line-height: 17px;
			text-align: center;
			color: $white;
		}
	}

	.nav_notifications {
		width: 51px;

		ul {
			width: 100%;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		li {
			margin: 0 5px;
			position: relative;

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

			i {
				color: $gray_aluminium;
				font-size: 22px;
			}

			.icon-message {
				font-size: 22px;
			}

			.icon-notifications {
				font-size: 24px;
			}
		}
	}

	.list_sub {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		width: 100%;
		height: 100vh;
		background: $white;
		box-shadow: 0 0 6px 0 rgba(37, 36, 36, 0.3);
		border-radius: 3px;
		z-index: -1;
		display: none;
		visibility: hidden;
		flex-direction: column;
		opacity: 0;
		transition: 0.2s $bezier-1;

		.close_notifications {
			position: absolute;
			top: 20px;
			right: 20px;
			z-index: 5;

			i {
				font-size: 20px;
			}
		}

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

			.empty {
				padding: 30px 15px;
			}

			.message_preview,
			a {
				padding: 15px;
			}
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
			padding: 30px 15px;

			&:after {
				content: "";
				position: absolute;
				left: 0;
				top: 100%;
				width: 100%;
				height: 26px;
				background: -moz-linear-gradient(
					bottom,
					rgba(255, 255, 255, 0.06) 0%,
					rgba(0, 0, 0, 0.06) 100%
				);
				background: -webkit-linear-gradient(
					bottom,
					rgba(255, 255, 255, 0.06) 0%,
					rgba(0, 0, 0, 0.06) 100%
				);
				background: linear-gradient(
					to top,
					rgba(255, 255, 255, 0.06) 0%,
					rgba(0, 0, 0, 0.06) 100%
				);
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
				background: -moz-linear-gradient(
					top,
					rgba(255, 255, 255, 0.06) 0%,
					rgba(0, 0, 0, 0.06) 100%
				);
				background: -webkit-linear-gradient(
					top,
					rgba(255, 255, 255, 0.06) 0%,
					rgba(0, 0, 0, 0.06) 100%
				);
				background: linear-gradient(
					to bottom,
					rgba(255, 255, 255, 0.06) 0%,
					rgba(0, 0, 0, 0.06) 100%
				);
				z-index: 9;
			}
		}
	}

	.main_menu a,
	.menu_toolbox ul li {
		font-size: 12px;
		line-height: 16px;
		margin-bottom: 10px;
		padding: 7px 0;
		display: flex;
		align-items: center;
		width: 100%;
		letter-spacing: 1.2px;
		text-transform: uppercase;

		&:last-child {
			margin-bottom: 0;
		}
	}

	.main_menu,
	.menu_toolbox {
		padding-bottom: 10px;
		border-bottom: 1px solid $gray_aluminium2;
	}

	.main_menu {
		margin-bottom: 10px;

		a,
		i {
			color: $gray_tuna;
		}

		a {
			color: $gray_tuna;
		}

		i {
			font-size: 16px;
			margin-right: 5px;
		}
	}

	.menu_toolbox {
		margin-bottom: 21px;

		ul {
			li {
				color: $blue_scooter;
			}

			i {
				color: $blue_scooter;
				font-size: 15px;
				margin-right: 7px;
			}
		}

		.user_image {
			width: 36px;
			height: 36px;
			border-radius: 50%;
			background-size: cover;
			background-repeat: no-repeat;
			background-position: center;
			background-image: url("~assets/img/user_default.png");
			border: 1px solid $gray_aluminium;
			margin-right: 8px;
		}

		&.logged_in {
			display: flex;
			flex-wrap: wrap;
			align-items: center;

			ul {
				width: 100%;

				li {
					a {
						color: $gray_tuna;
						position: relative;
						padding-right: 17px;
						width: 100%;

						i {
							position: absolute;
							top: 50%;
							right: 0;
							transform: translateY(-50%);
							color: $gray_tuna;
							font-size: 15px;
						}
					}
				}
			}
		}

		p {
			font-size: 12px;
			line-height: 16px;
			color: $gray_aluminium;
			margin-bottom: 10px;

			span {
				display: block;
				font-size: 10px;
				line-height: 13px;
				color: $white;
				padding: 2px 3px;
				border-radius: 10px;
				width: 70px;
				background: $saffron;
				text-align: center;
			}
		}
	}

	.user_actions {
		padding-top: 20px;
		border-top: 1px solid $gray_aluminium2;
		width: 100%;
		margin-top: 21px;

		button {
			font-size: 12px;
			line-height: 16px;
			letter-spacing: 1.2px;
			text-transform: uppercase;
			color: $gray_tuna;
		}
	}
</style>
