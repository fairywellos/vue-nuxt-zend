<template>
	<div class="form_search">
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
					v-on:keyup.enter="returnKeySearch()"
				>
			</div>
			<div class="input_icon_wrap">
				<label class="icon-location"></label>
				<input
					type="text"
					autocomplete="off"
					placeholder="City, Zip code"
					@click="openDropdown($event.target)"
					v-model="locationText"
					@input="updateVal($event.target)"
				>
				<div class="location_dropdown" v-on:click.stop="closeEvent()">
					<div class="current_location" @click="getGeolocation()">
						<i class="icon-location"></i>
						<span v-if="browserLocation.set">{{browserLocation.location.name + "," + browserLocation.location.abbreviation }}</span>
						<span v-else>Current location</span>
					</div>
					<div class="recent_searched" v-if="recentSearched.length > 0">
						<div class="actions">
							<p>Recent</p>
							<button @click="removeRecents($event.target)" type="button" role="button" class="btn">
								Clear
								all
							</button>
						</div>
						<ul role="list">
							<li v-for="item in recentSearched" :key="item.id">
								<label>
									<input
										type="radio"
										name="cityState"
										:value="item.name + ', ' + item.abbreviation"
										v-model="locationText"
									>
									{{ item.name }},
									<span class="state">{{ item.abbreviation }}</span>
								</label>
							</li>
						</ul>
					</div>
					<ul v-if="states.length" role="list">
						<li v-for="item in states.slice(0, 5)" :key="item.id">
							<label>
								<input
									type="radio"
									name="cityState"
									:value="item.name + ', ' + item.abbreviation"
									@click="location = item.id"
									v-model="locationText"
								>
								{{ item.name }},
								<span class="state">{{ item.abbreviation }}</span>
							</label>
						</li>
					</ul>
					<p v-else>
						<label>No data found</label>
					</p>
				</div>
			</div>
		</div>
			<nuxt-link
					@click="validateSearch($event)"
					:to="{path: '/search', query: {...this.$route.query, q: term,location:location}}"
					class="btn btn__primary btn__search"
					title="Search"
				>Search
			</nuxt-link>
	</div>
</template>

<script>
	import Multiselect from "vue-multiselect";
	import searchBar from "~/assets/js/searchBar.js";

	export default {
		props: ["is_home"],
		components: {
			Multiselect
		},
		extends: searchBar,
		data() {
			return {
				browserLocation:
					{
						set: false,
						latitude: "",
						longitude: "",
						location: false
					},
				recentSearched: [],
				locationText: this.$store.dispatch("apiCalls/getLocationText",this.$route.query).then((response) =>{
					this.locationText = response;
				}),
				states: [],
				term: this.$route.query.q || "",
				location: this.$route.query.location ||  "",
			};
		},
		mounted() {
			this.closeEvent();
		}
	};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style lang="scss">
	@import "~assets/scss/abstracts/_abstracts.scss";

	.form_search {
		.multiselect,
		.multiselect__tags {
			height: 41px;
			min-height: 41px;
			margin-left: -1px;
		}

		.multiselect__input,
		.multiselect__single {
			line-height: 41px;
		}

		.multiselect__tags {
			border-top-left-radius: 0;
			border-bottom-left-radius: 0;
			border-top-right-radius: 30px;
			border-bottom-right-radius: 30px;
			border-color: #e6e5e5;
		}

		.multiselect__option {
			min-height: 20px;
			padding: 7px 20px;

			&:after {
				font-size: 11px;
				line-height: 30px;
			}
		}

		.multiselect {
			::-webkit-input-placeholder {
				color: rgba($gray_aluminium, 0.5);
				@include source_sans_pro(400);
				font-size: 12px;
			}

			::-moz-placeholder {
				color: rgba($gray_aluminium, 0.5);
				@include source_sans_pro(400);
				font-size: 12px;
			}

			:-ms-input-placeholder {
				color: rgba($gray_aluminium, 0.5);
				@include source_sans_pro(400);
				font-size: 12px;
			}

			:-moz-placeholder {
				color: rgba($gray_aluminium, 0.5);
				@include source_sans_pro(400);
				font-size: 12px;
			}

			input {
				background: transparent;
			}

			&.multiselect--active {
				.multiselect__tags {
					border-top-left-radius: 0;
					border-bottom-left-radius: 0;
					border-top-right-radius: 30px;
					border-bottom-right-radius: 30px;
					border-color: #e6e5e5;
				}
			}

			.multiselect__select {
				display: none;
			}

			.multiselect__single {
				@include source_sans_pro(400);
			}

			.multiselect__tags {
				padding-right: 10px;
				padding-left: 40px;
			}

			.multiselect__content-wrapper {
				top: calc(100% + 55px);
				width: 297px !important;
				left: 0;
				max-height: 158px !important;
				overflow: hidden;
				padding: 10px 0;
			}

			.multiselect__placeholder {
				@include source_sans_pro(400);
				font-size: 12px;
				color: $gray_aluminium;
				padding-top: 14px;
			}
		}
	}
</style>

<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";

	.form_search {
		display: flex;
		justify-content: center;
		align-items: center;
		position: relative;

		.form_group {
			margin-right: 14px;
			display: flex;
			align-items: center;
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
			width: 430px;

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
				border-top-right-radius: 0;
				border-bottom-right-radius: 0;
				border-top-left-radius: 30px;
				border-bottom-left-radius: 30px;
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

			& + .input_icon_wrap {
				width: 171px;

				> label {
					font-size: 18px;
				}

				input {
					border-left: 0;
					border-top-right-radius: 30px;
					border-bottom-right-radius: 30px;
					border-top-left-radius: 0;
					border-bottom-left-radius: 0;
					font-size: 12px;
				}
			}
		}

		.btn__primary {
			padding: 12.5px 29px;

			&:hover {
				box-shadow: 0 10px 20px 0 rgba($black, 0.2);
			}

			&[disabled],
			&:disabled {
				background: $blue_picton;
				color: $white;
				opacity: 1;
				border: none;
			}
		}
	}

	.multiselect {
		& + .current_location {
			position: absolute;
			top: calc(100% + 3px);
			left: -1px;
			background: $white;
			border-top-left-radius: 3px;
			border-top-right-radius: 3px;
			border: 1px solid #e8e8e8;
			width: 297px !important;
			opacity: 0;
			z-index: -5;
			transition: 0.1s;
		}
	}

	.multiselect--active {
		& + .current_location {
			opacity: 1;
			visibility: visible;
			z-index: 5;
		}
	}



	.recent_searched {
		visibility: hidden;
		opacity: 0;
		height: 0;
		overflow: hidden;
		position: absolute;
		transition: 0.2s $bezier_ease_in_out;

		.actions {
			width: 100%;
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 17px 20px 5px;
		}

		p,
		.btn {
			font-size: 10px;
		}

		p {
			color: $gray_aluminium;
			text-transform: uppercase;
		}

		.btn {
			color: $blue_scooter;
		}
	}

	.current_location,
	li {
		cursor: pointer;
	}
</style>
