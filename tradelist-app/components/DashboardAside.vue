<template>
    <aside class="dashboard_aside">
        <div v-if="loggedInUser.photo" class="user_thumbnail" :style="{ backgroundImage: 'url('+ loggedInUser.photo + ')' }"></div>
        <div v-else class="user_thumbnail" ></div>
        <h3>{{ loggedInUser.publicName}}</h3>
        <div class="credits">
            <p>
                <strong>{{ usedCredits }}</strong> of
                <strong>{{ totalCredits }}</strong> used
            </p>
            <div class="upgrade" data-tooltip="Coming Soon!">
                <span>Upgrade</span>
            </div>
            <div class="progress_bar">
                <div class="bar" :data-used-credits="usedCredits"></div>
            </div>
        </div>
        <nav>
            <nuxt-link to="/dashboard/offers">
                <i class="icon-offers"></i>
                Offers
            </nuxt-link>
            <ul>
                <li>
                    <nuxt-link to="/dashboard/offers/">Offers i've made</nuxt-link>
                </li>
                <li>
                    <nuxt-link to="/dashboard/offers/my-listings">My listings</nuxt-link>
                </li>
            </ul>
			<nuxt-link to="/dashboard/trade-history">
				<i class="icon-pin"></i>
				Trade History
			</nuxt-link>
			<nuxt-link to="/dashboard/messages">
				<i class="icon-message"></i>
				Messages
				<span v-if="getConversations.new_conversations != 0" class="badge">{{getConversations.new_conversations}}</span>
			</nuxt-link>
			<nuxt-link to="/dashboard/saved/">
				<i class="icon-save"></i>
				Saved
			</nuxt-link>
			<ul>
				<li>
					<nuxt-link to="/dashboard/saved/">Watchlist</nuxt-link>
				</li>
				<li>
					<nuxt-link to="/dashboard/saved/saved-searches">Saved searches</nuxt-link>
				</li>
				<li>
					<nuxt-link to="/dashboard/saved/trade-partners">Trade partners</nuxt-link>
				</li>
			</ul>
			<nuxt-link to="/dashboard/settings">
				<i class="icon-settings"></i>
				Settings
			</nuxt-link>
			<ul>
				<li>
					<nuxt-link to="/dashboard/settings">Membership & credits</nuxt-link>
				</li>
				<li>
					<a href="javascript:" data-tooltip="Coming Soon!">Billing</a>
				</li>
				<li>
					<nuxt-link to="/dashboard/settings/edit-profile">Edit my profile</nuxt-link>
				</li>
				<li>
					<nuxt-link to="/dashboard/settings/account-settings">Account Settings</nuxt-link>
				</li>
			</ul>
		</nav>
		<nuxt-link to="/create-listing" class="btn btn__primary">Create a new listing</nuxt-link>
	</aside>
</template>

<script>
import userImg from "~/assets/img/dashboard/user-dashboard.jpg";
import { mapGetters } from "vuex";

export default {
	computed: {
		...mapGetters("auth", ["isAuthenticated", "loggedInUser"]),
		...mapGetters("messages", ["getConversations"])
	},
	data() {
		return {
			userImg,
			usedCredits: this.$auth.user.credits - 3,
			totalCredits: 3
		};
	}
};
</script>

<style lang="scss" scoped>
@import "~assets/scss/abstracts/_abstracts.scss";

.dashboard_aside {
	min-height: calc(100vh - 81px);

	nav {
		a {
			font-size: 12px;
			line-height: 16px;
			text-transform: uppercase;
			transition: 0.2s $bezier_ease_in_out;
			position: relative;
		}

		.badge {
			border-radius: 50%;
			font-size: 10px;
			position: absolute;
			top: 50%;
			transform: translateY(-50%);
			right: 23px;
			padding: 0 5px;
		}

		> a {
			display: block;
			border-bottom: 1px solid rgba($gray_iron, 0.67);
			padding: 25px 16px 26px 23px;
			color: $gray_aluminium;
			position: relative;

			&:hover {
				color: $gray_tuna;
			}

			&:before {
				content: "";
				position: absolute;
				left: 0;
				top: 0;
				width: 0;
				height: 100%;
				background: transparent;
				transition: 0.2s $bezier_ease_in_out;
			}

			&:first-child {
				border-top: 1px solid rgba($gray_iron, 0.67);
			}

			&.nuxt-link-active {
				color: $gray_tuna;

				&:before {
					width: 12px;
					background: $blue_picton;
				}

				& + {
					ul {
						display: block;
					}
				}
			}

			i {
				margin-right: 5px;
			}
		}

		> ul {
			display: none;
			background: rgba($gray_alto, 0.35);
			padding: 33px 18px 29px 29px;
			box-shadow: inset 0 -10px 25px -10px rgba(0, 0, 0, 0.3);

			li {
				margin-bottom: 14px;

				&:last-child {
					margin-bottom: 0;
				}
			}

			a {
				display: block;
				color: $gray_aluminium;
				letter-spacing: 0.5px;

				&.nuxt-link-exact-active {
					color: $blue_scooter;
				}

				&:hover {
					color: $blue_scooter;
				}

				&.link_offers_details,
				&.link_offers_offers {
					visibility: hidden;
					width: 0;
					height: 0;
					overflow: hidden;
				}

				&.link_offers_details {
					&.nuxt-link-exact-active {
						& + a + a {
							color: $blue_scooter;
						}
					}
				}

				&.link_offers_offers {
					&.nuxt-link-exact-active {
						& + a {
							color: $blue_scooter;
						}
					}
				}
			}
		}
	}
}
</style>
