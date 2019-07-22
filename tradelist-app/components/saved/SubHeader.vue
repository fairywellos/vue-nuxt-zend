<template>
	<div class="saved_tabs">
		<div class="container">
			<ul id="nav-tab">
				<li v-bind:class="{active: $route.path === '/find-trade-partners'}">
					<nuxt-link
						:to="{path: '/find-trade-partners'}"
						class="btn btn__primary"
						title="Watch list"
					>Find Trade Partner <div class="badge">New</div></nuxt-link>
				</li>
			</ul>
		</div>
	</div>
</template>

<script>
export default {
	data() {
		return {};
	},
	computed: {
		totalSavedListings() {
			return this.$store.getters["watchList/getTotalResults"] || 0;
		}
	}
};
</script>

<style lang="scss" scoped>
@import "~assets/scss/abstracts/_abstracts.scss";

.saved_tabs {
	background: $white;

	ul {
		display: flex;

		a {
			display: flex;
			text-decoration: none;
			height: 100%;
			align-items: center;
			padding: 30px 10px;
			font-size: 12px;
			letter-spacing: 0.5px;
			color: $gray_aluminium;
			border-bottom: 3px solid transparent;
			background: $white;
			transition: 0.2s $bezier_ease_in_out;
			border-radius: 0;

			&:hover {
				background: $gray_gallery;
			}
		}

		li {
			&.active {
				a {
					color: $gray_tuna;
					border-bottom-color: $blue_scooter;
				}
			}
		}
	}
}
</style>
