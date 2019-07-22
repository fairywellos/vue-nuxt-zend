<template>
    <div class="settings__page dashboard">
       <Header v-if="$device.isDesktop" />
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
        <div class="container">
            <div class="dashboard_content">
                <DashboardAside/>
                <fieldset>
                    <div class="breadcrumb">
                        <ul>
                            <li>Account settings</li>
                        </ul>
                    </div>
                    <div class="fieldset__container">
                        <div class="settings_content__item">
                            <header>My profile</header>
                            <h4>Visibility</h4>
                            <multiselect v-model="valueProfile" :options="profileOptions" @input="updateUserStatus"></multiselect>
                            <h4>Search engine privacy</h4>
                            <div class="custom_radio">
                                <input type="radio" name="group1" checked id="showFullNameP"
										value="full" v-model="valueUsername" @change="updateUserPrivacy">
                                <label for="showFullNameP">
                                    <div class="checkbox"></div>
                                    Show my full name (e.g. John Smith)
                                </label>
                            </div>
                            <div class="custom_radio">
                                <input type="radio" name="group1" id="showOnlyUserNameP"
									   value="username" v-model="valueUsername" @change="updateUserPrivacy">
                                <label for="showOnlyUserNameP">
                                    <div class="checkbox"></div>
                                    Show only my username (e.g. js1987.)
                                </label>
                            </div>
                        </div>
                        <!--<div class="settings_content__item">-->
                            <!--<header>Notifications</header>-->
                            <!--<h4>Visibility</h4>-->
                            <!--<multiselect v-model="valueNotifications" :options="notificationsOptions"></multiselect>-->
                            <!--<h4>Search engine privacy</h4>-->
                            <!--<div class="custom_radio">-->
                                <!--<input type="radio" checked name="group2" id="showFullNameN">-->
                                <!--<label for="showFullNameN">-->
                                    <!--<div class="checkbox"></div>-->
                                    <!--Show my full name (e.g. John Smith)-->
                                <!--</label>-->
                            <!--</div>-->
                            <!--<div class="custom_radio">-->
                                <!--<input type="radio" name="group2" id="showOnlyUserNameN">-->
                                <!--<label for="showOnlyUserNameN">-->
                                    <!--<div class="checkbox"></div>-->
                                    <!--Show only my username (e.g. js1987.)-->
                                <!--</label>-->
                            <!--</div>-->
                        <!--</div>-->
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</template>

