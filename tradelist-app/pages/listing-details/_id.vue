<template>
	<div
		:class="isAuthenticated &&  listing.user.id !== loggedInUser.id ? '' : 'listing_owner'"
		class="product_page"
	>
		<div class="container">
			<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<!-- Listing Detail -->
			<ins class="adsbygoogle"
				style="display:block"
				data-ad-client="ca-pub-1709497292936218"
				data-ad-slot="4464166452"
				data-ad-format="auto"
				data-full-width-responsive="true">
			</ins>
			<script>
				(adsbygoogle = window.adsbygoogle || []).push({});
			</script>

			<Header v-if="$device.isDesktop"/>
			<HeaderMobile v-if="$device.isMobileOrTablet"/>
			<no-ssr>
				<modal v-if="showModalMessage" class="modal_message" @close="closeModalMessage()">
					<div slot="body">
						<form method="post" @submit.prevent="sendMessage()">
							<div class="message_box_modal">
								<header>
									<div class="user_image sdasdas" v-if="listing.user.photo"
									     :style="{ backgroundImage: 'url('+ listing.user.photo + ')' }"></div>
									<div v-else class="user_image"></div>
									<div class="message_box__to">
										<p>
											<strong>To:</strong>
											<span>{{ listing.user.name }}</span>
										</p>
										<p>
											<strong>Subject:</strong>
											<span>Regarding this product</span>
										</p>
									</div>
									<button class="btn btn__primary">Send Message</button>
								</header>
								<textarea name="sendMessage" v-model="textMessage"
								          placeholder="Enter message here.."></textarea>
							</div>
						</form>
					</div>
				</modal>
			</no-ssr>
			<div class="row">
				<div :class="isAuthenticated && listing.user.id !== loggedInUser.id ? 'col-lg-8' : 'col-lg-9'">
					<div class="sample_tag"
					     v-if="parseInt(listing.user.sample_account) === 1 && listing.photos.length === 0">
						Sample account
					</div>
					<ProductGallery :galleryImages="listing.photos" :sample_account="listing.user.sample_account" v-if="listing.photos.length !== 0"/>
					<div
						class="watch_item"
						v-else
						v-show="isAuthenticated &&  listing.user.id !== loggedInUser.id"
					>
						<label class="float_right">
							<input type="checkbox">
							<i class="icon-save"></i> Watch item
						</label>
					</div>
					<div class="product_details">
						<div class="category" :style="{color:listing.mainCategory.colorCode}">{{listing.mainCategory.name}}</div>
						<div class="product_title_wrapper">
							<div class="product_title">
								<h1>{{listing.title}}</h1>
								<div class="owner_info">
									<div v-if="listing.user.photo" class="owner_thumbnail"
										 :style="{ backgroundImage: 'url('+ listing.user.photo + ')' }"></div>
									<div v-else class="owner_thumbnail"></div>
									<h4 v-if="listing.user.name">{{listing.user.name}}</h4>
									<h4 v-else>Unknown</h4>
									<div class="rating">
										<span v-if="listing.user.rating">
											<i class="icon-star"></i>
											{{listing.user.rating}}
										</span>
										<span v-else>
											<i class="icon-star"></i> N.A
										</span>
										<span>
											<a href="#">Follow</a>
										</span>
									</div>
								</div>
							</div>
							<div class="location_views">
								<span class="location" v-if="listing.location">
									<i class="icon-location"></i>
									{{listing.locationText}}
								</span>

								<!--<span class="views">-->
									<!--<i class="icon-eye"></i>-->
									<!--Viewed {{listing.views}} times-->
								<!--</span>-->
							</div>
							<ul class="delivery_info">
								<li>
									<div class="rounded_icon">
										<i class="icon-envelope"></i>
									</div>
									<p>
										Shipping available
										<small v-if="listing.shipping == 1">Yes</small>
										<small v-else>No</small>
									</p>
								</li>
								<li v-if="listing.condition">
									<div class="rounded_icon">
										<i class="icon-tag"></i>
									</div>
									<p v-if="listing.condition == 1">
										Used
										<small>Item Condition</small>
									</p>
									<p v-else-if="listing.condition == 2">
										New
										<small>Item Condition</small>
									</p>
								</li>
							</ul>
						</div>
					</div>
					<div class="product_details">
						<div class="product_highlights details_section">
							<div class="title">
								<h2>Highlights</h2>
							</div>
							<p v-if="listing.affiliateLink"><a class="btn" :href="listing.affiliateLink" target="_blank" rel="nofollow">Visit Store</a></p>
							<ul>
								<li>Category: {{listing.mainCategory.name}}</li>
								<li v-for="tag in listing.tags">Tag: {{tag.name}}</li>
								<li v-if="listing.metaTags">Meta tags: {{listing.metaTags}}</li>
							</ul>
						</div>
						<!--<div class="trades_interests details_section">-->
						<!--<div class="title">-->
						<!--<h2>Interested in trades for…</h2>-->
						<!--</div>-->
						<!--<h4>Categories</h4>-->
						<!--<ul class="categories">-->
						<!--<li class="experiences">-->
						<!--<img src="~assets/img/illustrations/experiences.svg" alt="experiences">-->
						<!--<p>Experiences</p>-->
						<!--</li>-->
						<!--<li class="motors">-->
						<!--<img src="~assets/img/illustrations/motors.svg" alt="motors">-->
						<!--<p>Motors</p>-->
						<!--</li>-->
						<!--<li class="clothing_accessories">-->
						<!--<img src="~assets/img/illustrations/clothing-accessories.svg"-->
						<!--alt="clothing accessories">-->
						<!--<p>-->
						<!--Clothing & <br> Accessories-->
						<!--</p>-->
						<!--</li>-->
						<!--</ul>-->
						<!--<h4>Specific items</h4>-->
						<!--<ul class="trade_items_list">-->
						<!--<li class="sporting_goods">-->
						<!--<div class="cat_bg">-->
						<!--<img src="~assets/img/illustrations/sporting-goods.svg" alt="sporting goods">-->
						<!--</div>-->
						<!--<p>-->
						<!--<strong>Slingshot Asylum Kiteboard 2018</strong>-->
						<!--</p>-->
						<!--</li>-->
						<!--<li class="experiences">-->
						<!--<div class="cat_bg">-->
						<!--<img src="~assets/img/illustrations/experiences.svg" alt="experiences">-->
						<!--</div>-->
						<!--<p>-->
						<!--<strong>Tickets to Hamilton</strong> in <strong>Seattle, WA</strong>-->
						<!--</p>-->
						<!--</li>-->
						<!--<li class="services">-->
						<!--<div class="cat_bg">-->
						<!--<img src="~assets/img/illustrations/services.svg" alt="services">-->
						<!--</div>-->
						<!--<p>-->
						<!--Lanscaping services-->
						<!--</p>-->
						<!--</li>-->
						<!--<li class="homes_housing">-->
						<!--<div class="cat_bg">-->
						<!--<img src="~assets/img/illustrations/homes-housing.svg" alt="homes housing">-->
						<!--</div>-->
						<!--<p>-->
						<!--<strong>Vacation homes</strong> in <strong>Hood River, OR</strong>-->
						<!--</p>-->
						<!--</li>-->
						<!--<li class="sporting_goods">-->
						<!--<div class="cat_bg">-->
						<!--<img src="~assets/img/illustrations/sporting-goods.svg" alt="sporting goods">-->
						<!--</div>-->
						<!--<p>-->
						<!--<strong>Santa Cruz Mountain Bikes</strong>-->
						<!--</p>-->
						<!--</li>-->
						<!--</ul>-->
						<!--</div>-->
						<div class="specs details_section">
							<ul class="product_nav_tab">
								<li class="active">
									<a href="#tab1" v-on:click="onTabclick">Summary</a>
								</li>
								<li>
									<a href="#tab2" v-on:click="onTabclick">Specification</a>
								</li>
							</ul>
							<div class="tab_content">
								<div class="tab_pane active" id="tab1">{{ listing.description }}</div>
								<div class="tab_pane" id="tab2">
									<p v-if="listing.brand">Brand: {{listing.brand}}</p>
									<p v-if="listing.color">Color: {{listing.color}}</p>
									<p v-if="listing.material">Material: {{listing.material}}</p>
									<p v-if="listing.year">Year: {{listing.year}}</p>
									<p v-if="listing.qty">QTY: {{listing.qty}}</p>
									<p v-if="listing.notes">Notes: {{listing.notes}}</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4" v-if="isAuthenticated &&  listing.user.id !== loggedInUser.id">
					<aside class="product_offer">
						<header>
							<h3>Make an offer</h3>
						</header>
						<div class="price">
							${{ listing.price }}
							<small>Listed value</small>
						</div>
						<div data-rating="4" class="trader_rating" v-if="listing.user.rating">
							<i class="icon-star"></i>
							<i class="icon-star"></i>
							<i class="icon-star"></i>
							<i class="icon-star"></i>
							<i class="icon-star"></i>
							{{listing.user.rating}} Trader rating
						</div>
						<div class="trader_rating" v-else>No rating</div>
						<hr>
						<h4>Select item(s)</h4>
						<div class="listing_list">
							<div class="item_wrapper" v-for="(checkedName, index) in checkedNames" :key="index">
								<div class="item">
									<span
										class="listing_img"
										:style="{ backgroundImage: 'url('+ allListings[checkedName].mainPhotoUrl + ')'
                                                  }"
									></span>
									<span class="listing_info">
										<p>{{ allListings[checkedName].title }}</p>
										<span>${{ allListings[checkedName].price }}</span>
									</span>
								</div>
							</div>
							<button
								type="button"
								class="btn_select"
								@click="triggerModal()"
								role="button"
								aria-label="Open Modal"
							>
								<i class="icon-add"></i>
							</button>
						</div>
						<modal v-if="showModalListings" class="modal_listings" @close="closeModalListings()">
							<div slot="header">
								<div class="header_inner">
									<p>Select what you want to offer</p>
									<button type="button" @click="closeModalListings()">
										<i class="icon-close"></i>
									</button>
								</div>
							</div>
							<div slot="body">
								<h4>YOUR LISTINGS</h4>
								<form class="listing_list">
									<div class="item_wrapper" v-for="(listing, index) in allListings" :key="index">
										<input
											type="checkbox"
											:value="index"
											:id="'item-' +
                                        listing.id"
											ref="listing"
											v-model="checkedNames"
											@change="updateTradeValue"
										>
										<label class="item" :for="'item-' + listing.id">
											<span
												class="listing_img"
												:style="{ backgroundImage: 'url('+ listing.mainPhotoUrl + ')' }"
											></span>
											<span class="listing_info">
												<p>{{ listing.title }}</p>
												<span>${{ listing.price }}</span>
											</span>
										</label>
									</div>
									<div class="item_wrapper">
										<nuxt-link to="/create-listing">
											<i class="icon-add"></i> Create a new listing
										</nuxt-link>
									</div>
								</form>
							</div>
							<div slot="footer">
								<div class="footer_inner">
									<button
										type="button"
										class="btn btn__primary float_right"
										@click="closeModalListings"
									>Review
									</button>
								</div>
							</div>
						</modal>
						<hr>
						<h4>Cash offer</h4>
						<ul class="cash_offer">
							<li>
								<div class="custom_radio">
									<input
										type="radio"
										id="mkOfferListedValue"
										name="mkOfferRadio"
										value="1"
										v-model="mkOfferRadio"
										@change="updateTradeValue"
									>
									<label for="mkOfferListedValue">
										<p>
											Listed value:
											<strong>${{ listing.price }}</strong>
										</p>
										<span class="checkbox"></span>
									</label>
								</div>
							</li>
							<li>
								<div class="custom_radio">
									<input
										type="radio"
										id="mkOfferOther"
										name="mkOfferRadio"
										value="2"
										v-model="mkOfferRadio"
										@change="updateTradeValue"
									>
									<label for="mkOfferOther">
										<p>Other</p>
										<div class="checkbox"></div>
									</label>
								</div>
								<input type="text" placeholder="$0.00" :disabled="mkOfferRadio == 1" v-model="cashOffer"
								       @change="updateTradeValue" @focus="mkOfferRadio=2">
							</li>
						</ul>
						<h4>Your trade value
							<div
								v-if="tradeValue > 0"
								class="trade_val float_right"
							>{{ getPercentageChange(listing.price, tradeValue) }}%
							</div>
						</h4>
						<button
							type="button"
							class="btn btn__primary"
							role="button"
							aria-label="Make offer"
							@click="makeAnOffer"
						>
							Make offer
						</button>
						<div class="actions">
							<button
								v-if="isAuthenticated &&  listing.user.id !== loggedInUser.id"
								class="btn btn__secondary"
								title="Message"
								@click="openModalMessage"
							>
								<i class="icon-message"></i> Message
							</button>
						</div>
					</aside>
				</div>
			</div>
			<!--<div class="page_section">-->
				<!--<h2>Similar items-->
					<!--<nuxt-link to="/" title="Watch similar items">-->
						<!--<i class="icon-save"></i> Watch similar items-->
					<!--</nuxt-link>-->
					<!--<nuxt-link to="#" class="float_right" title="See all">See all</nuxt-link>-->
				<!--</h2>-->
				<!--<div class="main_slider">-->
					<!--<div class="similar_listing_slider">-->
						<!--<div-->
							<!--v-for="(product, key) in allProducts"-->
							<!--:key="key"-->
						<!--&gt;-->
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
										<!--{{ product.category }}-->
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
		</div>
		<no-ssr>
			<modal v-if="showModalMakeOfferSuccess" class="modal_offer_success success_modal"
			       @close="closeModalMakeOfferSuccess()">
				<div slot="body">
					<div class="success_icon">
						<i class="icon-success-hand"></i>
					</div>
					<h3>Success!</h3>
					<p>You can view the status of <br> you offer here</p>
					<div class="extra_option">
						<n-link
							to="/dashboard/offers"
							class="btn btn__primary"
							aria-label="View offer"
						>View Offer</n-link>
					</div>
				</div>
			</modal>
		</no-ssr>
	</div>
