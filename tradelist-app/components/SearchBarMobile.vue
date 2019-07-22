<template>
	<div class="form_search" :class="{'is_visible' : searchToggleVisible}">
		<div class="form_group">
			<div class="input_icon_wrap">
				<label class="icon-tag"></label>
				<input
					type="search"
					autocomplete="off"
					placeholder="Eg. vintage watches"
					v-model="term"
					v-validate="'min:2'"
					data-vv-name="search"
					data-vv-as="Search"
					data-vv-scope="mainSearch"
					@focus="openMenuSearch()"
					v-on:keyup.enter="returnKeySearch()"
				>
			</div>
		</div>
		<div class="search_toggle">
			<button type="button" role="menuitem" class="btn__back" @click="closeMenuSearch()">
				<i class="icon-close"></i>
			</button>
			<div class="input_icon_wrap">
				<label class="icon-magnifying-glass"></label>
				<input
					type="search"
					autocomplete="off"
					placeholder='Try "Star Wars collectibles"'
					v-model="term"
					v-validate="'min:2'"
					data-vv-name="search"
					data-vv-as="Search"
					data-vv-scope="mainSearch"
					v-on:keyup.enter="returnKeySearch()"
				>
			</div>
			<p class="title">Recent searches</p>
			<div class="recent_list">
				<nuxt-link to="/search">
					<i class="icon-reload"></i>
					<p>
						"crab pot"
						<small>
							in <strong>Seattle</strong> · <strong>40 miles</strong>, priced <strong>$5</strong> to <strong>$50</strong>
						</small>
					</p>
				</nuxt-link>
				<nuxt-link to="/search">
					<i class="icon-reload"></i>
					<p>
						"crab pot"
						<small>
							in <strong>Seattle</strong> · <strong>40 miles</strong>, priced <strong>$5</strong> to <strong>$50</strong>
						</small>
					</p>
				</nuxt-link>
				<nuxt-link to="/search">
					<i class="icon-reload"></i>
					<p>
						"crab pot"
						<small>
							in <strong>Seattle</strong> · <strong>40 miles</strong>, priced <strong>$5</strong> to <strong>$50</strong>
						</small>
					</p>
				</nuxt-link>
				<nuxt-link to="/search">
					<i class="icon-reload"></i>
					<p>
						"crab pot"
						<small>
							in Seattle · 40 miles, priced $5 to $50
						</small>
					</p>
				</nuxt-link>
				<nuxt-link to="/search">
					<i class="icon-reload"></i>
					<p>
						"crab pot"
						<small>
							in <strong>Seattle</strong> · <strong>40 miles</strong>, priced <strong>$5</strong> to <strong>$50</strong>
						</small>
					</p>
				</nuxt-link>
			</div>
		</div>
	</div>
</template>

<script>
	import searchBar from "~/assets/js/searchBar.js";
	
	export default {
		extends: searchBar,
		data() {
			return {
				city: "",
				browserLocation: [
					{
						set: false,
						latitude: "",
						longitude: ""
					}
				],
				searchToggleVisible: false,
				pickedLocation: this.city,
				statesVal: null,
				term: this.$route.query.q || ""
			};
		},
		methods: {
			sTest() {
				this.$router.push({path: '/search', query: {...this.$route.query, q: this.term}})
			}
		},
		mounted() {
			this.closeEvent();
		}
	};
</script>

