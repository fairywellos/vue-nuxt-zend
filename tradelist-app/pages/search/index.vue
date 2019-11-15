<template>
	<div class="search_page">		
		<Header :is_home="is_home && $device.isDesktop" v-if="$device.isDesktop"/>
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<template v-if="!is_home">
		<HeaderTags
			path="search"
		/>
		</template>
		<div class="container">
			<template v-if="$device.isMobileOrTablet">
				<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<ins class="adsbygoogle"
				style="display:block"
				data-ad-format="fluid"
				data-ad-layout-key="-68+dk-2l-6f+ws"
				data-ad-client="ca-pub-1709497292936218"
				data-ad-slot="8375491307"></ins>
				<script>
				(adsbygoogle = window.adsbygoogle || []).push({});
				</script>
			</template>
			<template v-if="$device.isDesktop">
				<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<ins class="adsbygoogle"
				style="display:block"
				data-ad-format="fluid"
				data-ad-layout-key="-68+dk-2l-6f+ws"
				data-ad-client="ca-pub-1709497292936218"
				data-ad-slot="5223069572"></ins>

				<ins class="adsbygoogle"
				style="display:block"
				data-ad-client="ca-pub-1709497292936218"
				data-ad-slot="3534567470"
				data-ad-format="auto"
				data-full-width-responsive="true"></ins>

				<script>
				(adsbygoogle = window.adsbygoogle || []).push({});
				</script>
			</template>
			<template v-if="!is_home">
			<div class="results_hero">
				<div class="results_hero__inner">
					<p>
						<strong>"{{ $route.query.q }}"</strong>
						results
						<label class="save_search" v-if="isAuthenticated">
							<input type="checkbox" name="save-search" v-model="search_saved">
							<span class="icon-save"></span> <i>Save this search</i><i>Saved</i>
						</label>
					</p>
				</div>
			</div>
			</template>
			<template v-if="listings">
			<transition-group name="slide-fade" tag="div" class="cards_grid" v-if="listings.length > 0">
				<CardItem
					v-for="(listing, i) in listings"
					:key="`${i}-${listing.id}`"
					:id="listing.id"
					:listing="listing"
				/>
			</transition-group>
			</template>
			<template v-else-if="!is_home">
			<transition name="fade">
				<h3>No matching results for "{{ $route.query.q }}"</h3>
			</transition>
			</template>
		</div>
	</div>
</template>

<script>
	import Header from "~/components/Header";
	import HeaderMobile from "~/components/HeaderMobile";
	import HeaderTags from "~/components/HeaderTags";
	import CardItem from "~/components/CardItem";
	import { mapGetters } from "vuex";

	export default {
		props: ['is_home'],
		components: {
			Header,
			HeaderMobile,
			HeaderTags,
			CardItem
		},
		async fetch({params, store, route}) {
			console.log("Route Query", route.query)
			if (route.query) {
				await store.dispatch('searchResults/updateSearchResultAsync', route.query);
			} else {
				await store.dispatch('searchResults/updateSearchResultAsync', {q: "", location: ""});
			}
		},
		watchQuery: true, //Refresh la pagina atunci cand modificam query stringul din url.
		data() {
			if (this.is_home == true) return {}
			return {
				...this.$store.getters['searchResults/getByQuery'](this.localQuery),
				...{search_saved: this.$store.getters['searchQueriesSaved/getByQuery'](this.localQuery) !== undefined}
			};
		},
		async asyncData({store}) {
			let queries = await store.getters['searchQueriesSaved/getQueries'];
			return {queries: queries}
		},
		watch: {
			search_saved: function (saved) {
				this.$store.dispatch('searchQueriesSaved/setSaved', {query: this.localQuery, status: saved});
			},
		},
		computed: {
			...mapGetters("auth", ["isAuthenticated", "loggedInUser"]),
			...mapGetters({
                listings : 'searchResults/listings',
            }),
			localQuery(){
				if(this.is_home == true){
					return { q: "", location: "" }
				} else {
					return this.$route.query
				}
			}
		},
		mounted(){
			if(this.is_home == true){
				this.$store.dispatch('searchResults/updateSearchResultAsync', {q: "", location: ""});
			}
			var vm = this;
			console.log("Inspecting Search Component", this);
			window.addEventListener('scroll', function(event)
			{
				if(window.isNewPageLoading == true) return;
				var element = event.target.scrollingElement;
				if (element.scrollHeight - element.scrollTop < element.clientHeight + 200)
				{
					console.log("next page");
					window.isNewPageLoading = true;
					let query = vm.$route.query;
					let curPage = query.page || 1
					query.page = curPage + 1;
					vm.$store.dispatch('searchResults/updateSearchResultNewPageAsync', query);
				}
			});
		}
	};
</script>

<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";

	.search_page {
		.main_header {
			border-bottom: transparent;
		}

		.results_hero {
			margin-top: 52px;
		}
	}

	.save_search {
		position: relative;
		display: inline-block;
		margin-left: 15px;
		cursor: pointer;
		padding-left: 30px;
		color: $blue_picton;
		font-size: 10px;
		line-height: 14px;
		transition: .2s $bezier_ease_in_out;

		input[type=checkbox] {
			position: absolute;
			z-index: -1;
			opacity: 0;
			visibility: hidden;

			&:checked + .icon-save {
				color: $saffron;

				& + i {
					display: none;

					& + i {
						display: inline-block;
						color: $saffron;
					}
				}
			}
		}

		i {
			font-style: normal;
			transition: .2s $bezier_ease_in_out;

			& + i {
				display: none;
			}
		}

		.icon-save {
			position: absolute;
			top: 39%;
			left: 0;
			transform: translateY(-50%);
			font-size: 20px;
			color: $blue_picton;
			margin-right: 5px;
			transition: .2s $bezier_ease_in_out;
		}
	}

	.is_mobile {
		.search_page {
			padding-top: 24px;

			.mh_mobile {
				box-shadow: none;
			}

			.results_hero {
				margin-top: 0;
				padding: 27px 0 14px;
				margin-bottom: 14px;
			}

			.results_hero__inner {
				p {
					font-size: 10px;
					letter-spacing: 0.7px;
				}
			}
		}

		.save_search {
			.icon-save {
				font-size: 19px;
			}
		}

		.cards_grid {
			.card_item {
				margin-bottom: 7px;
			}
		}

		.main_visible {
			& + .header_tags {
				/*z-index: 9;*/
			}
		}
	}

	.cards_grid {
		.card_item {
			width: calc(25% - 21px);

			@media only screen and (max-width: 1260px) {
				width: calc(33.33333% - 10px);
			}

			@media only screen and (max-width: 968px) {
				width: calc(50% - 10px);
			}

			@media only screen and (max-width: 576px) {
				width: 100%;
			}
		}
	}
</style>
