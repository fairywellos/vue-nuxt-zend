<template>
	<div class="product_gallery">
		<div v-if="parseInt(sample_account) === 1" class="sample_tag">
			Sample account
		</div>
		<div class="button_group">
			<label>
				<input type="checkbox">
				<i class="icon-save"></i> Watch item
			</label>
		</div>
		<div class="product_gallery_slider">
			<div v-for="photo in galleryImages" :key="photo.key">
				<img :src="photo.url" :alt="photo.name">
			</div>
		</div>
	</div>
</template>

<script>
import axios from "axios";
import { mapGetters } from "vuex";

export default {
	props: ["galleryImages", "sample_account"],
	methods: {
		gallerySliderInit() {
			//slider init
			
			let slider = tns({
				container: '.product_gallery_slider',
				items: 1,
				autoHeight: true,
				slideBy: 1,
				loop: true,
				speed: 250,
				animateIn: "tns_slide_in",
				animateOut: "tns_slide_out",
				mouseDrag: true,
				autoplayButtonOutput: false
			});
		}
	},
	computed: {
		...mapGetters("auth", ["isAuthenticated", "loggedInUser"])
	},
	mounted() {
		this.gallerySliderInit();
	}
};
</script>

<style lang="scss">
@import "~assets/scss/abstracts/_abstracts.scss";

.listing_owner {
	.product_gallery {
		.button_group {
			display: none;
		}
	}
}
.product_gallery {
	position: relative;
	padding: 0 0 24px;
	margin-top: 25px;
	
	.sample_tag {
		position: absolute;
		left: 18px;
		top: 18px;
		border-radius: 3px;
		background: rgba($white, .6);
		text-align: center;
		padding: 6px 8px;
		font-size: 11px;
		line-height: 16px;
		color: $gray_tuna;
		text-transform: uppercase;
		z-index: 20;
	}

	.tns-item {
		position: relative;

		img {
			width: 100%;
			margin: 0 auto;
		}
	}

	.tns_slide_in {
		transition: 0.25s $bezier_ease_in;
	}

	.tns_slide_out {
		transition: 0.25s $bezier_ease_out;
	}

	.button_group {
		position: absolute;
		display: flex;
		top: 18px;
		right: 18px;
		z-index: 20;
		
		input[type=checkbox] {
			position: absolute;
			z-index: -9;
			opacity: 0;
			visibility: hidden;
			
			&:checked {
			& +	i {
					color: $saffron;
				}
			}
		}

		label {
			background: rgba($white, 0.6);
			color: $gray_tuna;
			font-size: 12px;
			padding: 12.5px 20px 14.5px 25px;
			border-radius: 3px;
			margin-left: 16px;
			position: relative;
			cursor: pointer;
			min-width: 130px;
			text-align: right;
		}

		i {
			position: absolute;
			top: 50%;
			left: 18px;
			transform: translateY(-50%);
			color: $gray_tuna;
			font-size: 16px;
			margin-right: 5px;
		}
	}

	button[data-controls="prev"] {
		left: 0;
		padding-left: 13px;

		&:before {
			width: 14px;
			height: 14px;
		}
	}

	button[data-controls="next"] {
		right: 0;
		padding-right: 13px;

		&:before {
			width: 14px;
			height: 14px;
		}
	}
}

.product_gallery_slider {
	overflow: hidden;

	div {
		opacity: 0;
		transition: 0.2s $bezier_ease_in_out;
	}

	&.tns-slider {
		overflow: visible;

		div {
			opacity: 1;
		}
	}
}
</style>