</template>

<script>
	import Header from "~/components/Header.vue";
	import HeaderMobile from "~/components/HeaderMobile.vue";
	import ProductGallery from "~/components/ProductGallery.vue";
	import Modal from "~/components/ModalTemplate.vue";
	import listingDetailsJS from "~/assets/js/listingDetails.js";
	import bike from "~/assets/img/listing-details/bike.jpg";
	import catering from "~/assets/img/listing-details/catering.jpg";
	import landscaping from "~/assets/img/listing-details/landscaping.jpg";
	import sail from "~/assets/img/listing-details/sail.jpg";
	import watch from "~/assets/img/listing-details/watch.jpg";
	import hat from "~/assets/img/listing-details/hat.jpg";
	import image1 from "~/assets/img/card-item/Image.png";
	import image2 from "~/assets/img/card-item/Image2.png";
	import image3 from "~/assets/img/card-item/Image3.png";
	import image4 from "~/assets/img/card-item/Image4.png";
	import ownerImage from "~/assets/img/card-item/user_image.png";
	import axios from "axios";
	import {mapGetters} from "vuex";

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
				name: "MacBook Pro 13 2012 *Grade A...",
				productImage: image2,
				category: "household",
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
				productImage: image1,
				category: "household",
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
				category: "household",
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
				name: "MacBook Pro 13 2012 *Grade A...",
				productImage: image3,
				category: "household",
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
			Header,
			HeaderMobile,
			ProductGallery,
			Modal
		},
		extends: listingDetailsJS,
		data() {
			return {
				bike,
				catering,
				landscaping,
				sail,
				watch,
				hat,
				allListings: [],
				showModalListings: false,
				showModalMessage: false,
				checkedNames: [],
				tradeValue: 0,
				image1,
				image2,
				image3,
				image4,
				ownerImage,
				allProducts: response.allProducts,
				userProducts: [],
				mkOfferRadio: 0,
				cashOffer: 0,
				productCategory: "household",
				textMessage: '',
				showModalMakeOfferSuccess: false
			};
		},
		computed: {
			...mapGetters("auth", ["isAuthenticated", "loggedInUser"])
		},
		mounted() {
			// this.sliderSimilarListingInit();
		}
	};
