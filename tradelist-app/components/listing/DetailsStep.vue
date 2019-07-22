<template>
	<div class="fieldset_container">
		<div class="d_flex">
			<div class="form_group form_group-small">
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
					data-vv-name="condition"
					data-vv-as="Condition"
					data-vv-scope="DetailsStep"
				/>

			</div>
		</div>
		<div class="d_flex">
			<div class="form_group form_group-medium">
				<label for="meta_tags">Meta tags</label>
				<input
					id="meta_tags"
					type="text"
					placeholder="Keyword lookup"
					data-vv-scope="DetailsStep"
					v-model="meta_tags"
				>
			</div>
			<div class="form_group form_group-medium">
				<label for>Location</label>
				<input
					type="hidden"
					v-model="location"
					data-vv-scope="DetailsStep"
					v-validate="'required:true'"
					data-vv-name="location"
					data-vv-as="Location"
				>
				<input
					type="text"
					autocomplete="off"
					placeholder="City, Zip code"
					@click="openDropdown($event.target)"
					v-model="locationText"
					@input="updateVal($event.target)"
					data-vv-scope="DetailsStep"
					v-validate="'required:true'"
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
										v-model="locationText"
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
									@click="location = item.id"
									v-model="locationText"
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
				<span v-if="errors" class="is_danger error_message">{{ errors.first('DetailsStep.location') }}</span>
			</div>
		</div>
		<div class="d_flex">
			<div class="form_group form_group-medium">
				<label for>Year</label>
				<input type="text" placeholder="Year" v-model="year" data-vv-scope="DetailsStep">
			</div>
			<div class="form_group form_group-medium">
				<label for>Brand</label>
				<input type="text" placeholder="Brand" v-model="brand" data-vv-scope="DetailsStep">
			</div>
		</div>
		<div class="d_flex">
			<div class="form_group form_group-medium">
				<label for>Color</label>
				<input type="text" placeholder="Color" v-model="color" data-vv-scope="DetailsStep">
			</div>
			<div class="form_group form_group-medium">
				<label for>Material</label>
				<input type="text" placeholder="Material" v-model="material" data-vv-scope="DetailsStep">
			</div>
		</div>
		<div class="d_flex">
			<div class="form_group form_group-medium">
				<label for>Qty</label>
				<input type="text" placeholder="Quantity" v-model="qty" data-vv-scope="DetailsStep"
					   data-vv-name="qty"
					   v-validate="'required:true'">
				<span v-if="errors" class="is_danger error_message">{{ errors.first('DetailsStep.qty') }}</span>
			</div>

			<div class="form_group form_group-medium">
				<label for>Available Date</label>
				<input
					type="text"
					placeholder="Available date"
					v-model="available_date"
					data-vv-scope="DetailsStep"
				>
			</div>
		</div>
		<div class="d_flex">
			<div class="form_group form_group-full">
				<label for="notes_desc">Notes</label>
				<textarea id="notes_desc" placeholder="Enter notes" v-model="notes" data-vv-scope="DetailsStep"></textarea>
			</div>
		</div>
	</div>
</template>

<script>
	import Multiselect from "vue-multiselect";
	import ListingConfig from "../../mixins/listingConfig.js";
	import searchBar from "~/assets/js/searchBar.js";


	let typingTimer;
	let doneTypingInterval = 1000;

	export default {
		mixins: [ListingConfig],
		components: {
			Multiselect
		},
		inject: ["$validator"],
		props: ["listingData"],
		extends: searchBar,
		data() {
			return {
				valueConditionSelect: null,
				valueInterestsSelect: null,
				optionsInterestsSelect: ["type 4", "type 5", "type 6"],
				valueListingSelect: null,
				optionsListingSelect: ["type 7", "type 8", "type 9"],
				city: "",
				browserLocation:
					{
						set: false,
						latitude: "",
						longitude: "",
						location: false
					},
				recentSearched: [],
				statesVal: null,
				states: [],
				term: this.$route.query.q || "",
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
			locationText: {
				get: function() {
					return this.listingData.locationText;
				},
				set: function(val) {
					this.$emit("update", { locationText: val });
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
		justify-content: flex-start;
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
		label {
			padding: 11px 9px;
		}
	}

	.form_group-full {
		width: 100%;
	}

	.form_group-medium {
		width: 256px;
	}

	.form_group-small {
		width: 80px;
	}

	.form_group {
		margin-right: 60px;
		position: relative;

		.location_dropdown {
			top: calc(100% - 15px);

			li {
				label {
					font-size: 12px;
					line-height: 23px;
					color: $gray_tuna;
					text-transform: capitalize;
					cursor: pointer;
				}
			}
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
