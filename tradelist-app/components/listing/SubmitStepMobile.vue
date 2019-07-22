<template>
	<div class="submit_step">
		<ul>
			<li v-for="(error, field) in errors.collect('server.*')">{{field}} : {{ error }}</li>
		</ul>
		<!-- <table>
			<tr v-for="(value, field) in listing">
				<td>
					<strong>{{legend[field]}}</strong>
				</td>
				<td>{{ formatListingValue(field, value) }}</td>
			</tr>
		</table>-->
		<div class="submit_message">
			<h3>You’re ready to publish!</h3>
			<p>
				You’ll be able to welcome your first guest after you set your availability, which you can easily do after
				you hit publish.
			</p>
		</div>
	</div>
</template>

<script>
import ListingConfig from "../../mixins/listingConfig.js";
import formatListingValue from "../../mixins/formatListingValue.js";
import stepNavigation from "~/assets/js/stepNavigation.js";

export default {
	mixins: [ListingConfig, formatListingValue],
	inject: ["$validator"],
	data() {
		return {
			activeStep: 0,
			stepList: [
				{ id: 0, ref: "CategoryStep", text: "Category" },
				{ id: 1, ref: "OverviewStep", text: "Overview" },
				{ id: 2, ref: "PhotosStep", text: "Photos" },
				{ id: 3, ref: "DetailsStep", text: "Details" },
				{ id: 4, ref: "SubmitStep", text: "Submit" }
			]
		};
	},
	extends: stepNavigation,
	computed: {
		listing: function() {
			return this.$store.getters["newListing/getData"];
		}
	}
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style lang="scss" scoped>
@import "~assets/scss/abstracts/_abstracts.scss";

.submit_listing_details {
	list-style-type: disc;
	padding: 0 0 20px 30px;

	li {
		color: $gray_tuna;
		margin-bottom: 10px;
		font-size: 16px;
		line-height: 21px;
	}
}

table {
	margin-bottom: 30px;
	border: 1px solid rgba($gray_aluminium, 0.17);
	border-collapse: collapse;

	tr {
		&:last-child {
			td {
				/*border-bottom-color: transparent;*/
			}
		}
	}

	td {
		padding: 15px 15px;
		border-bottom: 1px solid rgba($gray_aluminium, 0.17);
		line-height: 21px;
		font-size: 16px;
		color: $gray_tuna;

		&:first-child {
			width: 180px;
			border-right: 1px solid rgba($gray_aluminium, 0.17);
		}
	}
}

.submit_message {
	margin-bottom: 27px;
	max-width: 507px;

	h3 {
		font-size: 18px;
		line-height: 47px;
		font-weight: 700;
		letter-spacing: 0.17px;
		color: $gray_tuna;
		margin-bottom: 10px;
	}

	p {
		font-size: 16px;
		line-height: 21px;
	}
}
</style>
