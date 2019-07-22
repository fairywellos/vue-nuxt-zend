<template>
	<div class="fieldset_container">
		<div class="d_flex not_even">
			<div class="form_group">
				<label>Shipping</label>
				<button type="button" class="btn__switch" role="button">
					<label for="switchShip">
						<input
							type="checkbox"
							id="switchShip"
							name="check"
							v-model="shipping"
							data-vv-scope="DetailsStep"
						>
						<span>Yes</span>
						<span>No</span>
						<span class="toggle"></span>
					</label>
				</button>
			</div>
			<div class="form_group">
				<label>Condition</label>
				<multiselect
					placeholder="Please select..."
					v-model="condition"
					:options="listingCondition"
					label="name"
					track-by="id"
					v-validate="'required:true'"
					data-vv-name="condition"
					data-vv-as="Condition"
					data-vv-scope="DetailsStep"
				/>
				<span v-if="errors" class="is_danger error_message">{{ errors.first('DetailsStep.condition') }}</span>
			</div>
		</div>
		<div class="d_flex">
			<div class="form_group">
				<label for="meta_tags">Meta tags</label>
				<input
					id="meta_tags"
					type="text"
					placeholder="Keyword lookup"
					data-vv-scope="DetailsStep"
					v-model="meta_tags"
				>
			</div>
			<div class="form_group">
				<label for>Location</label>
				<input
					type="text"
					placeholder="Location"
					v-model="location"
					data-vv-scope="DetailsStep"
					v-validate="'required:true|min:5'"
				>
				<span v-if="errors" class="is_danger error_message">{{ errors.first('OverviewStep.location') }}</span>
			</div>
		</div>
		<div class="d_flex">
			<div class="form_group form_group_half">
				<label for>Year</label>
				<input type="text" placeholder="Year" v-model="year" data-vv-scope="DetailsStep">
			</div>
			<div class="form_group form_group_half">
				<label for>Brand</label>
				<input type="text" placeholder="Brand" v-model="brand" data-vv-scope="DetailsStep">
			</div>
		</div>
		<div class="d_flex">
			<div class="form_group form_group_half">
				<label for>Color</label>
				<input type="text" placeholder="Color" v-model="color" data-vv-scope="DetailsStep">
			</div>
			<div class="form_group form_group_half">
				<label for>Material</label>
				<input type="text" placeholder="Material" v-model="material" data-vv-scope="DetailsStep">
			</div>
		</div>
		<div class="d_flex">
			<div class="form_group form_group_half">
				<label for>Qty</label>
				<input type="text" placeholder="Quantity" v-model="qty" data-vv-scope="DetailsStep">
			</div>
			<div class="form_group form_group_half">
				<label for>Available Date</label>
				<input
					type="text"
					placeholder="Available date"
					v-model="available_date"
					data-vv-scope="DetailsStep">
			</div>
		</div>
		<div class="d_flex">
			<div class="form_group">
				<label for="notes_desc">Notes</label>
				<textarea id="notes_desc"
						  placeholder="Enter notes"
						  v-model="notes"
						  data-vv-scope="DetailsStep"
				></textarea>
			</div>
		</div>
	</div>
</template>

<script>
import Multiselect from "vue-multiselect";
import ListingConfig from "../../mixins/listingConfig.js";

export default {
	mixins: [ListingConfig],
	components: {
		Multiselect
	},
	inject: ["$validator"],
	props: ["listingData"],
	data() {
		return {
			valueConditionSelect: null,
			valueInterestsSelect: null,
			optionsInterestsSelect: ["type 4", "type 5", "type 6"],
			valueListingSelect: null,
			optionsListingSelect: ["type 7", "type 8", "type 9"]
		};
	},
	computed: {
		condition: {
			get: function() {
				let parent = this;
				return this.listingCondition.find(function(element) {
					return element.id === parent.listingData.condition;
				});
			},
			set: function(val) {
				this.$emit("update", {
					condition: typeof val !== "undefined" ? val.id : 0
				});
			}
		},
		shipping: {
			get: function() {
				return this.listingData.shipping === 1;
			},
			set: function(val) {
				this.$emit("update", { shipping: val === true ? 1 : 0 });
			}
		},
		year: {
			get: function() {
				return this.listingData.year;
			},
			set: function(val) {
				this.$emit("update", { year: val });
			}
		},
		brand: {
			get: function() {
				return this.listingData.brand;
			},
			set: function(val) {
				this.$emit("update", { brand: val });
			}
		},
		color: {
			get: function() {
				return this.listingData.color;
			},
			set: function(val) {
				this.$emit("update", { color: val });
			}
		},
		material: {
			get: function() {
				return this.listingData.material;
			},
			set: function(val) {
				this.$emit("update", { material: val });
			}
		},
		qty: {
			get: function() {
				return this.listingData.qty;
			},
			set: function(val) {
				this.$emit("update", { qty: val });
			}
		},
		location: {
			get: function() {
				return this.listingData.location;
			},
			set: function(val) {
				this.$emit("update", { location: val });
			}
		},
		notes: {
			get: function() {
				return this.listingData.notes;
			},
			set: function(val) {
				this.$emit("update", { notes: val });
			}
		},
		available_date: {
			get: function() {
				return this.listingData.available_date;
			},
			set: function(val) {
				this.$emit("update", { available_date: val });
			}
		},
		meta_tags: {
			get: function() {
				return this.listingData.meta_tags;
			},
			set: function(val) {
				this.$emit("update", { meta_tags: val });
			}
		}
	}
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style lang="scss" scoped>
@import "~assets/scss/abstracts/_abstracts.scss";

.d_flex {
	justify-content: space-between;
	flex-wrap: wrap;
	
	&.not_even {
		flex-wrap: nowrap;
		
		.form_group {
			&:first-child {
				width: 90px;
			}
			
			&:last-child {
				width: calc(100% - 90px - 29px);
			}
		}
	}
}

.custom-checkbox {
	& + .custom-checkbox {
		margin-left: 15px;
	}

	label {
		text-transform: none;
	}
}

.btn__switch {
	margin-bottom: 15px;
	
	label {
		padding: 11px 9px;
	}
}

.form_group {
	width: 100%;
	
	&.form_group_half {
		width: 45%;
	}

	&:last-child {
		margin-right: 0;
	}

	input[type="text"],
	input[type="number"],
	input[type="email"] {
		width: 100%;
		display: block;
	}

	textarea {
		width: 100%;
		height: 178px;
	}
}

label {
	margin-bottom: 8px;
	text-transform: uppercase;
	font-size: 10px;
	max-width: 150px;
}
</style>