<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";
	
	.form_search {
		display: flex;
		justify-content: center;
		align-items: center;
		position: relative;
		
		.form_group {
			display: flex;
			align-items: center;
			width: 100%;
		}
		
		.error_message {
			position: absolute;
			left: 14px;
			bottom: -26px;
			border-radius: 3px;
			display: block;
			padding: 5px;
			z-index: 9;
			transition: 0.3s $bezier_ease_in_out;
		}
		
		.input_icon_wrap {
			position: relative;
			width: 100%;
			
			> label {
				position: absolute;
				top: 50%;
				left: 15px;
				transform: translateY(-50%);
				color: $blue_picton;
				margin: 0;
				z-index: 5;
			}
			
			input {
				padding-left: 40px;
				border-radius: 4px;
				height: 41px;
				display: block;
				font-size: 13px;
				border-color: #e6e5e5;
				color: $gray_aluminium;
				background: transparent;
				@include source_sans_pro(400);
				
				&:focus {
					border-color: #e6e5e5;
				}
			}
			
		}
	}
	
	input {
		&.is-visible {
			& + .location_dropdown {
				height: auto;
				max-height: 230px;
				z-index: 99;
				visibility: visible;
				opacity: 1;
			}
			
			&.is-empty {
				& + .location_dropdown {
					.recent_searched {
						opacity: 1;
						visibility: visible;
						height: auto;
						overflow: visible;
						position: static;
						
						& + ul {
							opacity: 0;
							visibility: hidden;
						}
					}
				}
			}
		}
	}
	
	.current_location,
	li {
		cursor: pointer;
	}
	
	.current_location {
		padding: 5px 20px;
		
		border-bottom: 1px solid #e8e8e8;
		color: $blue_picton;
		font-size: 12px;
		height: 52px;
		display: flex;
		align-items: center;
		
		i {
			font-size: 18px;
			margin-right: 10px;
			position: relative;
			top: -3px;
		}
	}
	
	ul {
		padding: 10px 0;
	}
	
	li {
		padding: 0 20px;
		background: transparent;
		transition: 0.2s $bezier_ease_in_out;
		
		.state {
			text-transform: uppercase;
		}
		
		input[type="radio"] {
			position: absolute;
			opacity: 0;
			visibility: hidden;
			z-index: -9999;
		}
		
		label {
			position: static;
			transform: none;
			display: block;
			width: 100%;
			padding: 2.5px 0;
			font-size: 12px;
			line-height: 23px;
			color: $gray_tuna;
			text-transform: capitalize;
			cursor: pointer;
		}
		
		&:hover {
			background: rgba($gray_aluminium, 0.1);
		}
	}
	
	.search_toggle {
		visibility: hidden;
		position: fixed;
		left: 0;
		right: 0;
		top: 0;
		width: 100%;
		height: 100vh;
		transform: translateY(-100%);
		background: white;
		padding: 50px 19px 19px;
		transition: .25s $bezier_ease_in_out;
		z-index: 1000;
		
		.btn__back {
			position: absolute;
			left: 9px;
			top: 7px;
		}
		
		.title {
			text-transform: uppercase;
			font-size: 10px;
			letter-spacing: 2px;
			color: $gray_aluminium;
			margin-top: 27px;
		}
		.input_icon_wrap {
			position: relative;
			left: -19px;
			padding: 0 19px 12px;
			width: calc(100% + 38px);
			box-shadow: 0 2px 6px rgba($black, 0.1);
			
			> label {
				top: calc(50% - 6px);
				left: 34px;
			}
			
			.icon-magnifying-glass {
				color: $gray_tuna;
			}
		}
	}
	
	.form_search {
		&.is_visible{
			.search_toggle {
				transform: translateY(0%);
				visibility: visible;
				bottom: 0;
				overflow-x: hidden;
				overflow-y: auto;
			}
		}
	}
	
	.recent_list {
		a {
			display: flex;
			align-items: center;
			width: 100%;
			padding: 11px 8px;
			border-bottom: 1px solid $gray_aluminium2;
			
			i {
				font-size: 15px;
				color: $gray_tuna;
			}
			
			p {
				font-size: 14px;
				line-height: 18px;
				color: $gray_tuna;
				padding-left: 17px;
				margin: 0;
				
				small {
					display: block;
					font-size: 12px;
					line-height: 16px;
					color: $gray_aluminium;
					padding-top: 3px;
				}
			}
		}
	}
</style>
