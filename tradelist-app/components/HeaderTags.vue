<template>
	<header class="header_tags">
		<div class="container">
			<div class="main_header__bottom">
				<ul class="filters">
					<!--<li class="is_active">
						<button type="button" class="btn btn__secondary">
							<i class="icon-location"></i> Seattle, WA
						</button>
					</li>-->
					<li :class="{'is_active': this.activeFilterCat === 1, 'is_applied' : this.activeFilterCat === 2}" @click="openCategory($event)">
						<button type="button" class="btn btn__secondary">
							<span>Category</span>
							<span v-if="mainCatsSelected.length === 1 ">{{findCategory(mainCatsSelected[0]).name }}</span>
							<span v-else-if="mainCatsSelected.length > 1 ">Category ({{mainCatsSelected.length}})</span>

							<span v-else>Category</span>
						</button>
						<div class="tags_dropdown">
							<ul>
								<li v-for="mainCat in optionsMainCats" :key="mainCat.id" class="custom-checkbox">
									<input
										type="checkbox"
										v-bind:id="'main_cat_'+mainCat.id"
										v-bind:value="mainCat.id"
										v-model="mainCatsSelected"
									>
									<label v-bind:for="'main_cat_'+mainCat.id">
										<span class="checkmark"></span>
										<p>{{mainCat.name}}</p>
									</label>
								</li>
							</ul>
							<div class="action_buttons">
								<button type="button" role="button" class="btn clear_tag" @click="clearCategory($event)">
									Clear
								</button>
								<button type="button" role="button" class="btn apply_tag" @click="applyCategory($event)">
									Apply
								</button>
							</div>
						</div>
					</li>
					<li :class="{ 'is_active' : activeFilterPrice === 1, 'is_applied' : this.activeFilterPrice === 2 }" @click="openPrice($event)">
						<button type="button" class="btn btn__secondary">
							<span>Price</span>
							<span>${{ Math.round(selectedPriceRange[0]) }} - ${{ Math.round(selectedPriceRange[1]) }}</span>
						</button>
						<div class="tags_dropdown">
							<div class="price_range_tag">
								<img src="~/assets/img/price-range.png" alt="range price">
								<div id="rangeSlider"></div>
								<p>
									$
									<span id="minVal"></span> - $
									<span id="maxVal"></span>
								</p>
							</div>
							<div class="action_buttons">
								<button type="button" role="button" class="btn clear_tag" @click="resetPrice($event)">Reset
								</button>
								<button type="button" role="button" class="btn apply_tag" @click="applyPrice($event)">Apply
								</button>
							</div>
						</div>
					</li>
					<li :class="{ 'is_active' : activeFilterTradeType === 1, 'is_applied' : this.activeFilterTradeType === 2 }" @click="openTradeType($event)">
						<button type="button" class="btn btn__secondary">
							<span>Trade type</span>
							<span>
								<span>{{tradeTypeDetails}}</span>
								<span v-if="tradeTypeDetails.length < 1">Trade Type</span>
							</span>
						</button>
						<div class="tags_dropdown">
							<ul>
								<li>
									<div class="custom-checkbox">
										<input type="checkbox" id="tradeOfferTags" value="1" v-model="checkedTrades">
										<label for="tradeOfferTags">
											<span class="checkmark"></span>
											<p>
												Trade Offers
												<small>Listings open to trade offers</small>
											</p>
										</label>
									</div>
								</li>
								<li>
									<div class="custom-checkbox">
										<input type="checkbox" id="tradeCashOfferTags" value="2"
										       v-model="checkedTrades">
										<label for="tradeCashOfferTags">
											<span class="checkmark"></span>
											<p>
												Cash Offers
												<small>Listings open to cash offers</small>
											</p>
										</label>
									</div>
								</li>
							</ul>
							<div class="action_buttons">
								<button type="button" role="button" class="btn clear_tag" @click="clearTradeType($event)">
									Clear
								</button>
								<button type="button" role="button" class="btn apply_tag" @click="applyTradeType($event)">
									Apply
								</button>
							</div>
						</div>
					</li>
					<!--<li>
						<button type="button" class="btn btn__secondary">Condition</button>
					</li>
					<li>
						<button type="button" class="btn btn__secondary">Shipping</button>
					</li>
					<li>
						<button type="button" class="btn btn__secondary">More filters</button>
					</li>-->
				</ul>
				<!--<div class="main_header__map_toggle">
					<span>Map</span>
					<div class="btn__switch">
						<label>
							<input type="checkbox" value="None" name="check">
							<span>On</span>
							<span>Off</span>
							<span class="toggle"></span>
						</label>
					</div>
				</div>-->
			</div>
		</div>
	</header>