<script>
    import Multiselect from "vue-multiselect";
	import Header from "~/components/Header";
	import HeaderMobile from "~/components/HeaderMobile";
    import DashboardAside from "~/components/DashboardAside";
	import { mapGetters } from "vuex";

	const PRIVACY = {
		1: "full",
		2: "username"
	};

	const PUBLIC = {
		1: "Public",
		2: "Private"
	};

	export default {
		middleware: 'auth',
        components: {
            Multiselect,
			Header,
			HeaderMobile,
            DashboardAside
        },
        head: {
            title: "Account settings"
        },
        data() {
            return {
                valueProfile: PUBLIC[this.$auth.user.public],
                profileOptions: ["Public", "Private"],
                valueNotifications: null,
                notificationsOptions: ["Public", "Private"],
				valueUsername: PRIVACY[this.$auth.user.privacy]
            };
        },
		computed: {
			...mapGetters("auth", ["isAuthenticated", "loggedInUser"])
		},
		methods: {
			updateUserPrivacy(){
				this.$axios.post("user/update-privacy",{
					status: this.valueUsername
				}).then((response) => {
					this.$auth.fetchUser();
				})
			},
			updateUserStatus(){
				this.$axios.post("user/update-public",{
					status: this.valueProfile
				}).then((response) => {
					this.$auth.fetchUser();
				})
			}
		}
    };
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style lang="scss" scoped>
	//VARIABLES
	// Colors

	$white: #fff;
	$black: #000;
	$blue_picton: #48C0EB;
	$blue_scooter: #32AEDC;
	$gray_iron: #DBDCDF;
	$gray_albaster: #FCFCFC;
	$gray_ghost: #C6C9CF;
	$gray_aluminium: #AAAEB3;
	$gray_aluminium2: #ECEDEF;
	$gray_tuna: #393C40;
	$gray_alto: #D8D8D8;
	$gray_gallery: #EAEAEA;
	$shark: #27292B;
	$shamrock_darker: #2DBD9B;
	$mine_shaft: #252424;
	$valencia: #DC4250;
	$persimmon: #FF5A5A;
	$saffron: #F7BC31;
	$shamrock: #3FD0AD;
	$bright_sun: #FFCF47;
	$tundora: #4A4A4A;
	$hot_pink: #FF689A;

	$gray_shark: #202224;
	$gray_abbey: #474B4F;
	$gray_pale_sky: #6F7379;
	$java: #1DE9B6;
	$keppel: #37BC9B;
	$carnation: #EF5362;

	// Gradients
	$gradient_blue: linear-gradient(225deg, #16b2ec 0%, #4b9cb8 100%);
	$gradient_yellow: linear-gradient(225deg, #ffcc55 0%, #edb107 100%);
	$gradient_red: linear-gradient(225deg, #ffcc55 0%, #edb107 100%);
	$gradient_green: linear-gradient(225deg, #5dd1b5 0%, #1a9f80 100%);

	$bezier-1: cubic-bezier(0.65, 0.05, 0.36, 1);
	$bezier-2: cubic-bezier(0.1, 0.88, 0.3, 0.98);
	$bezier_linear: cubic-bezier(0.250, 0.250, 0.750, 0.750);
	$bezier_ease: cubic-bezier(0.250, 0.100, 0.250, 1.000);
	$bezier_ease_in: cubic-bezier(0.420, 0.000, 1.000, 1.000);
	$bezier_ease_out: cubic-bezier(0.000, 0.000, 0.580, 1.000);
	$bezier_ease_in_out: cubic-bezier(0.420, 0.000, 0.580, 1.000);
	$bezier_easeInQuad: cubic-bezier(0.550, 0.085, 0.680, 0.530);
	$bezier_easeInCubic: cubic-bezier(0.550, 0.055, 0.675, 0.190);
	$bezier_easeInQuart: cubic-bezier(0.895, 0.030, 0.685, 0.220);
	$bezier_easeInQuint: cubic-bezier(0.755, 0.050, 0.855, 0.060);
	$bezier_easeInSine: cubic-bezier(0.470, 0.000, 0.745, 0.715);
	$bezier_easeInExpo: cubic-bezier(0.950, 0.050, 0.795, 0.035);
	$bezier_easeInCirc: cubic-bezier(0.600, 0.040, 0.980, 0.335);
	$bezier_easeInBack: cubic-bezier(0.600, -0.280, 0.735, 0.045);
	$easeOutQuad: cubic-bezier(0.250, 0.460, 0.450, 0.940);

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
		font-family: 'Nunito', sans-serif;
		font-style: normal;
		font-weight: $weight;
	}

	@mixin open_sans($weight) {
		font-family: 'Open Sans', sans-serif;
		font-style: normal;
		font-weight: $weight;
	}

	@mixin sintony($weight) {
		font-family: 'Sintony', sans-serif;
		font-style: normal;
		font-weight: $weight;
	}

	@mixin source_sans_pro($weight) {
		font-family: 'Source Sans Pro', sans-serif;
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


	@mixin transition( $speed , $time ) {
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
			box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
		} @else if $level==4 {
			box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
		} @else if $level==5 {
			box-shadow: 0 19px 38px rgba(0, 0, 0, 0.30), 0 15px 12px rgba(0, 0, 0, 0.22);
		} @else if $level==6 {
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.11);
		}
	}

	@mixin media($breakpoint) {
		@if $breakpoint==sm {
			@media screen and(max-width: map-get($grid-breakpoints, sm) - 1) {
				@content
			}
		;
		}
		@if $breakpoint==md {
			@media screen and(max-width: map-get($grid-breakpoints, md) - 1) {
				@content
			}
		;
		}
		@if $breakpoint==desk {
			@media screen and(max-width: map-get($grid-breakpoints, lg) - 1) {
				@content
			}
		;
		}
	}

	@mixin media_min($breakpoint) {
		@if $breakpoint==phone {
			@media screen and(min-width: map-get($grid-breakpoints, sm) - 1) {
				@content
			}
		;
		}
		@if $breakpoint==tab {
			@media screen and(min-width: map-get($grid-breakpoints, md) - 1) {
				@content
			}
		;
		}
		@if $breakpoint==desk {
			@media screen and(min-width: map-get($grid-breakpoints, lg) - 1) {
				@content
			}
		;
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
	@mixin make-container-max-widths($max-widths: $container-max-widths, $breakpoints: $grid-breakpoints) {
		@each $breakpoint,
		$container-max-width in $max-widths {
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

	@mixin make-grid-columns($columns: $grid-columns, $gutter: $grid-gutter-width, $breakpoints: $grid-breakpoints) {
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
					@if not ($infix == "" and $i == 0) { // Avoid emitting useless .offset-0
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
	@function breakpoint-next($name, $breakpoints: $grid-breakpoints, $breakpoint-names: map-keys($breakpoints)) {
		$n: index($breakpoint-names, $name);
		@return if($n < length($breakpoint-names), nth($breakpoint-names, $n + 1), null);
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
		@return if($next, breakpoint-min($next, $breakpoints) - .02px, null);
	}

	// Returns a blank string if smallest breakpoint, otherwise returns the name with a dash infront.
	// Useful for making responsive utilities.
	//
	//    >> breakpoint-infix(xs, (xs: 0, sm: 576px, md: 768px, lg: 992px, xl: 1200px))
	//    ""  (Returns a blank string)
	//    >> breakpoint-infix(sm, (xs: 0, sm: 576px, md: 768px, lg: 992px, xl: 1200px))
	//    "-sm"
	@function breakpoint-infix($name, $breakpoints: $grid-breakpoints) {
		@return if(breakpoint-min($name, $breakpoints)==null, "", "-#{$name}");
	}

	// Media of at least the minimum breakpoint width. No query for the smallest breakpoint.
	// Makes the @content apply to the given breakpoint and wider.
	@mixin media-breakpoint-up($name, $breakpoints: $grid-breakpoints) {
		$min: breakpoint-min($name, $breakpoints);
		@if $min {
			@media (min-width: $min) {
				@content;
			}
		}
		@else {
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
		}
		@else {
			@content;
		}
	}

	// Media that spans multiple breakpoint widths.
	// Makes the @content apply between the min and max breakpoints
	@mixin media-breakpoint-between($lower, $upper, $breakpoints: $grid-breakpoints) {
		$min: breakpoint-min($lower, $breakpoints);
		$max: breakpoint-max($upper, $breakpoints);
		@if $min !=null and $max !=null {
			@media (min-width: $min) and (max-width: $max) {
				@content;
			}
		}
		@else if $max==null {
			@include media-breakpoint-up($lower, $breakpoints) {
				@content;
			}
		}
		@else if $min==null {
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
		}
		@else if $max==null {
			@include media-breakpoint-up($name, $breakpoints) {
				@content;
			}
		}
		@else if $min==null {
			@include media-breakpoint-down($name, $breakpoints) {
				@content;
			}
		}
	}

	.fieldset__container {

        .settings_content__item {
            background: $white;
            border-radius: 5px;
            width: 391px;
            padding: 0 33px 25px;

            header {
                font-size: 18px;
                line-height: 24px;
                color: $gray_aluminium;
                padding: 38px 33px 30px;
                border-bottom: 1px solid rgba($gray_aluminium, 0.3);
                position: relative;
                margin-left: -33px;
                width: calc(100% + 66px);
                margin-bottom: 26px;
            }

            h4 {
                font-size: 10px;
                line-height: 13px;
                letter-spacing: 2px;
                color: $gray_aluminium;
                text-transform: uppercase;
                margin-bottom: 9px;
            }

            & + .settings_content__item {
                margin-left: 11px;
                width: calc(100% - 405px);
            }
        }

        .multiselect {
            margin-bottom: 28px;
            max-width: 170px;
        }

        .multiselect__placeholder {
            padding-top: 3px;
        }
    }
</style>
