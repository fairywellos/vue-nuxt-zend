<template>
	<div class="container">
		<div class="sponsored_listing">
			<ul class="sponsored_airbnb" :style="{ backgroundImage: `url(${bgShakingHands})` }">
				<li>
					<h4>Featured</h4>
					<img src="~/assets/img/homepage/airbnb_logo.jpg" alt="airbnb logo">
					<h3>Trading places? Trade what you’ve got for a great stay.</h3>
					<a href="#" class="btn btn__secondary">
						<span>Visit the airbnb shop</span>
					</a>
				</li>
				<li></li>
			</ul>
			<div class="sponsored_whale">
				<a href="#">
					<img src="~/assets/img/homepage/whale_wins.png" class="img_fluid" alt="whale wins image">
				</a>
			</div>
		</div>
	</div>
</template>

<script>
import bgShakingHands from "~/assets/img/homepage/shaking-hands.jpg";

export default {
	data() {
		return {
			bgShakingHands
		};
	}
};
</script>

<style lang="scss" scoped>
@import "~assets/scss/abstracts/_abstracts.scss";

.sponsored_listing {
	display: flex;
	flex-wrap: wrap;
	margin: 43px 0;

	h4 {
		@include nunito(200);
		font-size: 16px;
		line-height: 28px;
		color: $white;
		letter-spacing: -0.15px;
		opacity: 0.73;
	}

	h3 {
		@include source_sans_pro(900);
		font-size: 34px;
		line-height: 40px;
		letter-spacing: -0.31px;
	}

	.btn__secondary {
		border-color: $white;
		color: $white;

		&:before {
			background: lighten($persimmon, 2%);
		}
	}
}

.sponsored_airbnb {
	background-position: center right;
	background-size: contain;
	background-repeat: no-repeat;
	display: flex;
	flex: 2;
	margin-right: 20px;

	h3 {
		margin-bottom: 35px;
		color: $white;
	}

	li {
		flex: 1;
		padding: 38px 46px;

		&:first-child {
			background: $persimmon;
			color: white;
		}
	}
}

.sponsored_whale {
	flex: 1;
}
</style>
