<template>
	<div class="fieldset_container">
		<div class="form_group">
			<div class="location">
				<label for="title">Title</label>
				<input
					type="text"
					v-model="title"
					name="title"
					id="title"
					v-validate="'required:true|min:5'"
					data-vv-scope="OverviewStep"
				>
				<span v-if="errors" class="is_danger error_message">{{ errors.first('OverviewStep.title') }}</span>
			</div>
			<div class="trade_location">
				<label for="switch2">Local only?</label>
				<button type="button" class="btn__switch" role="button">
					<label for="switch2">
						<input
							type="checkbox"
							id="switch2"
							name="check"
							data-vv-scope="OverviewStep"
							v-model="local_trades_only"
							:searchable="false"
						>
						<span>On</span>
						<span>Off</span>
						<span class="toggle"></span>
					</label>
				</button>
			</div>
		</div>
		<div class="form_group">
			<div class="value">
				<label for="price">Estimate value</label>
				<input
					type="text"
					v-model="price"
					placeholder="$USD"
					id="price"
					name="price"
					data-vv-scope="OverviewStep"
				>
			</div>
			<div class="trade_type">
				<label>Trade type</label>
				<div class="custom-checkbox">
					<input
						type="checkbox"
						v-model="trade_or_cash"
						value="1"
						id="barter"
						data-vv-scope="OverviewStep"
					>
					<label for="barter">
						<span class="checkmark"></span>
						Barter
					</label>
				</div>
				<div class="custom-checkbox">
					<input
						type="checkbox"
						v-model="trade_or_cash"
						value="2"
						id="cash"
						data-vv-scope="OverviewStep"
					>
					<label for="cash">
						<span class="checkmark"></span>
						Cash
					</label>
				</div>
			</div>
			<div class="listing_type">
				<label>Listing Type</label>
				<multiselect
					placeholder="Please select..."
					v-model="listingTypeObj"
					:options="listingType"
					label="name"
					track-by="id"
					v-validate="'required:true'"
					data-vv-name="listing_type"
					data-vv-as="Listing type"
					data-vv-scope="OverviewStep"
				/>
				<span
					v-if="errors"
					class="is_danger error_message"
				>{{ errors.first('OverviewStep.listing_type') }}</span>
			</div>
		</div>
		<div class="form_group">
			<label for="description">Description</label>
			<textarea
				name="description"
				id="description"
				v-model="description"
				placeholder="Please enter you listing description"
				data-vv-scope="OverviewStep"
			></textarea>
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
			return {};
		},
		computed: {
			title: {
				get: function () {
					return this.listingData.title;
				},
				set: function (val) {
					this.$emit("update", {title: val});
				}
			},
			description: {
				get: function () {
					return this.listingData.description;
				},
				set: function (val) {
					this.$emit("update", {description: val});
				}
			},
			price: {
				get: function () {
					return this.listingData.price;
				},
				set: function (val) {
					this.$emit("update", {price: val});
				}
			},
			listingTypeObj: {
				get: function () {
					this.listingTypeId = this.listingData.listingType;
					return this.getListingTypeObj(this.listingTypeId);
				},
				set: function (val) {
					this.listingTypeId = typeof val !== "undefined" ? val.id : 0;
					this.$emit("update", {listingType: this.listingTypeId});
				}
			},
			trade_or_cash: {
				get: function () {
					let value = this.listingData.tradeOrCash;
					value = value === 0 ? [] : value === 3 ? ["1", "2"] : [value];
					
					return value;
				},
				set: function (val) {
					if (typeof val === "undefined") {
						val = [];
					} else if (val.indexOf("1") >= 0 && val.indexOf("2") >= 0) {
						val = 3;
					} else {
						val = val[0];
					}
					
					this.$emit("update", {
						tradeOrCash: typeof val === "undefined" ? 0 : val
					});
				}
			},
			local_trades_only: {
				get: function () {
					return this.listingData.local_trades_only === 1;
				},
				set: function (val) {
					this.$emit("update", {
						local_trades_only: val === true ? 1 : 0
					});
				}
			}
		},
		methods: {
			getListingTypeObj(listingTypeId) {
				if (typeof listingTypeId === "undefined") {
					return [];
				}
				
				return this.listingType.find(function (element) {
					return element.id === listingTypeId;
				});
			}
		}
	};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";
	
	.form_group {
		.btn__switch {
			margin-top: 5px;
		}
		
		.is_danger {
			display: inline-block;
			margin-bottom: 15px;
		}
		
		.location {
			width: 100%;
			max-width: calc(100% - 128px);
			margin-right: 30px;
		}
		
		.price {
			width: 100%;
			max-width: 256px;
		}
		
		.value {
			margin-right: 15px;
			margin-bottom: 15px;
			width: calc(100% - 217px - 30px);
		}
		
		.trade_type {
			margin-right: 10px;
			margin-bottom: 15px;
		}
		
		.listing_type {
			width: 100%;
			
			.multiselect {
				max-width: 100%;
			}
			
			.multiselect__single {
				margin-bottom: 0;
			}
		}
	}
	
	button {
		label[for="switch2"] {
			display: flex;
		}
	}
	
	input[type="text"]:not(.multiselect__input),
	input[type="email"],
	input[type="email"],
	input[type="tel"],
	input[type="password"],
	input[type="search"],
	select,
	textarea {
		color: $gray_aluminium;
		
		&:focus {
			color: $gray_shark;
		}
	}
	
	label {
		text-transform: uppercase;
	}
	
	textarea {
		height: 178px;
	}
</style>
