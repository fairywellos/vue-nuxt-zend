<template>
	<div>
		<Header v-if="$device.isDesktop"/>
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<div class="container">
			<h1>Nearby</h1>
			<div class="cards_grid">
				<div class="card_item" v-for="product in allProducts" :key="product.id">
					<button type="button" class="save_btn" role="button">
						<label>
							<input type="checkbox" name="state">
							<span class="icon"></span>
						</label>
					</button>
					<nuxt-link
						:to="'/listing-details/' + product.id"
						class="card_item_thumbnail"
						:style="{ backgroundImage: 'url('+ product.productImage + ')' }"
						:title="product.name"
					></nuxt-link>
					<nuxt-link
						:to="'/listing-details/' + product.id"
						class="card_item__info"
						:title="product.name"
					>
						<div v-bind:class="[product.defaultClass, product.category_slug]">
							{{
							product.category}}
						</div>
						<h3>{{ product.name }}</h3>
						<div class="card_item_price">
							<div class="value">${{ product.price }}</div>
							<div class="location">{{ product.location }}</div>
						</div>
						<div class="card_item_transaction_type">
							<div class="name">
								<div class="user_image" :style="{ backgroundImage: 'url('+ product.ownerThumbnail + ')' }"></div>
								{{ product.owner }}
							</div>
							<ul class="transaction_type" role="listbox">
								<li v-for="(transaction, index) in product.transaction" :key="index">{{ transaction }}</li>
							</ul>
						</div>
						<div class="looking_for">
							<h4>Looking for</h4>
							<ul role="listbox">
								<li v-for="(service, index) in product.lookingFor" :key="index">
									<div class="cat_bg">
										<img
											:src="require('~/assets/img/illustrations/' + service.icon + '.svg')"
											:alt="service.service"
										>
									</div>
									<p>{{ service.service }}</p>
								</li>
							</ul>
						</div>
					</nuxt-link>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import Header from "~/components/Header";
	import HeaderMobile from "~/components/HeaderMobile";
	import image1 from "~/assets/img/card-item/Image.png";
	import image2 from "~/assets/img/card-item/Image2.png";
	import image3 from "~/assets/img/card-item/Image3.png";
	import image4 from "~/assets/img/card-item/Image4.png";
	import image5 from "~/assets/img/card-item/Image.jpg";
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
			},
			{
				id: "8",
				name: "One month co-working space",
				productImage: image3,
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
				productImage: image1,
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
		head() {
			return {
				title: "See all",
				meta: [
					{
						hid: "description",
						name: "description",
						content: "See all page description"
					}
				]
			};
		},
		components: {
			Header,
			HeaderMobile
		},
		data() {
			return {
				image1,
				image2,
				image3,
				image4,
				image5,
				ownerImage,
				allProducts: response.allProducts
			};
		}
	};
</script>

