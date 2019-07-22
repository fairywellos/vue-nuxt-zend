<template>
	<div class="trader_card">
		<h3 v-if="privacy == 1">{{ name }}</h3>
		<h3 v-else > {{username}} </h3>
		<p>{{ location }}</p>
		<div class="thumbnail" :style="{ backgroundImage: 'url('+ thumbnail + ')' }"></div>
		<ul role="list">
			<li>
				<p>
					{{ trades }}
					<span class="badge" v-if="badge">New</span>
					<small>Trades</small>
				</p>
			</li>
			<li>
				<p>
					{{ followersCard }}
					<small>Followers</small>
				</p>
			</li>
			<li>
				<p>
					<span v-if="rating">{{ rating }}</span>
					<span v-else>N/A</span>
					<small>Rating</small>
				</p>
			</li>
		</ul>
		<div class="button_group">
			<nuxt-link :to="'/view-profile/' + id " class="btn btn__primary" role="button" title="View items">View items</nuxt-link>
			<button v-if="followedCard == 1" type="button" class="btn btn__secondary" style="background-color: limegreen; color: white" role="button" aria-label="Following" @click="unfollow()">Unfollow</button>
			<button v-else="followedCard == 0" type="button" class="btn btn__secondary" role="button" aria-label="Following" @click="follow()">Follow</button>
		</div>
	</div>
</template>

<script>
export default {
	props: [
		"id",
		"name",
		"followed",
		"location",
		"privacy",
		"username",
		"thumbnail",
		"trades",
		"followers",
		"rating",
		"badge"
	],
	data() {
		return {
			followedCard: this.followed,
			followersCard: this.followers,
		}
	},
	methods: {
		follow: function (user) {
			this.$axios.post("/follow/following", {
				user_id: this.id
			}).then( (response) => {
				this.followedCard = 1;
				this.followersCard = parseInt(this.followersCard) + 1;
			});
		},
		unfollow: function (user) {
			this.$axios.post("/follow/unfollow", {
				user_id: this.id
			}).then( (response) => {
				this.followedCard = 0;
				this.followersCard = parseInt(this.followersCard) - 1;
			});
		}
	}
};
</script>

<style lang="scss">
@import "~assets/scss/abstracts/_abstracts.scss";

.trader_grid {
	padding-bottom: 30px;
}

.trader_card {
	background: $white;
	text-align: center;
	padding: 30px 25px;
	min-width: 362px;
	transition: .3s $bezier_ease_in_out;

	&:hover {
		box-shadow: -1px 2px 30px rgba(0, 0, 0, 0.1),
			-1px 2px 30px rgba(0, 0, 0, 0.1);
	}

	@media only screen and (max-width: 1659px) {
		min-width: 335px;
	}

	h3 {
		font-size: 18px;
		line-height: 24px;
		color: $gray_tuna;
		margin-bottom: 10px;

		& + p {
			font-size: 12px;
			line-height: 16px;
			color: $gray_tuna;
			margin-bottom: 30px;
		}
	}

	.thumbnail {
		width: 80px;
		height: 80px;
		border-radius: 50%;
		background-size: cover;
		background-position: center;
		background-repeat: no-repeat;
		margin: 0 auto 22px;
	}

	ul {
		display: flex;
		width: 100%;
		margin-bottom: 11px;
		padding: 0 12px;
	}

	li {
		flex: 1;
		padding: 0 15px;
		position: relative;

		.badge {
			position: absolute;
			right: -12px;
			top: 1px;
		}

		p {
			font-size: 18px;
			line-height: 24px;
			color: $gray_tuna;

			small {
				display: block;
				width: 100%;
				text-transform: uppercase;
				font-size: 10px;
				color: $gray_aluminium;
				font-weight: 700;
				letter-spacing: 2px;
			}
		}
	}

	.button_group {
		display: flex;
		justify-content: space-between;
		align-items: center;

		.btn {
			display: block;
			width: 100%;
		}

		.btn__primary {
			max-width: 196px;

			@media only screen and (max-width: 1659px) {
				max-width: 175px;
			}
		}

		.btn__secondary {
			max-width: 104px;
		}
	}
}
</style>
