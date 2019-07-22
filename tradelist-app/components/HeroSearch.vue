<template>
	<div class="hero_search">
		<div class="container">
			<div class="hero_search__inner">
				<p>Buy, Sell, Trade</p>
				<no-ssr>
					<div class="main_logo">
						<img src="~/assets/img/logo-svg.svg" alt="tradelist logo"><i>Tradelist</i>
					</div>
					<SearchBar/>
				</no-ssr>

				<nuxt-link to="/browse-local" class="go_to_link" title="Browse local">Browse local</nuxt-link>
			</div>
			<a
				href="#firstScroll"
				class="next_section"
				@click="smoothScrollTo()"
				title="Scroll to next section"
			></a>
		</div>
	</div>
</template>

<script>
import SearchBar from "~/components/SearchBar.vue";

export default {
	head: {
		script: [
			{
				src:
					"https://cdn.jsdelivr.net/gh/cferdinandi/smooth-scroll/dist/smooth-scroll.polyfills.min.js",
				body: true
			}
		]
	},
	components: {
		SearchBar
	},
	methods: {
		smoothScrollTo: function() {
			let scroll = new SmoothScroll(".next_section", {
				speed: 300,
				offset: 111,
				easing: "linear",
				updateURL: false
			});
		}
	}
};
</script>

<style lang="scss" scoped>
@import "~assets/scss/abstracts/_abstracts.scss";

.hero_search {
	background: $white;
	margin-bottom: 48px;
	position: relative;

	.next_section {
		position: absolute;
		bottom: 13px;
		left: 50%;
		transform: translateX(-50%);
		height: 47px;
		width: 55px;

		&:before,
		&:after {
			content: "";
			position: absolute;
			display: block;
			background: #f1f1f2;
			border-radius: 3px;
			height: 100%;
			width: 7px;
			top: -5%;
		}

		&:before {
			left: 70%;
			transform: rotate(44deg);
		}

		&:after {
			right: 70%;
			transform: rotate(-44deg);
		}
	}
}

.hero_search__inner {
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	height: calc(100vh - 81px);
	margin-bottom: 20px;

	p {
		font-size: 15px;
		line-height: 20px;
		color: $gray_aluminium;
		margin-bottom: 10px;
	}

	.main_logo {
		margin-bottom: 27px;
	}

	.form_search {
		margin-bottom: 16px;
	}
}

.go_to_link {
	font-size: 14px;
	text-align: center;
	color: $blue_picton;
	transition: 0.2s $bezier_ease_in;
	margin-bottom: 50px;

	&:hover {
		color: darken($blue_picton, 15%);
	}
}
</style>