</template>

<script>
	import ListingConfig from "../mixins/listingConfig.js";
	
	export default {
		propos: ["path"],
		mixins: [ListingConfig],
		data() {
			return {
				startPrice: [0, 0],
				activeFilterCat: this.$route.query.category !== undefined
					? 2
					: false,
				activeFilterPrice: this.$route.query.price_max !== undefined && this.$route.query.price_max > 0
					? 2
					: false,
				activeFilterTradeType: this.$route.query.trade_type !== undefined
					? 2
					: false,
				checkedTrades:
					this.$route.query.trade_type !== undefined
						? this.$route.query.trade_type.split(",")
						: [],
				selectedPriceRange: [
					this.$route.query.price_min || 0,
					this.$route.query.price_max || 0
				],
				mainCatsSelected:
					this.$route.query.category !== undefined
						? this.$route.query.category.split(",")
						: [],
				optionsMainCats: this.$store.getters["mainCategories/getCategories"]
			};
		},
		methods: {
			findCategory(id) {
				return this.$store.getters['mainCategories/getCategory'](id);
			},
			resetUrl(element,secondEl) {
				let query = {...this.$route.query};3
				delete query[element];
				if(secondEl !== false){
					delete query[secondEl];
				}
				this.$router.push({
					path: this.path,
					query: query
				});
			},
			openCategory: function (event) {
				this.activeFilterCat = 1;
				
				if (this.activeFilterPrice !== 2) {
					this.activeFilterPrice = false;
				}
				
				
				if (this.activeFilterTradeType !== 2) {
					this.activeFilterTradeType = false;
				}
			},
			clearCategory: function (event) {
				event.stopPropagation();
				this.mainCatsSelected = [];
				this.activeFilterCat= false;
				this.resetUrl("category",false);
			},
			applyCategory: function (event) {
				event.stopPropagation();
				this.activeFilterCat = 2;
				if(this.mainCatsSelected.length > 0){
					this.$router.push({
						path: this.path,
						query: {
							...this.$route.query,
							category: this.mainCatsSelected.join(",")
						}
					});
				}else {
					this.activeFilterCat = false;
					this.resetUrl("category",false);
				}
			},
			openPrice: function (el) {
				this.activeFilterPrice = 1;
				
				if (this.activeFilterCat !== 2) {
					this.activeFilterCat = false;
				}
				
				if (this.activeFilterTradeType !== 2) {
					this.activeFilterTradeType = false;
				}
			},
			applyPrice(event) {
				event.stopPropagation();
				this.activeFilterPrice = 2;
				if(this.selectedPriceRange[1] > 0){
					this.$router.push({
						path: this.path,
						query: {
							...this.$route.query,
							price_min: this.selectedPriceRange[0] || 0,
							price_max: this.selectedPriceRange[1] || 0
						}
					});
				}else {
					this.resetPrice(event);
				}

			},
			resetPrice: function (event) {
				event.stopPropagation();
				this.selectedPriceRange = this.startPrice;
				let rangeSlider = document.getElementById("rangeSlider");
				rangeSlider.noUiSlider.set(this.startPrice);
				this.activeFilterPrice = false;
				this.resetUrl("price_min","price_max");
			},
			openTradeType: function (el) {
				this.activeFilterTradeType = 1;
				
				if (this.activeFilterPrice !== 2) {
					this.activeFilterPrice = false;
				}
				
				if (this.activeFilterCat !== 2) {
					this.activeFilterCat = false;
				}
			},
			applyTradeType: function (event) {
				event.stopPropagation();
				this.activeFilterTradeType = 2;
				if(this.checkedTrades.length > 0){

					this.$router.push({
						path: this.path,
						query: {
							...this.$route.query,
							trade_type: this.checkedTrades.join(",")
						}
					});
				}else {
					this.clearTradeType(event);
				}
			},
			clearTradeType: function (event) {
				event.stopPropagation();
				this.activeFilterTradeType = false;
				this.checkedTrades = [];
				this.resetUrl("trade_type");
			},
			initRangeSlider: function () {
				if (rangeSlider) {
					let rangeSlider = document.getElementById("rangeSlider");
					let minVal = document.querySelector("#minVal");
					let maxVal = document.querySelector("#maxVal");
					let that = this;
					
					noUiSlider.create(rangeSlider, {
						start:
							this.selectedPriceRange.length > 0
								? this.selectedPriceRange
								: this.startPrice,
						connect: true,
						step: 100,
						range: {
							min: 0,
							max: 10000
						}
					});
					
					rangeSlider.noUiSlider.on("update", function (values) {
						let collectVal;
						
						collectVal = values;
						that.selectedPriceRange = collectVal;
						
						minVal.innerHTML = Math.round(values[0]);
						
						if (Math.round(values[1]) >= 10000) {
							maxVal.innerHTML = "10000+";
						} else {
							maxVal.innerHTML = Math.round(values[1]);
						}
					});
				}
			},
			detectClickOutside: function (event) {
				let self = this;
				let catsUnderHero = document.querySelectorAll('.categories_list li label');
				
				document.addEventListener('click', function (event) {
					if (self.activeFilterCat === 1) {
						if (document.querySelector('li.is_active').contains(event.target)) {
							return;
						} else {
							if (self.mainCatsSelected.length > 0 )  {
								self.activeFilterCat = 2;
								self.$router.push({
									path: this.path,
									query: {
										...self.$route.query,
										category: self.mainCatsSelected.join(",")
									}
								});
							} else {
								self.activeFilterCat = false;
								self.clearCategory(event);
							}
						}
					}
					
					if (self.activeFilterTradeType === 1) {
						if (document.querySelector('li.is_active').contains(event.target)) {
							return;
						} else {
							if (self.checkedTrades.length > 0 )  {
								self.activeFilterTradeType = 2;
								self.$router.push({
									path: this.path,
									query: {
										...self.$route.query,
										trade_type: self.checkedTrades.join(",")
									}
								});
							} else {
								self.activeFilterTradeType = false;
								self.clearTradeType(event);
							}
						}
					}
					
					if (self.activeFilterPrice === 1) {
						if (document.querySelector('li.is_active').contains(event.target)) {
							return;
						} else {
							if (self.selectedPriceRange[1] > 0 )  {
								self.activeFilterPrice = 2;
								self.$router.push({
									path: this.path,
									query: {
										...self.$route.query,
										price_min: self.selectedPriceRange[0] || 0,
										price_max: self.selectedPriceRange[1] || 0
									}
								});
							} else {
								self.activeFilterPrice = false;
								self.resetPrice(event);
							}
						}
					}
				});
			},
			
		},
		computed: {
			tradeTypeDetails() {
				let parent = this;
				let tradeTypes = this.checkedTrades.map(function (tradeType) {
					let detail = parent.listingTradeType.find(function (element) {
						return element.id === parseInt(tradeType);
					});
					
					return detail !== undefined ? detail.name : null;
				});
				
				return tradeTypes.join(", ");
			}
		},
		mounted() {
			this.initRangeSlider();
			this.detectClickOutside();
		}
	};
