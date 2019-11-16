<template>
	<div class="browse_local_page">
		<Header v-if="$device.isDesktop"/>
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<HeaderTags path="browse-local"/>
		
		<div class="page_section">
			<div class="container">
				<h2 v-if="isAuthenticated">
					What can we help you find, {{ loggedInUser.publicName }}?
				</h2>
				<h2 v-else>
					What can we help you find ?
				</h2>
				<ul class="categories_list" role="list">
					<li v-for="(mainCat, key) in optionsMainCats" :key="key">
						<input
							type="checkbox"
							v-bind:id="'main_cat_u' + mainCat.id"
							v-bind:value="mainCat.id"
							name="main_category"
							v-model="mainCatsSelectedBL"
							@change="applyCategory($event)"
						>
						<label v-bind:for="'main_cat_u'+mainCat.id" :style="{borderColor: mainCat.colorCode}">
							<span class="checkbox"></span>
							<img v-bind:src="require('~/assets/img/illustrations/' + mainCat.icon)"
							     v-bind:alt="mainCat.name">
							<p :style="{color: mainCat.colorCode}">{{mainCat.name}}</p>
						</label>
					</li>
					<!--					<li>-->
					<!--						<a href="#" class="other">-->
					<!--							<img src="~/assets/img/illustrations/other.svg" alt="other">-->
					<!--							<p>-->
					<!--								Other-->
					<!--							</p>-->
					<!--						</a>-->
					<!--					</li>-->
				</ul>
			</div>
		</div>
		
		<div class="page_section">
			<div class="container">
				<h2>
					<span v-if="location">Popular near {{ location.city + ", " + location.state }}</span>

					<!--								<nuxt-link class="float_right" to="" title="See all">See all</nuxt-link>-->
				</h2>
				<div class="cards_grid">
					<template v-for="(listing, i) in allProducts">
						<div :key="`${i}-infeed-mobile`" v-if="$device.isMobileOrTablet && i==4">
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
						<div :key="`${i}-infeed`" v-if="$device.isDesktop && i == 8">
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
						<div :key="`${i}-banner`" v-if="i==allProducts.length-8">
							<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
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
						<CardItem
							:key="`${i}-${listing.id}`"
							:id="listing.id"
							:listing="listing"
						/>
					</template>
					<!-- <CardItem v-for="(listing, i) in allProducts" :key="`${i}-${listing.id}`" :id="listing.id"
					          :listing="listing"/> -->
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import Header from '~/components/Header.vue';
	import HeaderMobile from '~/components/HeaderMobile.vue';
	import HeaderTags from '~/components/HeaderTags.vue';
	import CardItem from '~/components/CardItem.vue';
	import ListingConfig from "~/mixins/listingConfig.js";
	import heroImg from "~/assets/img/browse-local/browse-local.jpg";
	import {
		mapGetters
	} from 'vuex';
	import apiCalls from "../../store/modules/apiCalls";
	
	export default {
		// mixins: [ListingConfig],
		components: {
			Header,
			HeaderMobile,
			HeaderTags,
			CardItem
		},
		head() {
			return {
				title: 'Browse Local',
				meta: [{
					hid: 'description',
					name: 'description',
					content: 'Browse local description'
				}]
			}
		},
		data() {
			return {
				heroImg,
				mainCatsSelectedBL:
					this.$route.query.category !== undefined
						? this.$route.query.category.split(",")
						: [],
				optionsMainCats: this.$store.getters["mainCategories/getCategories"]
			}
		},
		methods: {
			applyCategory: function (event) {
				event.stopPropagation();
				let self = this;
				if(this.mainCatsSelectedBL.length > 0){
					self.$router.push({
						path: this.path,
						query: {
							...self.$route.query,
							category: self.mainCatsSelectedBL.join(",")
						}
					});
						// self.refreshPage();
				}else {
					self.resetUrl("category",false);
						// self.refreshPage();
				}
			},
			findCategory(id) {
				return this.$store.getters['mainCategories/getCategory'](id);
			},
			resetUrl(element, secondEl) {
				let query = {...this.$route.query};
				delete query[element];
				if (secondEl !== false) {
					delete query[secondEl];
				}
				this.$router.push({
					path: this.path,
					query: query
				});
			}
		},
		watchQuery: true,
		async asyncData({
			                store,
			                query
		                }) {
			let data = await store.dispatch("apiCalls/getBrowseLocal", query);
			return data;
		},
		mounted() {
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition((data) => {
						this.$axios.post("/browse-local", {
							lat: data.coords.latitude,
							long: data.coords.longitude,
						}).then((result) => {
							this.location = result.data.result;
							
							let query = {
								...this.$route.query
							};
							query['location'] = result.data.result.id;
							
							this.$axios.get("/browse-local/all", {
								params: query
							}).then((result => {
								this.allProducts = result.data.result;
							}));
						});
					},
					(error) => {
						if (error.code == error.PERMISSION_DENIED)
							console.log("You need to activate your location in order for this page to work");
					});
				
			} else {
				console.log("Geolocation is not supported by this browser.");
			}
		},
		computed: {
			...mapGetters('auth', ['isAuthenticated', 'loggedInUser'])
		}
	}
