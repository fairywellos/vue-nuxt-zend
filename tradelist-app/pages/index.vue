<template>
	<div class="is_homepage">
		<HeaderHomepage v-if="$device.isDesktop"/>
		<HeroSearch v-if="!$device.isMobileOrTablet"/>
		<div v-if="$device.isDesktop">
			<!--<div class="page_section" v-if="!isAuthenticated">-->
				<!--<div class="container">-->
					<!--<h2>Recently added-->
						<!--<nuxt-link class="float_right" to="/see-all" title="See all">See all</nuxt-link>-->
					<!--</h2>-->
					<!--<div class="main_slider">-->
						<!--<div class="card_item_slider">-->
							<!--<div v-for="product in allProducts" :key="product.id">-->
								<!--<div class="card_item">-->
									<!--<div class="save_btn">-->
										<!--<label>-->
											<!--<input type="checkbox" name="state">-->
											<!--<span class="icon"></span>-->
										<!--</label>-->
									<!--</div>-->
									<!--<nuxt-link-->
										<!--:to="'/listing-details/' + product.id"-->
										<!--class="card_item_thumbnail"-->
										<!--:style="{ backgroundImage: 'url('+ product.productImage + ')' }"-->
										<!--:title="product.name"-->
									<!--&gt;</nuxt-link>-->
									<!--<nuxt-link-->
										<!--:to="'/listing-details/' + product.id"-->
										<!--class="card_item__info"-->
										<!--:title="product.name"-->
									<!--&gt;-->
										<!--<div v-bind:class="[product.defaultClass, product.category_slug]">-->
											<!--{{-->
											<!--product.category}}-->
										<!--</div>-->
										<!--<h3>{{ product.name }}</h3>-->
										<!--<div class="card_item_price">-->
											<!--<div class="value">${{ product.price }}</div>-->
											<!--<div class="location">{{ product.location }}</div>-->
										<!--</div>-->
										<!--<div class="card_item_transaction_type">-->
											<!--<div class="name">-->
												<!--<div-->
													<!--class="user_image"-->
													<!--:style="{ backgroundImage: 'url('+ product.ownerThumbnail + ')' }"-->
												<!--&gt;</div>-->
												<!--{{ product.owner }}-->
											<!--</div>-->
											<!--<ul class="transaction_type" role="listbox">-->
												<!--<li v-for="(transaction, index) in product.transaction" :key="index">{{-->
													<!--transaction }}-->
												<!--</li>-->
											<!--</ul>-->
										<!--</div>-->
										<!--<div class="looking_for">-->
											<!--<h4>Looking for</h4>-->
											<!--<ul role="listbox">-->
												<!--<li v-for="(service, index) in product.lookingFor" :key="index">-->
													<!--<div class="cat_bg">-->
														<!--<img-->
															<!--:src="require('~/assets/img/illustrations/' + service.icon + '.svg')"-->
															<!--:alt="service.service"-->
														<!--&gt;-->
													<!--</div>-->
													<!--<p>{{ service.service }}</p>-->
												<!--</li>-->
											<!--</ul>-->
										<!--</div>-->
									<!--</nuxt-link>-->
								<!--</div>-->
							<!--</div>-->
						<!--</div>-->
					<!--</div>-->
				<!--</div>-->
			<!--</div>-->
			<div class="page_section" id="firstScroll">
				<div class="container">
					<h2>Trending trades
						<!--<nuxt-link class="float_right" to="/see-all" title="See all">See all</nuxt-link>-->
					</h2>
					<div class="main_slider">
						<div class="card_item_slider">
							<div v-for="product in allProducts" :key="product.id">
								<CardItem :id="product.id" :listing="product" />
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--<div class="page_section" v-if="isAuthenticated">-->
				<!--<div class="container">-->
					<!--<h2>Gear up for summer-->
						<!--<nuxt-link class="float_right" to="/see-all" title="See all">See all</nuxt-link>-->
					<!--</h2>-->
					<!--<div class="main_slider">-->
						<!--<div class="card_item_slider">-->
							<!--<div v-for="product in allProducts" :key="product.id">-->
								<!--<div class="card_item">-->
									<!--<div class="save_btn">-->
										<!--<label>-->
											<!--<input type="checkbox" name="state">-->
											<!--<span class="icon"></span>-->
										<!--</label>-->
									<!--</div>-->
									<!--<nuxt-link-->
										<!--:to="'/listing-details/' + product.id"-->
										<!--class="card_item_thumbnail"-->
										<!--:style="{ backgroundImage: 'url('+ product.productImage + ')' }"-->
										<!--:title="product.name"-->
									<!--&gt;</nuxt-link>-->
									<!--<nuxt-link-->
										<!--:to="'/listing-details/' + product.id"-->
										<!--class="card_item__info"-->
										<!--:title="product.name"-->
									<!--&gt;-->
										<!--<div v-bind:class="[product.defaultClass, product.category_slug]">-->
											<!--{{-->
											<!--product.category}}-->
										<!--</div>-->
										<!--<h3>{{ product.name }}</h3>-->
										<!--<div class="card_item_price">-->
											<!--<div class="value">${{ product.price }}</div>-->
											<!--<div class="location">{{ product.location }}</div>-->
										<!--</div>-->
										<!--<div class="card_item_transaction_type">-->
											<!--<div class="name">-->
												<!--<div-->
													<!--class="user_image"-->
													<!--:style="{ backgroundImage: 'url('+ product.ownerThumbnail + ')' }"-->
												<!--&gt;</div>-->
												<!--{{ product.owner }}-->
											<!--</div>-->
											<!--<ul class="transaction_type" role="listbox">-->
												<!--<li v-for="(transaction, index) in product.transaction" :key="index">{{-->
													<!--transaction }}-->
												<!--</li>-->
											<!--</ul>-->
										<!--</div>-->
										<!--<div class="looking_for">-->
											<!--<h4>Looking for</h4>-->
											<!--<ul role="listbox">-->
												<!--<li v-for="(service, index) in product.lookingFor" :key="index">-->
													<!--<div class="cat_bg">-->
														<!--<img-->
															<!--:src="require('~/assets/img/illustrations/' + service.icon + '.svg')"-->
															<!--:alt="service.service"-->
														<!--&gt;-->
													<!--</div>-->
													<!--<p>{{ service.service }}</p>-->
												<!--</li>-->
											<!--</ul>-->
										<!--</div>-->
									<!--</nuxt-link>-->
								<!--</div>-->
							<!--</div>-->
						<!--</div>-->
					<!--</div>-->
				<!--</div>-->
			<!--</div>-->
		</div>
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<div v-if="$device.isMobileOrTablet">
			<div class="page_section">
				<div class="container">
					<h1>
						Explore trades by category
					</h1>
					<ul class="categories_list" role="list">
						<li>
							<a href="#" class="art_collectibles">
								<img src="~/assets/img/illustrations/art-collectibles.svg" alt="art & collectibles">
								<p>
									Art &amp; <br>
									Collectibles
								</p>
							</a>
						</li>
						<li>
							<a href="#" class="arts_crafts">
								<img src="~/assets/img/illustrations/arts-crafts.svg" alt="arts crafts">
								<p>
									Arts &amp; Crafts
								</p>
							</a>
						</li>
						<li>
							<a href="#" class="experiences">
								<img src="~/assets/img/illustrations/experiences.svg" alt="experiences">
								<p>
									Experiences
								</p>
							</a>
						</li>
						<li>
							<a href="#" class="motors">
								<img src="~/assets/img/illustrations/motors.svg" alt="motors">
								<p>
									Motors
								</p>
							</a>
						</li>
						<li>
							<a href="#" class="clothing_accessories">
								<img src="~/assets/img/illustrations/clothing-accessories.svg"
								     alt="clothing accessories">
								<p>
									Clothing &amp; <br> Accessories
								</p>
							</a>
						</li>
						<li>
							<a href="#" class="homes_housing">
								<img src="~/assets/img/illustrations/homes-housing.svg" alt="homes housing">
								<p>
									Homes &amp; <br> Housing
								</p>
							</a>
						</li>
						<li>
							<a href="#" class="electronics">
								<img src="~/assets/img/illustrations/electronics.svg" alt="electronincs">
								<p>
									Electronics
								</p>
							</a>
						</li>
						<li>
							<a href="#" class="services">
								<img src="~/assets/img/illustrations/services.svg" alt="services">
								<p>
									Services
								</p>
							</a>
						</li>
						<li>
							<a href="#" class="sporting_goods">
								<img src="~/assets/img/illustrations/sporting-goods.svg" alt="sporting goods">
								<p>
									Porting Goods
								</p>
							</a>
						</li>
						<li>
							<a href="#" class="business_industrial">
								<img src="~/assets/img/illustrations/business-industrial.svg" alt="business industrial">
								<p>
									Business &amp; <br> Industrial
								</p>
							</a>
						</li>
						<li>
							<a href="#" class="other">
								<img src="~/assets/img/illustrations/other.svg" alt="other">
								<p>
									Other
								</p>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="page_section">
				<div class="container">
					<h1>
						Trending trades happening now
					</h1>
					<div class="cards_grid">
						<div class="card_item card_item__reduced " v-for="product in allProducts.slice(0, 4)" :key="product.id">
							<nuxt-link :to="'/listing-details/' + product.id"
							           class="card_item_thumbnail"
							           :style="{ backgroundImage: 'url('+ product.mainPhotoUrl + ')' }"
							           :title="product.title">

							</nuxt-link>
							<nuxt-link :to="'/listing-details/' + product.id" class="card_item__info"
							           :title="product.name">
								<div v-bind:class="[product.defaultClass, product.category_slug]">
									{{ product.category }}
								</div>
								<h3>
									{{ product.title }}
								</h3>
								<div class="card_item_price">
									<div class="value">${{ product.price }}</div>
									<div class="location">{{ product.location }}</div>
								</div>
							</nuxt-link>
						</div>
					</div>