<style lang="scss" scoped>
	//VARIABLES
	// Colors

	$white: #fff;
	$black: #000;
	$blue_picton: #48c0eb;
	$blue_scooter: #32aedc;
	$gray_iron: #dbdcdf;
	$gray_albaster: #fcfcfc;
	$gray_ghost: #c6c9cf;
	$gray_aluminium: #aaaeb3;
	$gray_aluminium2: #ecedef;
	$gray_tuna: #393c40;
	$gray_alto: #d8d8d8;
	$gray_gallery: #eaeaea;
	$shark: #27292b;
	$shamrock_darker: #2dbd9b;
	$mine_shaft: #252424;
	$valencia: #dc4250;
	$persimmon: #ff5a5a;
	$saffron: #f7bc31;
	$shamrock: #3fd0ad;
	$bright_sun: #ffcf47;
	$tundora: #4a4a4a;
	$hot_pink: #ff689a;

	$gray_shark: #202224;
	$gray_abbey: #474b4f;
	$gray_pale_sky: #6f7379;
	$java: #1de9b6;
	$keppel: #37bc9b;
	$carnation: #ef5362;

	// Gradients
	$gradient_blue: linear-gradient(225deg, #16b2ec 0%, #4b9cb8 100%);
	$gradient_yellow: linear-gradient(225deg, #ffcc55 0%, #edb107 100%);
	$gradient_red: linear-gradient(225deg, #ffcc55 0%, #edb107 100%);
	$gradient_green: linear-gradient(225deg, #5dd1b5 0%, #1a9f80 100%);

	$bezier-1: cubic-bezier(0.65, 0.05, 0.36, 1);
	$bezier-2: cubic-bezier(0.1, 0.88, 0.3, 0.98);
	$bezier_linear: cubic-bezier(0.25, 0.25, 0.75, 0.75);
	$bezier_ease: cubic-bezier(0.25, 0.1, 0.25, 1);
	$bezier_ease_in: cubic-bezier(0.42, 0, 1, 1);
	$bezier_ease_out: cubic-bezier(0, 0, 0.58, 1);
	$bezier_ease_in_out: cubic-bezier(0.42, 0, 0.58, 1);
	$bezier_easeInQuad: cubic-bezier(0.55, 0.085, 0.68, 0.53);
	$bezier_easeInCubic: cubic-bezier(0.55, 0.055, 0.675, 0.19);
	$bezier_easeInQuart: cubic-bezier(0.895, 0.03, 0.685, 0.22);
	$bezier_easeInQuint: cubic-bezier(0.755, 0.05, 0.855, 0.06);
	$bezier_easeInSine: cubic-bezier(0.47, 0, 0.745, 0.715);
	$bezier_easeInExpo: cubic-bezier(0.95, 0.05, 0.795, 0.035);
	$bezier_easeInCirc: cubic-bezier(0.6, 0.04, 0.98, 0.335);
	$bezier_easeInBack: cubic-bezier(0.6, -0.28, 0.735, 0.045);
	$easeOutQuad: cubic-bezier(0.25, 0.46, 0.45, 0.94);

	$enable-grid-classes: true !default;

	// Grid breakpoints
	//
	// Define the minimum dimensions at which your layout will change,
	// adapting to different screen sizes, for use in media queries.

	$grid-breakpoints: (
		sm: 576px,
		md: 968px,
		lg: 1260px,
		xl: 1660px
	);

	// Grid containers
	//
	// Define the maximum width of `.container` for different screen sizes.

	$container-max-widths: (
		sm: 100%,
		md: 100%,
		lg: 98%,
		xl: 1600px
	);

	// Grid columns
	//
	// Set the number of columns and specify the width of the gutters.

	$grid-columns: 12;
	$grid-gutter-width: 30px;

	//MIXINS
	//Main font
	@mixin nunito($weight) {
		font-family: "Nunito", sans-serif;
		font-style: normal;
		font-weight: $weight;
	}

	@mixin open_sans($weight) {
		font-family: "Open Sans", sans-serif;
		font-style: normal;
		font-weight: $weight;
	}

	@mixin sintony($weight) {
		font-family: "Sintony", sans-serif;
		font-style: normal;
		font-weight: $weight;
	}

	@mixin source_sans_pro($weight) {
		font-family: "Source Sans Pro", sans-serif;
		font-style: normal;
		font-weight: $weight;
	}

	@mixin clearfix {
		&::after {
			clear: both;
			content: "";
			display: block;
		}
	}

	@mixin transition($speed, $time) {
		transition-duration: $speed;
		transition-timing-function: $time;
	}

	@mixin shadow($level: 1) {
		@if $level==0 {
			box-shadow: 0px 0px 0px 0px transparent;
		} @else if $level==1 {
			box-shadow: 0px 5px 40px -14px $c-black;
		} @else if $level==2 {
			box-shadow: 0px 5px 16px -7px $c-black;
		} @else if $level==3 {
			box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19),
				0 6px 6px rgba(0, 0, 0, 0.23);
		} @else if $level==4 {
			box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25),
				0 10px 10px rgba(0, 0, 0, 0.22);
		} @else if $level==5 {
			box-shadow: 0 19px 38px rgba(0, 0, 0, 0.3),
				0 15px 12px rgba(0, 0, 0, 0.22);
		} @else if $level==6 {
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.11);
		}
	}

	@mixin media($breakpoint) {
		@if $breakpoint==sm {
			@media screen and(max-width: map-get($grid-breakpoints, sm) - 1) {
				@content;
			}
		}
		@if $breakpoint==md {
			@media screen and(max-width: map-get($grid-breakpoints, md) - 1) {
				@content;
			}
		}
		@if $breakpoint==desk {
			@media screen and(max-width: map-get($grid-breakpoints, lg) - 1) {
				@content;
			}
		}
	}

	@mixin media_min($breakpoint) {
		@if $breakpoint==phone {
			@media screen and(min-width: map-get($grid-breakpoints, sm) - 1) {
				@content;
			}
		}
		@if $breakpoint==tab {
			@media screen and(min-width: map-get($grid-breakpoints, md) - 1) {
				@content;
			}
		}
		@if $breakpoint==desk {
			@media screen and(min-width: map-get($grid-breakpoints, lg) - 1) {
				@content;
			}
		}
	}

	//GRIDS
	/// Grid system
	//
	// Generate semantic grid columns with these mixins.
	@mixin make-container() {
		width: 100%;
		padding-right: ($grid-gutter-width + 15);
		padding-left: ($grid-gutter-width + 15);
		margin-right: auto;
		margin-left: auto;
		@include clearfix();
	}

	// For each breakpoint, define the maximum width of the container in a media query
	@mixin make-container-max-widths(
		$max-widths: $container-max-widths,
		$breakpoints: $grid-breakpoints
	) {
		@each $breakpoint, $container-max-width in $max-widths {
			@include media-breakpoint-up($breakpoint, $breakpoints) {
				max-width: $container-max-width;
			}
		}
	}

	@mixin make-row() {
		// display: flex;
		// flex-wrap: wrap;
		margin-right: ($grid-gutter-width / -2);
		margin-left: ($grid-gutter-width / -2);
		@include clearfix();
	}

	@mixin make-col-ready() {
		position: relative;
		// Prevent columns from becoming too narrow when at smaller grid tiers by
		// always setting `width: 100%;`. This works because we use `flex` values
		// later on to override this initial width.

		width: 100%;
		min-height: 1px; // Prevent collapsing
		padding-right: ($grid-gutter-width / 2);
		padding-left: ($grid-gutter-width / 2);
	}

	@mixin make-col($size, $columns: $grid-columns) {
		// flex: 0 0 percentage($size / $columns);
		// Add a `max-width` to ensure content within each column does not blow out
		// the width of the column. Applies to IE10+ and Firefox. Chrome and Safari
		// do not appear to require this.
		max-width: percentage($size / $columns);
	}

	@mixin make-col-offset($size, $columns: $grid-columns) {
		$num: $size / $columns;
		margin-left: if($num==0, 0, percentage($num));
	}

	//GRID FRAMEWORK

	// Framework grid generation
	//
	// Used only by Bootstrap to generate the correct number of grid classes given
	// any value of `$grid-columns`.

	@mixin make-grid-columns(
		$columns: $grid-columns,
		$gutter: $grid-gutter-width,
		$breakpoints: $grid-breakpoints
	) {
		// Common properties for all breakpoints
		%grid-column {
			position: relative;
			float: left;
			width: 100%;
			min-height: 1px; // Prevent columns from collapsing when empty
			padding-right: ($gutter / 2);
			padding-left: ($gutter / 2);
		}

		@each $breakpoint in map-keys($breakpoints) {
			$infix: breakpoint-infix($breakpoint, $breakpoints);

			// Allow columns to stretch full width below their breakpoints
			@for $i from 1 through $columns {
				.col#{$infix}-#{$i} {
					@extend %grid-column;
				}
			}
			.col#{$infix},
			.col#{$infix}-auto {
				@extend %grid-column;
			}

			@include media-breakpoint-up($breakpoint, $breakpoints) {
				// Provide basic `.col-{bp}` classes for equal-width flexbox columns
				.col#{$infix} {
					// flex-basis: 0;
					// flex-grow: 1;
					max-width: 100%;
				}
				.col#{$infix}-auto {
					// flex: 0 0 auto;
					width: auto;
					max-width: none; // Reset earlier grid tiers
				}

				@for $i from 1 through $columns {
					.col#{$infix}-#{$i} {
						@include make-col($i, $columns);
					}
				}

				// .order#{$infix}-first { order: -1; }

				// .order#{$infix}-last { order: $columns + 1; }

				// @for $i from 0 through $columns {
				//   .order#{$infix}-#{$i} { order: $i; }
				// }

				// `$columns - 1` because offsetting by the width of an entire row isn't possible
				@for $i from 0 through ($columns - 1) {
					@if not($infix == "" and $i == 0) {
						// Avoid emitting useless .offset-0
						.offset#{$infix}-#{$i} {
							@include make-col-offset($i, $columns);
						}
					}
				}
			}
		}
	}

	//BREAKPOINTS

	// Breakpoint viewport sizes and media queries.
	//
	// Breakpoints are defined as a map of (name: minimum width), order from small to large:
	//
	//    (xs: 0, sm: 576px, md: 768px, lg: 992px, xl: 1200px)
	//
	// The map defined in the `$grid-breakpoints` global variable is used as the `$breakpoints` argument by default.
	// Name of the next breakpoint, or null for the last breakpoint.
	//
	//    >> breakpoint-next(sm)
	//    md
	//    >> breakpoint-next(sm, (xs: 0, sm: 576px, md: 768px, lg: 992px, xl: 1200px))
	//    md
	//    >> breakpoint-next(sm, $breakpoint-names: (xs sm md lg xl))
	//    md
	@function breakpoint-next(
		$name,
		$breakpoints: $grid-breakpoints,
		$breakpoint-names: map-keys($breakpoints)
	) {
		$n: index($breakpoint-names, $name);
		@return if(
			$n < length($breakpoint-names),
			nth($breakpoint-names, $n + 1),
			null
		);
	}

	// Minimum breakpoint width. Null for the smallest (first) breakpoint.
	//
	//    >> breakpoint-min(sm, (xs: 0, sm: 576px, md: 768px, lg: 992px, xl: 1200px))
	//    576px
	@function breakpoint-min($name, $breakpoints: $grid-breakpoints) {
		$min: map-get($breakpoints, $name);
		@return if($min !=0, $min, null);
	}

	// Maximum breakpoint width. Null for the largest (last) breakpoint.
	// The maximum value is calculated as the minimum of the next one less 0.02px
	// to work around the limitations of `min-` and `max-` prefixes and viewports with fractional widths.
	// See https://www.w3.org/TR/mediaqueries-4/#mq-min-max
	// Uses 0.02px rather than 0.01px to work around a current rounding bug in Safari.
	// See https://bugs.webkit.org/show_bug.cgi?id=178261
	//
	//    >> breakpoint-max(sm, (xs: 0, sm: 576px, md: 768px, lg: 992px, xl: 1200px))
	//    767.98px
	@function breakpoint-max($name, $breakpoints: $grid-breakpoints) {
		$next: breakpoint-next($name, $breakpoints);
		@return if($next, breakpoint-min($next, $breakpoints) - 0.02px, null);
	}

	// Returns a blank string if smallest breakpoint, otherwise returns the name with a dash infront.
	// Useful for making responsive utilities.
	//
	//    >> breakpoint-infix(xs, (xs: 0, sm: 576px, md: 768px, lg: 992px, xl: 1200px))
	//    ""  (Returns a blank string)
	//    >> breakpoint-infix(sm, (xs: 0, sm: 576px, md: 768px, lg: 992px, xl: 1200px))
	//    "-sm"
	@function breakpoint-infix($name, $breakpoints: $grid-breakpoints) {
		@return if(breakpoint-min($name, $breakpoints) ==null, "", "-#{$name}");
	}

	// Media of at least the minimum breakpoint width. No query for the smallest breakpoint.
	// Makes the @content apply to the given breakpoint and wider.
	@mixin media-breakpoint-up($name, $breakpoints: $grid-breakpoints) {
		$min: breakpoint-min($name, $breakpoints);
		@if $min {
			@media (min-width: $min) {
				@content;
			}
		} @else {
			@content;
		}
	}

	// Media of at most the maximum breakpoint width. No query for the largest breakpoint.
	// Makes the @content apply to the given breakpoint and narrower.
	@mixin media-breakpoint-down($name, $breakpoints: $grid-breakpoints) {
		$max: breakpoint-max($name, $breakpoints) + 1;
		@if $max {
			@media (max-width: $max) {
				@content;
			}
		} @else {
			@content;
		}
	}

	// Media that spans multiple breakpoint widths.
	// Makes the @content apply between the min and max breakpoints
	@mixin media-breakpoint-between(
		$lower,
		$upper,
		$breakpoints: $grid-breakpoints
	) {
		$min: breakpoint-min($lower, $breakpoints);
		$max: breakpoint-max($upper, $breakpoints);
		@if $min !=null and $max !=null {
			@media (min-width: $min) and (max-width: $max) {
				@content;
			}
		} @else if $max==null {
			@include media-breakpoint-up($lower, $breakpoints) {
				@content;
			}
		} @else if $min==null {
			@include media-breakpoint-down($upper, $breakpoints) {
				@content;
			}
		}
	}

	// Media between the breakpoint's minimum and maximum widths.
	// No minimum for the smallest breakpoint, and no maximum for the largest one.
	// Makes the @content apply only to the given breakpoint, not viewports any wider or narrower.
	@mixin media-breakpoint-only($name, $breakpoints: $grid-breakpoints) {
		$min: breakpoint-min($name, $breakpoints);
		$max: breakpoint-max($name, $breakpoints);
		@if $min !=null and $max !=null {
			@media (min-width: $min) and (max-width: $max) {
				@content;
			}
		} @else if $max==null {
			@include media-breakpoint-up($name, $breakpoints) {
				@content;
			}
		} @else if $min==null {
			@include media-breakpoint-down($name, $breakpoints) {
				@content;
			}
		}
	}

	h1 {
		font-size: 38px;
		font-weight: 400;
		line-height: normal;
		color: $gray_tuna;
		padding: 15px 0;
	}
</style>
