<template>
	<div class="settings__page dashboard">
		<Header v-if="$device.isDesktop"/>
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<div class="container">
			<div class="dashboard_content">
				<DashboardAside/>
				<fieldset>
					<div class="breadcrumb">
						<ul>
							<li>Edit My Profile</li>
						</ul>
					</div>
					<div class="fieldset__container">
						<div class="fieldset__col">
							<div class="fieldset__box_user">
								<header>
									<h4>Basic information</h4>
								</header>
								<div class="user">
									<div
										v-if="user.photo"
										class="user_image"
										:style="{ backgroundImage: 'url('+ user.photo + ')' }"
									></div>
									<div v-else class="user_image"></div>
									<label>
										<p>Edit image</p>
										<input type="file" accept="image" @change="previewImage">
									</label>
								</div>
								<div class="basic_info">
									<div class="form_group form_group--half">
										<label>First Name</label>
										<input type="text" v-model="user.firstName">
									</div>
									<div class="form_group form_group--half">
										<label for>Last Name</label>
										<input type="text" v-model="user.lastName">
									</div>
									<div class="form_group">
										<label for>Email</label>
										<input type="email" :value="user.email" disabled>
									</div>
									<div class="form_group location form_group--half">
										<label for>
											Location
											<i class="icon-location"></i>
										</label>
										<input
											type="text"
											autocomplete="off"
											placeholder="City, Zip code"
											@click="openDropdown($event.target)"
											v-model="user.locationText"
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
																v-model="user.locationText"
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
															@click="user.location = item.id"
															v-model="user.locationText"
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
									<div class="form_group form_group--half">
										<label>User Name</label>
										<input type="text" v-model="user.userName">
									</div>
								</div>
							</div>
						</div>
						<aside class="fieldset__col">
							<div class="trading_status">
								<button
									class="btn btn__primary"
									type="button"
									role="button"
									@click="updateProfile"
									aria-label="Save"
								>Save</button>
								<h3>Currently trading?</h3>
								<div class="btn__switch" data-tooltip="Coming Soon!">
									<label>
										<input type="checkbox" disabled value="None" name="check">
										<span>Yes</span>
										<span>No</span>
										<span class="toggle"></span>
									</label>
								</div>
							</div>
							<hr>
							<h3>Profile link</h3>
							<div class="link">
								<i @click="copyToClipboard" class="icon-link"></i>
								<input type="text" :value="link" id="linkText" readonly>
								<button type="button" role="button" @click="copyToClipboard">Copy</button>
							</div>
						</aside>
					</div>
					<div class="fieldset__container">
						<div class="fieldset__col">
							<div class="fieldset__box">
								<header>
									<h4>Profile Description</h4>
								</header>

								<div class="box__inner">
									<textarea v-model="user.description"></textarea>
								</div>
							</div>
							<!--<div class="fieldset__box">-->
							<!--<button type="button" role="button" class="btn btn__secondary btn_action">Edit</button>-->
							<!--<div class="box__inner">-->
							<!--<div class="service">-->
							<!--<h4>Service</h4>-->
							<!--<img src="~/assets/img/dashboard/service.png" alt="yellow lightning icon">-->
							<!--<p>-->
							<!--Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod-->
							<!--tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,-->
							<!--quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo-->
							<!--consequat.Lorem ipsum dolor sit amet-->
							<!--</p>-->
							<!--</div>-->
							<!--<div class="products">-->
							<!--<h4>Products</h4>-->
							<!--<img src="~/assets/img/dashboard/products.png" alt="blue box icon">-->
							<!--<ul>-->
							<!--<li>-->
							<!--Lorem ipsum dolor sit amet, consectetur adipisicing-->
							<!--</li>-->
							<!--<li>-->
							<!--Elit, sed do eiusmod tempor incididunt ut labore et-->
							<!--</li>-->
							<!--<li>-->
							<!--Dolore magna aliqua. Ut enim ad minim veniam, quis-->
							<!--</li>-->
							<!--<li>-->
							<!--Nostrud exercitation ullamco laboris nisi ut aliquip-->
							<!--</li>-->
							<!--</ul>-->
							<!--</div>-->
							<!--</div>-->
							<!--</div>-->
							<!--<div class="fieldset__box">-->
							<!--<button type="button" role="button" class="btn btn__secondary btn_action">Update-->
							<!--</button>-->
							<!--<header>-->
							<!--<h4>Interested in trading for…</h4>-->
							<!--</header>-->
							<!--<ul class="trade_items_list">-->
							<!--<li class="sporting_goods">-->
							<!--<div class="cat_bg">-->
							<!--<img src="~/assets/img/illustrations/sporting-goods.svg"-->
							<!--alt="sporting goods">-->
							<!--</div>-->
							<!--<p>-->
							<!--<strong>Slingshot Asylum Kiteboard 2018</strong>-->
							<!--</p>-->
							<!--</li>-->
							<!--<li class="experiences">-->
							<!--<div class="cat_bg">-->
							<!--<img src="~/assets/img/illustrations/experiences.svg" alt="experiences">-->
							<!--</div>-->
							<!--<p>-->
							<!--<strong>Tickets to Hamilton</strong> in-->
							<!--<strong>Seattle, WA</strong>-->
							<!--</p>-->
							<!--</li>-->
							<!--<li class="services">-->
							<!--<div class="cat_bg">-->
							<!--<img src="~/assets/img/illustrations/services.svg" alt="services">-->
							<!--</div>-->
							<!--<p>Lanscaping services</p>-->
							<!--</li>-->
							<!--<li class="homes_housing">-->
							<!--<div class="cat_bg">-->
							<!--<img src="~/assets/img/illustrations/homes-housing.svg" alt="homes housing">-->
							<!--</div>-->
							<!--<p>-->
							<!--<strong>Vacation homes</strong> in-->
							<!--<strong>Hood River, OR</strong>-->
							<!--</p>-->
							<!--</li>-->
							<!--<li class="sporting_goods">-->
							<!--<div class="cat_bg">-->
							<!--<img src="~/assets/img/illustrations/sporting-goods.svg"-->
							<!--alt="sporting goods">-->
							<!--</div>-->
							<!--<p>-->
							<!--<strong>Santa Cruz Mountain Bikes</strong>-->
							<!--</p>-->
							<!--</li>-->
							<!--</ul>-->
							<!--</div>-->
							<div class="fieldset__box">
								<header>
									<h4>Listings for trade ({{userListings.length}})</h4>
								</header>
								<ul class="trade_listings_profile">
									<li v-for="listing in userListings">
										<div
											class="product_image"
											v-if="listing.photoName"
											:style="{ backgroundImage: 'url('+ listing.mainPhotoUrl+ ')' }"
										></div>
										<div class="product_image placeholder_image" v-else></div>
										<div class="details">
											<p class="title">
												{{listing.title}}
												<span class="price">${{listing.price}}</span>
											</p>
											<p>Creation Date: {{formatDate(listing.dateAdded.date)}}</p>
											<p>Expiration Date: {{formatDate(listing.availability.date)}}</p>
											<p>
												Status:
												<span
													v-if="listing.status == 1 && !listingAvailable(listing.availability)"
													:class="listingStatusColor[0]"
												>Expired</span>
												<span v-else :class="listingStatusColor[listing.status]"> {{listingStatus[listing.status]}}</span>
											</p>
										</div>
										<div class="listing_actions">
											<button  type="button" role="button" class="btn btn__primary"
													 v-if="listing.status == 1 && !listingAvailable(listing.availability)"
													 @click="repostListing(listing)">
												Repost
											</button>
											<button  type="button" role="button" class="btn btn__primary"
													 v-else  disabled>
												Repost
											</button>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</fieldset>
			</div>
		</div>
	</div>