<!--					<nuxt-link class="btn btn__secondary see_all" to="/see-all" title="See all">-->
<!--						See all <img src="~assets/img/long-arrow-right.png" alt="arrow">-->
<!--					</nuxt-link>-->
				</div>
			</div>
			<div class="page_section">
				<div class="container">
					<h1>
						Featured trades
					</h1>
					<div class="cards_grid">
						<div class="card_item" v-for="product in allProducts.slice(0, 4)" :key="product.id">
							<div class="save_btn">
								<label>
									<input type="checkbox" name="state">
									<span class="icon"></span>
								</label>
							</div>
							<nuxt-link :to="'/listing-details/' + product.id"
							           class="card_item_thumbnail"
							           :style="{ backgroundImage: 'url('+ product.mainPhotoUrl + ')' }"
							           :title="product.title">

							</nuxt-link>
							<nuxt-link :to="'/listing-details/' + product.id" class="card_item__info"
							           :title="product.title">
								<div v-bind:class="[product.defaultClass, product.category_slug]">
									{{ product.category }}
								</div>
								<h3>
									{{ product.title }}
								</h3>
								<div class="card_item_price">
									<div class="value">${{ product.price }}</div>
									<div class="location">{{ product.location }}</div>
								</div>
								<div class="card_item_transaction_type">
									<div class="name">
										<div
											class="user_image"
											:style="{ backgroundImage: 'url('+ product.user_photo + ')' }"
										></div>
										{{ product.owner }}
									</div>
									<ul class="transaction_type" role="listbox">
										<li
											v-for="(transaction, index) in product.transaction"
											:key="index"
										>{{ transaction }}
										</li>
									</ul>
								</div>
								<div class="card_item_buttons">
									<button type="button" class="btn btn__primary">Make an offer</button>
									<button type="button" class="btn btn__secondary">Message</button>
								</div>
							</nuxt-link>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import HeaderHomepage from "~/components/HeaderHomepage.vue";
	import HeaderMobile from "~/components/HeaderMobile.vue";
	import CardItem from '~/components/CardItem.vue';
	import HeroSearch from "~/components/HeroSearch.vue";
	import SponsoredListing from "~/components/SponsoredListing.vue";
	import {mapGetters} from "vuex";
	import image1 from "~/assets/img/card-item/Image.png";
	import image2 from "~/assets/img/card-item/Image2.png";
	import image3 from "~/assets/img/card-item/Image3.png";
	import image4 from "~/assets/img/card-item/Image4.png";
	import ownerImage from "~/assets/img/card-item/user_image.png";

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
			}
		]
	};

	export default {
		components: {
			HeaderHomepage,
			HeaderMobile,
			HeroSearch,
			SponsoredListing,
			CardItem
		},
		head() {
			return {
				meta: [
					{
						hid: "description",
						name: "description",
						content: "Homepage description"
					}
				]
			};
		},
		data() {
			return {
				image1,
				image2,
				image3,
				image4,
				ownerImage,
				allProducts: [],
			};
		},
		async asyncData({store,query}) {
			let data = await store.dispatch("apiCalls/getDefaultListings", query);
			return data;
		},
		computed: {
			...mapGetters("auth", ["isAuthenticated", "loggedInUser"])
		}
	};
</script>
