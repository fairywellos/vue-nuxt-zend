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
			<template v-if="alterListings">
				<transition-group name="slide-fade" tag="div" class="cards_grid" v-if="alterListings.length > 0">
					<template v-for="(listing, i) in alterListings">
						<div class="banner-ads" :key="`${i}-banner`" v-if="i == listings.length - 8 - listings.length % 4 - (listings.length >= 20)">
							<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
							<!-- Desktop Pagination -->
							<ins class="adsbygoogle"
							style="display:block"
							data-ad-client="ca-pub-1709497292936218"
							data-ad-slot="3534567470"
							data-ad-format="auto"
							data-full-width-responsive="true"></ins>
							<script>
							(adsbygoogle = window.adsbygoogle || []).push({});
							</script>
						</div>
						<div class="card_item" :key="`${i}-infeed`" v-if="i == 8 && $device.isDesktop">
							<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
							<ins class="adsbygoogle"
							style="display:block"
							data-ad-format="fluid"
							data-ad-layout-key="-68+dk-2l-6f+ws"
							data-ad-client="ca-pub-1709497292936218"
							data-ad-slot="5223069572"></ins>
							<script>
							(adsbygoogle = window.adsbygoogle || []).push({});
							</script>
						</div>
						<div class="card_item" :key="`${i}-infeed`" v-if="i == 4 && $device.isMobileOrTablet">
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
						</div>
						
						<CardItem
							:key="`${i}-${listing.id}`"
							:id="listing.id"
							:listing="listing"
						/>
					</template>
					<div class="card_item" :key="`infeed`" v-if="alterListings.length <= 8 && $device.isDesktop">
						<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
						<ins class="adsbygoogle"
						style="display:block"
						data-ad-format="fluid"
						data-ad-layout-key="-68+dk-2l-6f+ws"
						data-ad-client="ca-pub-1709497292936218"
						data-ad-slot="5223069572"></ins>
						<script>
						(adsbygoogle = window.adsbygoogle || []).push({});
						</script>
					</div>
					<div style="width:100%" :key="`banner`" v-if="alterListings.length <= 8">
						<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
						<!-- Desktop Pagination -->
						<ins class="adsbygoogle"
						style="display:block"
						data-ad-client="ca-pub-1709497292936218"
						data-ad-slot="3534567470"
						data-ad-format="auto"
						data-full-width-responsive="true"></ins>
						<script>
						(adsbygoogle = window.adsbygoogle || []).push({});
						</script>
					</div>
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
	import image1 from "~/assets/img/card-item/Image.png";
	import image2 from "~/assets/img/card-item/Image2.png";
	import image3 from "~/assets/img/card-item/Image3.png";
	import image4 from "~/assets/img/card-item/Image4.png";
	import ownerImage from "~/assets/img/card-item/user_image.png";
	import ListingItems from '~/pages/search/index.vue';

	const response = {
		allProducts: [
			{
				id: "0",
				name: "Hans J. Wegner Danish Teak Chil...",
				productImage: image1,
				category: "household",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "homes_housing",
				price: "1,465",
				transaction: ["trade", "cash"],
				location: "Albuquerque, NM",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},
			{
				id: "1",
				name: "MacBook Pro 13 2012 *Grade A...",
				productImage: image2,
				category: "electronics",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "electronics",
				price: "2,465",
				transaction: ["trade", "cash"],
				location: "Fremont, WA",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},
			{
				id: "2",
				name: "1-week Surf lessons in Maui",
				productImage: image3,
				category: "Experiences",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "experiences",
				price: "465",
				transaction: ["cash"],
				location: "Downtown Seattle, WA",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},
			{
				id: "3",
				name: "One month co-working space",
				productImage: image4,
				category: "Business & Industrial",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "business_industrial",
				price: "1,000",
				transaction: ["cash", "trade"],
				location: "Ballard, WA",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},
			{
				id: "4",
				name: "Hans J. Wegner Danish Teak Chil...",
				productImage: image1,
				category: "Household",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "homes_housing",
				price: "99",
				transaction: ["trade"],
				location: "Ballard, WA",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},
			{
				id: "5",
				name: "MacBook Pro 13 2012 *Grade A...",
				productImage: image2,
				category: "Electronics",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "electronics",
				price: "70",
				transaction: ["trade", "cash"],
				location: "Normandy Park, WA",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},
			{
				id: "6",
				name: "1-week Surf lessons in Maui",
				productImage: image3,
				category: "Experiences",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "experiences",
				price: "999",
				transaction: ["trade", "cash"],
				location: "Fremont, WA",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},
			{
				id: "7",
				name: "One month co-working space",
				productImage: image4,
				category: "Business & Industrial",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "business_industrial",
				price: "800",
				transaction: ["trade", "cash"],
				location: "Ballard, WA",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},
			{
				id: "8",
				name: "One month co-working space",
				productImage: image4,
				category: "Business & Industrial",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "business_industrial",
				price: "800",
				transaction: ["trade", "cash"],
				location: "Ballard, WA",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},
			{
				id: "9",
				name: "One month co-working space",
				productImage: image4,
				category: "Business & Industrial",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "business_industrial",
				price: "800",
				transaction: ["trade", "cash"],
				location: "Ballard, WA",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},
			{
				id: "10",
				name: "One month co-working space",
				productImage: image4,
				category: "Business & Industrial",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "business_industrial",
				price: "800",
				transaction: ["trade", "cash"],
				location: "Ballard, WA",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},
			{
				id: "11",
				name: "One month co-working space",
				productImage: image4,
				category: "Business & Industrial",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "business_industrial",
				price: "800",
				transaction: ["trade", "cash"],
				location: "Ballard, WA",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},
			{
				id: "12",
				name: "One month co-working space",
				productImage: image4,
				category: "Business & Industrial",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "business_industrial",
				price: "800",
				transaction: ["trade", "cash"],
				location: "Ballard, WA",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},
			{
				id: "13",
				name: "One month co-working space",
				productImage: image4,
				category: "Business & Industrial",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "business_industrial",
				price: "800",
				transaction: ["trade", "cash"],
				location: "Ballard, WA",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},{
				id: "14",
				name: "Hans J. Wegner Danish Teak Chil...",
				productImage: image1,
				category: "household",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "homes_housing",
				price: "1,465",
				transaction: ["trade", "cash"],
				location: "Albuquerque, NM",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},
			{
				id: "15",
				name: "MacBook Pro 13 2012 *Grade A...",
				productImage: image2,
				category: "electronics",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "electronics",
				price: "2,465",
				transaction: ["trade", "cash"],
				location: "Fremont, WA",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},
			{
				id: "16",
				name: "1-week Surf lessons in Maui",
				productImage: image3,
				category: "Experiences",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "experiences",
				price: "465",
				transaction: ["cash"],
				location: "Downtown Seattle, WA",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},
			{
				id: "17",
				name: "One month co-working space",
				productImage: image4,
				category: "Business & Industrial",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "business_industrial",
				price: "1,000",
				transaction: ["cash", "trade"],
				location: "Ballard, WA",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},
			{
				id: "18",
				name: "Hans J. Wegner Danish Teak Chil...",
				productImage: image1,
				category: "Household",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "homes_housing",
				price: "99",
				transaction: ["trade"],
				location: "Ballard, WA",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},
			{
				id: "19",
				name: "MacBook Pro 13 2012 *Grade A...",
				productImage: image2,
				category: "Electronics",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "electronics",
				price: "70",
				transaction: ["trade", "cash"],
				location: "Normandy Park, WA",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},
			{
				id: "20",
				name: "1-week Surf lessons in Maui",
				productImage: image3,
				category: "Experiences",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "experiences",
				price: "999",
				transaction: ["trade", "cash"],
				location: "Fremont, WA",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},
			{
				id: "21",
				name: "One month co-working space",
				productImage: image4,
				category: "Business & Industrial",
				owner: "Merle Jørgensen",
				defaultClass: "card_item__cat",
				category_slug: "business_industrial",
				price: "800",
				transaction: ["trade", "cash"],
				location: "Ballard, WA",
				ownerThumbnail: ownerImage,
				lookingFor: [
					{
						icon: "sporting-goods",
						class: "sporting_goods",
						service: "Landscaping services"
					},
					{
						icon: "homes-housing",
						class: "homes_housing",
						service: "Santa Cruz Mountain Bikes"
					},
					{
						icon: "experiences",
						class: "experiences",
						service: "Tickets to Hamilton in Hood River, OR"
					}
				]
			},
			
		]
	};
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
			// ...mapGetters({
            //     listings : 'searchResults/listings',
			// }),
			listings(){
				return response.allProducts.slice(0,5);
			},
			alterListings(){
				if(this.listings >= 40){
					return this.listings.slice(0, this.listings.length -1)
				}
				return this.listings
			},
			localQuery(){
				if(this.is_home == true){
					return { q: "", location: "" }
				} else {
					return this.$route.query
				}
			},
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

	.banner-ads {
		margin-bottom: 14px;
		width:100%;
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