</template>

<script>
	import Header from "~/components/Header";
	import HeaderMobile from "~/components/HeaderMobile";
	import DashboardAside from "~/components/DashboardAside";
	import { mapGetters } from "vuex";
	import editProfile from "~/assets/js/editProfile.js";

	export default {
		middleware: "auth",
		components: {
			Header,
			HeaderMobile,
			DashboardAside
		},
		head: {
			title: "Edit my profile"
		},
		computed: {
			...mapGetters("auth", ["isAuthenticated", "loggedInUser"])
		},
		extends: editProfile,
		data() {
			return {
				link: '',
				listingStatus: {
					1: "Active",
					2: "Inactive",
					3: "Traded"
				},
				listingStatusColor: {
					0: "status_danger",
					1: "status_success",
					2: "status_danger",
					3: "status_danger"
				},
				profilePhoto: null,
				browserLocation:
					{
						set: false,
						latitude: "",
						longitude: "",
						location: false
					},
				recentSearched: [],
				states: [],
			};
		},
		async asyncData({ store }) {
			let response = await store.dispatch("apiCalls/getMyProfile");
			return { user: response.user, userListings: response.userListings };
		},
		mounted() {
			this.currentTime();
			this.link = window.location.protocol + "//" + window.location.hostname + "/view-profile/" + this.$auth.user.id;
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

	.fieldset__container {
		justify-content: space-between;
	}

	.trade_listings_profile {
		padding: 24px 34px;

		.details {
			padding: 5px 0;

			p {
				margin-top: 0;
			}
		}

		.status_success {
			color: $shamrock_darker;
		}

		.status_danger {
			color: $valencia;
		}

		li {
			display: flex;
			align-items: center;
			background: $gray_albaster;
			border-radius: 3px;
			border: 1px solid rgba($gray_aluminium, 0.7);
			position: relative;
			padding-right: 240px;
			margin-bottom: 9px;

			.product_image {
				width: 134px;
				height: 134px;
				margin-right: 20px;
				background-size: cover;
				background-repeat: no-repeat;
				background-position: center;
			}

			.price {
				display: block;
				width: 100%;
				font-weight: 700;
				margin-bottom: 7px;
				font-size: 12px;
				color: $gray_aluminium;
			}

			.title {
				font-size: 14px;
				margin-bottom: 3px;
				color: $black;
				letter-spacing: 0;
			}

			p {
				font-size: 12px;
				line-height: normal;
				margin-bottom: 2px;
				color: $gray_aluminium;
				letter-spacing: 0.9px;
			}

			.listing_actions {
				position: absolute;
				top: 17px;
				right: 18px;
				display: flex;
				align-items: center;
			}
		}
	}

	.fieldset__box {
		position: relative;

		.btn_action {
			position: absolute;
			top: 17px;
			right: 18px;
			padding: 12px 25px 10px;
		}
	}

	.trading_status {
		.btn {
			width: 100%;
			display: block;
			margin-bottom: 25px;
		}
	}

	.box__inner {
		@include clearfix;
		padding: 30px 35px;

		textarea {
			height: 200px;
		}

		.service {
			padding-right: 20px;
		}

		.service,
		.products {
			@include clearfix;
			float: left;
			width: 50%;
			padding-top: 10px;

			img {
				float: left;
				margin-right: 10px;
			}

			ul,
			p {
				float: left;
				width: calc(100% - 55px);
			}

			ul {
				list-style: disc;
				padding-left: 25px;

				li {
					font-size: 12px;
					line-height: normal;
					color: $gray_aluminium;
					margin-bottom: 5px;
					padding-left: 10px;
				}
			}
		}

		h4 {
			margin-bottom: 18px;
			clear: both;
		}

		p {
			font-size: 12px;
			color: $gray_aluminium;
			line-height: normal;
		}
	}

	.fieldset__col {
		width: calc(100% - 375px);
	}

	aside.fieldset__col {
		width: 362px;

		h3 {
			margin-bottom: 11px;
		}

		hr {
			border: none;
			height: 1px;
			background: $gray_aluminium;
			margin: 24px 0;
		}

		.fieldset__box {
			@include clearfix;
			padding: 31px 30px 29px;

			h3 {
				margin-bottom: 29px;
			}

			.btn {
				min-width: 136px;
			}
		}

		.partners {
			li {
				display: flex;
				align-items: center;
				margin-bottom: 20px;
			}

			.user_image {
				width: 40px;
				height: 40px;
				margin-right: 20px;
			}

			p {
				font-size: 12px;
				line-height: 16px;
				color: $gray_tuna;
			}
		}
	}

	.fieldset__box {
		width: 100%;
		margin-bottom: 18px;

		header {
			font-size: 18px;
			line-height: 24px;
			color: $gray_aluminium;
			padding: 38px 30px 31px;
			border-bottom: 1px solid rgba($gray_aluminium, 0.25);
		}

		h4 {
			font-size: 18px;
			line-height: 24px;
			color: $gray_aluminium;
		}
	}

	.fieldset__box_user {
		background: $white;
		margin-bottom: 14px;
		display: flex;
		flex-wrap: wrap;
		align-items: flex-start;
		padding: 24px 25px 30px;

		header {
			width: 100%;
			margin-bottom: 25px;
		}

		h4 {
			font-size: 18px;
			line-height: 24px;
			color: $gray_aluminium;
		}

		p {
			small {
				display: block;
			}
		}

		.user {
			min-width: 102px;
			width: 102px;
			margin-right: 20px;

			.user_image {
				margin: 0 auto 10px;
			}

			label {
				input {
					position: absolute;
					z-index: -9;
					opacity: 0;
					visibility: hidden;
				}

				p {
					font-size: 12px;
					line-height: 18px;
					color: $blue_scooter;
					text-align: center;
				}
			}
		}

		.basic_info {
			width: calc(100% - 136px);
			display: flex;
			flex-wrap: wrap;
			justify-content: space-between;

			input[type="text"],
			input[type="email"],
			input[type="password"] {
				width: 100%;
				display: block;
			}

			.location_dropdown {
				label {
					position: static;
					transform: none;
					display: block;
					width: 100%;
					padding: 2.5px 0;
					font-size: 12px;
					line-height: 23px;
					letter-spacing: 0;
					color: $gray_tuna;
					text-transform: capitalize;
					cursor: pointer;
					margin-bottom: 0;
				}
			}

			.form_group {
				width: 100%;
				margin-bottom: 15px;
				position: relative;

				&--half {
					width: 48%;
				}

				p {
					font-size: 11px;
					color: $gray_aluminium;
					padding: 10px 0 15px;
				}
			}

			.location {
				input {
					padding-left: 35px;
				}
			}

			label {
				font-size: 11px;
				display: block;
				letter-spacing: 2px;
				text-transform: uppercase;
				color: $gray_aluminium;
				margin-bottom: 10px;

				i {
					position: absolute;
					top: 35px;
					left: 13px;
					font-size: 18px;
					color: $gray_aluminium;
				}
			}
		}
	}

	.extra {
		display: flex;
		padding: 30px 55px 25px;
		background: $gray_albaster;

		li {
			display: flex;
			align-items: center;
			margin-right: 20px;
		}

		i {
			font-size: 25px;
			margin-right: 15px;
		}

		.icon-location {
			color: $blue_scooter;
			font-size: 44px;
		}

		.icon-eye {
			font-size: 40px;
			color: $shamrock_darker;
		}

		.icon-save {
			color: $saffron;
			font-size: 42px;
		}

		.icon-calendar {
			font-size: 35px;
			color: $valencia;
		}

		p {
			font-size: 26px;
			line-height: normal;
			color: $gray_tuna;
			min-width: 100px;

			small {
				font-size: 10px;
				display: block;
				color: $gray_aluminium;
				letter-spacing: 2px;
				text-transform: uppercase;
			}
		}
	}

	.user_image {
		width: 102px;
		height: 102px;
		border-radius: 50%;
		background-size: cover;
		background-repeat: no-repeat;
		background-position: center;
		margin-right: 24px;
	}

	.trade_items_list {
		padding: 32px 35px;
	}

	.btn__switch {
		height: 35px;

		label {
			background: $white;
			padding: 8px 11px;
		}

		.toggle {
			width: 25px;
			height: 25px;
		}
	}

	.link {
		position: relative;

		.icon-link {
			position: absolute;
			left: 12px;
			top: 15px;
			font-size: 22px;
			color: $gray_tuna;
		}

		input[type="text"] {
			border: 1px solid $gray_aluminium;
			border-radius: 5px;
			font-size: 12px;
			color: $gray_tuna;
			height: 51px;
			line-height: normal;
			padding: 0 10px 0 45px;
			margin-bottom: 10px;
			background: transparent;
		}

		button {
			font-size: 12px;
			color: $blue_scooter;
		}
	}
</style>