</script>

<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";
	
	.header_tags {
		background: $gray_albaster;
		position: fixed;
		left: 0;
		right: 0;
		top: 80px;
		z-index: 97;
		border-bottom: 1px solid $gray_aluminium2;
	}
	
	#rangeSlider {
		margin: -4px 0 17px;
	}
	
	.price_range_tag {
		img {
			display: block;
			margin: 0 auto;
		}
		
		p {
			font-size: 14px;
			line-height: normal;
			color: $gray_aluminium;
		}
	}
	
	.main_header__bottom {
		padding: 3px 0 9px;
		transition: 0.2s $bezier_ease_in_out;
		display: flex;
		justify-content: space-between;
		align-items: center;
		
		li {
			margin-right: 6px;
			
			&:last-child {
				margin-right: 0;
			}
		}
	}
	
	.filters {
		display: flex;
		justify-content: space-between;
		align-items: center;
		
		li {
			position: relative;
			
			> .btn__secondary {
				text-transform: none;
			}
			
			.btn {
				padding: 10px 14px 8px;
				color: $gray_tuna;
				font-size: 12px;
				
				> span {
					& + span {
						display: none;
					}
				}
			}
		}
		
		> li {
			padding-bottom: 5px;
			
			&:hover {
				> .btn__secondary {
					background: rgba($gray_aluminium, 0.5);
				}
			}
			
			&.is_active,
			&.is_applied {
				> .btn {
					background: $blue_scooter;
					color: $white;
					border-color: $blue_scooter;
					text-transform: capitalize;
					
					> span {
						display: none;
						
						& + span {
							display: block;
						}
					}
				}
			}
			
			&.is_active {
				.tags_dropdown {
					opacity: 1;
					visibility: visible;
					z-index: 10;
				}
			}
		}
		
		.icon-location {
			margin-right: 5px;
			
			&:before {
				color: $white;
			}
		}
	}
	
	.tags_dropdown {
		position: absolute;
		top: 99%;
		left: 0;
		background: $white;
		border-radius: 3px;
		border: 1px solid $gray_aluminium;
		padding: 22px 27px 14px;
		min-width: 309px;
		box-shadow: 0 5px 21px rgba($black, 0.5);
		opacity: 0;
		visibility: hidden;
		z-index: -99;
		
		.action_buttons {
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding-top: 7px;
			
			.btn {
				font-size: 12px;
				line-height: normal;
				text-transform: none;
			}
		}
		
		.clear_tag {
			color: $gray_tuna;
		}
		
		.apply_tag {
			color: $blue_scooter;
		}
		
		.custom-checkbox {
			margin-bottom: 7px;
			
			label {
				color: $gray_tuna;
				display: flex;
				align-items: center;
				padding-left: 0;
			}
			
			p {
				max-width: 175px;
			}
			
			small {
				display: block;
				width: 100%;
				font-size: 12px;
				color: $gray_aluminium;
				/*display: inline-block;*/
				padding-top: 6px;
			}
			
			.checkmark {
				margin-right: 10px;
				position: static;
				transform: translateY(0);
				
				&:before {
				}
			}
		}
	}
	
	.main_header__map_toggle {
		font-size: 12px;
		letter-spacing: 2.4px;
		color: $gray_tuna;
		display: flex;
		align-items: center;
		justify-content: space-between;
		
		span {
			margin-right: 5px;
		}
	}
	
	.is_mobile {
		.header_tags {
			top: 61px;
		}
		
		.search_page {
			padding-top: 128px;
			
			.mh_mobile {
				box-shadow: none;
			}
			
			.results_hero {
				margin-top: 0;
			}
		}
		
		.main_header__bottom {
			padding: 6px 0 14px;
			
			.filters {
				> li {
					padding-bottom: 0;
				}
			}
		}
	}
</style>