</script>

<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";
	
	.is_mobile {
		.hero_image {
			margin-bottom: 40px;
			padding: 40px 15px;
			min-height: auto;

			h1 {
				font-size: 40px;
			}
			
			.img_overlay {
				img {
					width: 70px;
				}
			}
		}
	}
	
	.browse_local_page {
		padding-top: 51px;
		
		.main_header {
			box-shadow: none;
			border-bottom: none;
		}
	}
	
	.hero_image {
		position: relative;
		max-width: 1600px;
		min-height: 549px;
		margin: 0 auto 61px;
		padding: 25px 15px;
		display: flex;
		align-items: center;
		justify-content: center;
		background-size: cover;
		background-position: center;
		background-repeat: no-repeat;
		
		@media only screen and (max-width: 992px) {
			min-height: 350px;
		}
		
		.img_overlay {
			
			
			img {
				width: 120px;
				margin: 0 auto 5px;
				display: block;
			}
		}
		
		h1 {
			font-size: 81px;
			font-weight: 700;
			letter-spacing: -2.71px;
			text-align: center;
			color: $gray_tuna;
			
			@media only screen and (max-width: 992px) {
				font-size: 50px;
			}
		}
		
		i {
			display: block;
			width: 190px;
			margin: 0 auto;
			font-size: 23px;
		}
	}
	
	.categories_list {
		li {
			input {
				position: absolute;
				opacity: 0;
				z-index: -1;
				visibility: hidden;
			}
		}
		
		img {
			max-width: 45px;
			max-height: 45px;
		}
		
		p {
			width: 100%;
		}
		
		label {
			padding: 10px 12px;
			display: flex;
			flex-wrap: wrap;
			align-items: center;
			justify-content: center;
			height: 100%;
			width: 100%;
			cursor: pointer;
			position: relative;
			border: 2px solid;
			
			.checkbox {
				width: 20px;
				height: 20px;
				border-radius: 5px;
				position: absolute;
				top: 10px;
				left: 10px;
				-webkit-box-shadow: 0 1px 5px rgba(0, 0, 0, .5), 0 1px 5px rgba(0, 0, 0, .5);
				box-shadow: 0 1px 5px rgba(0, 0, 0, .5), 0 1px 5px rgba(0, 0, 0, .5);
				opacity: 0;
				-webkit-transition: 0.2s cubic-bezier(0.42, 0, 0.58, 1);
				transition: 0.2s cubic-bezier(0.42, 0, 0.58, 1);
				
				&:after {
					content: "";
					display: block;
					position: absolute;
					top: 3px;
					left: 7px;
					width: 4px;
					height: 10px;
					border: solid #AAAEB3;
					border-width: 0 2px 2px 0;
					-webkit-transform: rotate(45deg);
					transform: rotate(45deg);
				}
			}
		}
		
		input[type=checkbox] {
			&:checked {
				& + label {
					.checkbox {
						opacity: 1;
					}
				}
			}
		}
	}
</style>