</script>


<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";

	.sample_tag {
		position: absolute;
		left: 15px;
		top: 41px;
		border-radius: 3px;
		border: 1px solid $gray_tuna;
		background: rgba($white, .6);
		text-align: center;
		padding: 6px 8px;
		font-size: 11px;
		line-height: 16px;
		color: $gray_tuna;
		text-transform: uppercase;
		z-index: 20;
	}

	.listing_owner {
		.rating {
			text-align: center;

			span {
				a {
					display: none;
				}

				& + span {
					&:before {
						display: none;
					}
				}
			}
		}
	}

	.main_slider {
		max-height: 700px;
	}

	.trade_items_list {
		padding: 0 0 19px;
	}

	.watch_item {
		@include clearfix;
		width: 100%;
		margin: 24px 0 0;

		input[type=checkbox] {
			position: relative;
			z-index: -9;
			opacity: 0;
			visibility: hidden;

			&:checked {
				& + i {
					color: $saffron;
				}
			}
		}

		label {
			border: 1px solid $gray_tuna;
			background: rgba($white, .6);
			border-radius: 3px;
			color: $gray_tuna;
			min-width: 130px;
			font-size: 12px;
			padding: 12.5px 30px 14.5px 35px;
			position: relative;
			cursor: pointer;
			text-align: right;

			i {
				position: absolute;
				top: 50%;
				left: 19px;
				transform: translateY(-50%);
				font-size: 16px;
				color: $gray_tuna;
				margin-right: 5px;
			}
		}
	}

	.product_gallery {
		margin-bottom: 21px;

		button[data-controls="prev"],
		button[data-controls="next"] {
			opacity: 0.8;
			border: none;

			&:hover {
				opacity: 1;
			}
		}

		& + .product_details {
			padding-top: 0;
		}
	}

	.product_offer {
		border: 1px solid $gray_aluminium;
		border-radius: 4px;
		background: $white;
		margin: 24px 0;
		padding: 0 12px 25px 27px;

		header {
			position: relative;
			margin-left: -27px;
			width: calc(100% + 40px);
			border-bottom: 1px solid $gray_aluminium;
			padding: 37px 31px 29px;
			margin-bottom: 19px;
		}

		h3 {
			font-size: 18px;
			line-height: 24px;
			color: $gray_tuna;
		}

		h4 {
			@include clearfix;
			font-size: 10px;
			line-height: 13px;
			text-transform: uppercase;
			color: $gray_aluminium;
			letter-spacing: 2px;
			margin-bottom: 12px;

			.trade_val {
				font-size: 18px;
				line-height: 24px;
				color: $gray_tuna;
				font-weight: 300;
				padding-right: 20px;
				position: relative;

				&:after {
					content: "";
					position: absolute;
					top: 50%;
					right: 0;
					transform: translateY(-50%);
					width: 0;
					height: 0;
					border-left: 7px solid transparent;
					border-right: 7px solid transparent;
					border-bottom: 8px solid $shamrock;
				}
			}
		}

		hr {
			margin: 0 0 15.5px;
			border: none;
			height: 1px;
			width: 100%;
			background: rgba($gray_aluminium, 0.23);
		}

		.price {
			font-size: 38px;
			line-height: 50px;
			color: $gray_tuna;
			font-weight: 700;

			small {
				font-size: 14px;
				color: $gray_tuna;
			}
		}

		.trader_rating {
			font-size: 10px;
			line-height: 20px;
			color: $gray_aluminium;
			margin-bottom: 14.5px;

			i {
				color: $saffron;
				font-size: 12px;
				display: none;
			}

			&[data-rating="1"] {
				i {
					&:nth-child(-n + 1) {
						display: inline-flex;
					}
				}
			}

			&[data-rating="2"] {
				i {
					&:nth-child(-n + 2) {
						display: inline-flex;
					}
				}
			}

			&[data-rating="3"] {
				i {
					&:nth-child(-n + 3) {
						display: inline-flex;
					}
				}
			}

			&[data-rating="4"] {
				i {
					&:nth-child(-n + 4) {
						display: inline-flex;
					}
				}
			}

			&[data-rating="5"] {
				i {
					&:nth-child(-n + 5) {
						display: inline-flex;
					}
				}
			}
		}

		.btn_select {
			width: 100%;
			max-width: 228px;
			height: 90px;
			border: 1px solid $gray_gallery;
			border-radius: 3px;
			background: $gray_albaster;
			line-height: 90px;
			display: flex;
			align-items: center;
			justify-content: center;
			margin-bottom: 17.5px;
			transition: 0.2s $bezier_ease_in_out;

			&:hover {
				box-shadow: 0 1px 7px 0 rgba(0, 0, 0, 0.3);
			}

			i {
				color: $blue_scooter;
				font-size: 16px;
			}
		}

		.cash_offer {
			margin-bottom: 44px;

			li {
				margin-bottom: 3px;
			}

			label {
				display: flex;

				.checkbox {
				}
			}

			p {
				font-size: 12px;
				line-height: 28px;
				color: $gray_aluminium;
				margin: 0;

				strong {
					color: #1b001c;
				}
			}

			input {
				margin-top: 4px;
				max-width: 146px;
				height: 50px;

				&:disabled {
					opacity: .4;
				}
			}
		}

		.btn {
			display: block;
			width: 100%;
		}

		.btn__primary {
			margin: 25px 0 17px;
		}

		.actions {
			display: flex;
			padding-top: 5px;

			.btn__secondary {
				border: none;
				color: $gray_tuna;
				padding: 6px 20px;
				text-transform: capitalize;
				font-size: 12px;

				i {
					color: $gray_tuna;
					margin-right: 10px;
					font-size: 16px;
					vertical-align: middle;
				}

				&:hover {
					box-shadow: none;
				}
			}
		}
	}

	.product_page {
		.page_section {
			padding-top: 90px;
		}

		.listing_list {
			display: flex;
			flex-wrap: wrap;

			input {
				position: absolute;
				opacity: 0;
				visibility: hidden;
				z-index: -9;

				&:checked {
					& + label {
						border: 2px solid $shamrock_darker;
					}
				}
			}

			.item_wrapper {
				& + .btn_select {
					width: 121px;
				}

				a {
					text-decoration: none;
					border: 1px solid $gray_ghost;
					background: $white;
					padding: 38px 0 37px;
					font-size: 10px;
					letter-spacing: 1px;
					text-align: center;
					color: $gray_aluminium;
					text-transform: uppercase;
					line-height: 18px;
					width: 304px;
					display: block;
					border-radius: 5px;
					margin-bottom: 6px;

					i {
						margin-right: 10px;
						font-size: 16px;
						vertical-align: middle;
					}
				}
			}

			.item {
				display: flex;
				align-items: center;
				margin-right: 10px;
				border-radius: 3px;
				min-width: 297px;
				background: $gray_albaster;
				border: 1px solid $gray_gallery;
				transition: 0.2s $bezier_ease_in_out;
				margin-bottom: 6px;
			}

			label.item {
				cursor: pointer;
				min-width: 304px;
			}

			.listing_img {
				width: 90px;
				height: 90px;
				margin-right: 19px;
				background-color: $gray_aluminium;
				background-position: center;
				background-size: cover;
				background-repeat: no-repeat;
			}

			.listing_info {
				width: calc(100% - 109px);
				padding-right: 15px;

				p {
					font-size: 14px;
					color: $gray_tuna;
					line-height: 18px;
					margin: 0 0 5px;
				}

				span {
					font-size: 12px;
					line-height: 16px;
					color: $gray_aluminium;
					letter-spacing: 0.9px;
					display: block;
				}
			}
		}
	}

	.product_details {
		padding-top: 25px;

		.details_section {
			background: $white;
			margin-bottom: 12px;
			padding: 0 31px;
		}

		.category {
			text-transform: uppercase;
			font-size: 18px;
			letter-spacing: 0;
		}

		.product_title_wrapper {
			margin-bottom: 47px;

			h1 {
				font-size: 38px;
				line-height: 50px;
				color: $black;
				font-weight: 400;
			}

			.product_title {
				display: flex;
				justify-content: space-between;
				align-items: center;
				margin-right: 20px;
			}

			.owner_info {
				text-align: center;

				h4 {
					font-size: 14px;
					line-height: 18px;
					color: $gray_tuna;
					margin-bottom: 6px;
				}

				.rating {
					font-size: 12px;
					line-height: 16px;

					span {
						color: $gray_aluminium;

						a {
							color: $blue_picton;
							text-decoration: none;
						}

						& + span {
							padding-left: 12px;
							position: relative;

							&:before {
								content: "";
								position: absolute;
								top: 50%;
								left: 2px;
								transform: translateY(-50%);
								width: 3px;
								height: 3px;
								border-radius: 50%;
								background: #aaaeb3;
							}
						}
					}

					i {
						color: $saffron;
					}
				}
			}

			.owner_thumbnail {
				width: 109px;
				height: 109px;
				border-radius: 50%;
				background-color: $gray_alto;
				margin: 0 auto 13px;
			}

			.location_views {
				margin-bottom: 40px;

				span {
					font-size: 18px;
					line-height: 24px;
					color: $gray_tuna;
					margin-right: 13px;
				}

				i {
					color: $gray_tuna;
					position: relative;
					top: 1px;
					margin-right: 8px;
				}

				.icon-location {
					font-size: 20px;
				}

				.icon-eye {
					font-size: 15px;
				}
			}
		}

		.delivery_info {
			display: flex;
			justify-content: flex-start;
			flex-wrap: wrap;
			align-items: center;
			margin-bottom: 44px;

			li {
				display: flex;
				align-items: center;
				width: 355px;
				margin-bottom: 15px;
			}

			.rounded_icon {
				display: block;
				width: 110px;
				height: 110px;
				background: $white;
				border-radius: 50%;
				margin-right: 14px;
				position: relative;
				font-size: 35px;

				i {
					color: $shamrock;
					position: absolute;
					top: 50%;
					left: 50%;
					transform: translate(-50%, -50%);

					&:before {
						color: $shamrock;
					}
				}

				.icon-tag {
					font-size: 45px;
				}
			}

			p {
				font-size: 22px;
				line-height: 29px;
				color: $black;
				max-width: 210px;

				small {
					display: block;
					font-size: 16px;
					color: rgba($black, 0.4);
				}
			}
		}

		.title {
			position: relative;
			margin-left: -31px;
			width: calc(100% + 62px);
			padding: 38px 31px 30px;
			border-bottom: 1px solid rgba($gray_iron, 0.17);

			h2 {
				font-size: 18px;
				line-height: 24px;
				color: $gray_aluminium;
			}
		}

		.product_highlights {
			ul {
				padding: 27px 25px 29px;
				list-style: disc;

				li {
					font-size: 16px;
					line-height: 21px;
					color: #393c40;
				}
			}
		}

		.trades_interests {
			.categories {
				position: relative;
				margin-left: -31px;
				width: calc(100% + 62px);
				padding: 25px 34px 29px;
				display: flex;
				border-bottom: 1px solid rgba($gray_iron, 0.17);

				li {
					display: flex;
					flex-wrap: wrap;
					justify-content: center;
					align-items: center;
					margin-right: 30px;
					text-align: center;
				}

				p {
					width: 100%;
					font-size: 14px;
					line-height: 18px;
					font-weight: 700;
					margin-top: 8px;
					margin: 0;
				}

				img {
					margin-bottom: 8px;
				}
			}

			h4 {
				padding-top: 15px;
				font-size: 10px;
				line-height: 13px;
				letter-spacing: 2px;
				color: $gray_aluminium;
				margin-bottom: 20px;
				text-transform: uppercase;
			}
		}

		.tab_content {
			padding: 27px 0 33px;

			.tab_pane {
				position: absolute;
				opacity: 0;
				transition: 0.2s $bezier_ease_in_out;
				z-index: -9999;
			}

			.tab_pane {
				&.active {
					position: static;
					opacity: 1;
					z-index: auto;
				}
			}
		}

		.specs {
			.product_nav_tab {
				display: flex;
				position: relative;
				padding: 0 31px;
				margin-left: -31px;
				width: calc(100% + 62px);
				border-bottom: 1px solid rgba($gray_iron, 0.17);

				li {
					&.active {
						a:after {
							opacity: 1;
						}
					}
				}

				a {
					position: relative;
					display: block;
					font-size: 18px;
					line-height: 24px;
					color: $gray_tuna;
					padding: 40px 11px 28px;

					&:after {
						content: "";
						position: absolute;
						bottom: -1px;
						left: 50%;
						transform: translateX(-50%);
						width: 75%;
						height: 2px;
						background-color: $shamrock_darker;
						opacity: 0;
						transition: 0.2s $bezier_ease_in_out;
						border-radius: 3px;
					}
				}
			}

			p {
				font-size: 16px;
				line-height: 21px;
				color: $gray_tuna;
				margin-bottom: 30px;
			}
		}
	}

	.similar_listing_slider {
		white-space: nowrap;
		transition: all 0s;

		> .tns-item {
			display: inline-block;
			vertical-align: top;
			white-space: normal;
		}
	}
</style>
