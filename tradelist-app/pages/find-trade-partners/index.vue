<template>
<div>
					<Header />
					<SubHeader />

					<div class="tab_content">
						<div class="container">
							<div class="trade_simple_search">
								<input type="text" placeholder="Search..." v-model="searchBar" v-on:keyup.enter="searchPartner()">
								<button type="submit" class="btn btn__primary" @click="searchPartner()">Search</button>
								<ul class="filters">
									<li :class="{ 'is_active' : activeFilterLocation === 1, 'is_applied' : activeFilterLocation === 2 }" @click="openLocation($event)">
										<button type="button" class="btn btn__secondary">
											<span>Location</span>
											<span v-if="pickedVal">{{pickedVal}}</span>
											<span v-else>Location</span>
										</button>
										<div class="location_dropdown">
											<div class="search_inside">
												<input type="text" autocomplete="off" placeholder="City, Zip code" v-model="locationText" @input="updateVal($event.target)">
											</div>
											<div class="current_location" @click="getGeolocation()">
												<i class="icon-location"></i>
												<span v-if="browserLocation.set">{{browserLocation.location.name + "," + browserLocation.location.abbreviation }}</span>
												<span v-else>Current location</span>
											</div>
											<div class="recent_searched" v-if="recentSearched.length > 0">
												<div class="actions">
													<p>Recent</p>
													<button @click="removeRecents($event.target),searchPartner()" type="button" role="button" class="btn">
														Clear
														all
													</button>
												</div>
												<ul role="list">
													<li v-for="item in recentSearched" :key="item.id">
														<label>
															<input type="radio" name="cityState" :value="item.name + ', ' + item.abbreviation" v-model="locationText">
															{{ item.name }},
															<span class="state">{{ item.abbreviation }}</span>
														</label>
													</li>
												</ul>
											</div>
											<ul v-if="states.length" role="list">
												<li v-for="item in states.slice(0, 5)" :key="item.id">
													<label>
														<input type="radio" name="cityState" :value="item.name + ', ' + item.abbreviation" @click="location = item.id; pickedVal = item.name + ', ' + item.abbreviation" v-model="locationText">
														{{ item.name }},
														<span class="state">{{ item.abbreviation }}</span>
													</label>
												</li>
											</ul>
											<p v-else>
												<label>No data found</label>
											</p>
											<div class="action_buttons">
												<button type="button" role="button" class="btn clear_tag" @click="clearLocation($event)">Clear</button>
												<button type="button" role="button" class="btn apply_tag" @click="applyLocation()">Apply</button>
											</div>
										</div>
									</li>
									<li :class="{'is_active': this.activeFilterCat === 1, 'is_applied' : this.activeFilterCat === 2}" @click="openCategory($event)">
										<button type="button" class="btn btn__secondary">
											<span>Category</span>
											<span v-if="mainCatsSelected.length > 0">Category ({{mainCatsSelected.length}})</span>
											<span v-else>Category</span>
										</button>
										<div class="tags_dropdown">
											<ul>
												<li v-for="mainCat in optionsMainCats" :key="mainCat.id" class="custom-checkbox">
													<input type="checkbox" v-bind:id="'main_cat_'+mainCat.id" v-bind:value="mainCat.id" v-model="mainCatsSelected">
													<label v-bind:for="'main_cat_'+mainCat.id">
														<span class="checkmark"></span>
														<p>{{mainCat.name}}</p>
													</label>
												</li>
											</ul>
											<div class="action_buttons">
												<button type="button" role="button" class="btn clear_tag" @click="clearCategory($event), searchPartner()">Clear</button>
												<button type="button" role="button" class="btn apply_tag" @click.stop="applyCategory($event)">Apply</button>
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
												<div id="rangeSliderPartners"></div>
												<p>
													$
													<span id="minVal"></span> - $
													<span id="maxVal"></span>
												</p>
											</div>
											<div class="action_buttons">
												<button type="button" role="button" class="btn clear_tag" @click="resetPrice($event), searchPartner()">Reset</button>
												<button type="button" role="button" class="btn apply_tag" @click="applyPrice($event)">Apply</button>
											</div>
										</div>
									</li>
								</ul>
							</div>
							<div class="trader_grid">
								<TraderCard
										v-for="traderData in tradersData"
										:key="traderData.id"
										:id="traderData.id"
										:followed="traderData.followed"
										:name="traderData.name"
										:privacy="traderData.privacy"
										:username="traderData.username"
										:location="traderData.location"
										:thumbnail="traderData.photo_url"
										:trades="traderData.trades"
										:followers="traderData.followers"
										:rating="traderData.rating"
										:badge="traderData.badge"
								/>
							</div>
						</div>
					</div>
				</div>
</template>

<script>
import Header from "~/components/Header.vue";
				import SubHeader from "~/components/saved/SubHeader.vue";
				import TraderCard from "~/components/TraderCard";
				import image1 from "~/assets/img/card-item/Image.png";
				import image2 from "~/assets/img/card-item/Image2.png";
				import image3 from "~/assets/img/card-item/Image3.png";
				import image4 from "~/assets/img/card-item/Image4.png";
				import image5 from "~/assets/img/card-item/Image.jpg";
				import ownerImage from "~/assets/img/card-item/user_image.png";
				import ListingConfig from "~/mixins/listingConfig.js";
				import findTradePartners from "~/assets/js/findTradePartners.js";

	export default {
		mixins: [ListingConfig],
		middleware: "auth",
		components: {
			Header,
			SubHeader,
			TraderCard
		},
		extends: findTradePartners,
		data() {
			return {
				image1,
				image2,
				image3,
				image4,
				image5,
				ownerImage,
				searchBar: "",
				startPrice: [0, 1025],
				activeFilterCat: false,
				activeFilterPrice: false,
				activeFilterLocation: false,
				checkedTrades: [],
				selectedPriceRange: [0,0],
				mainCatsSelected: [],
				optionsMainCats: this.$store.getters[
					"mainCategories/getCategories"
				],
				city: "",
				browserLocation: [
					{
						set: false,
						latitude: "",
						longitude: "",
						location: false
					}
				],
				recentSearched: [],
				locationText: this.city,
				statesVal: null,
				states: [],
				term: this.$route.query.q || "",
				pickedVal: false,
				location: false
			};
		},
		async asyncData({store}) {
			let tradersData = await store.dispatch('apiCalls/getTradePartners');
			return {tradersData: tradersData}
		},
		computed: {
			tradeTypeDetails() {
				let parent = this;
				let tradeTypes = this.checkedTrades.map(function(tradeType) {
					let detail = parent.listingTradeType.find(function(element) {
						return element.id === parseInt(tradeType);
					});

								return detail !== undefined ? detail.name : null;
							});

							return tradeTypes.join(", ");
						}
					}
				};
</script>


<style lang="scss" scoped>
@import "~assets/scss/abstracts/_abstracts.scss";

.searched_list {
    padding-top: 45px;
    margin-bottom: 90px;

    li {
        align-items: center;
        border: 1px solid $gray_gallery;
        border-radius: 2px;
        background: $white;
        margin-bottom: 13px;
        padding: 10px 15px;
        position: relative;
    }

    h3 {
        font-size: 14px;
        color: $gray_tuna;
        margin-bottom: 4px;
    }

    p {
        font-size: 12px;
        color: $gray_aluminium;
        font-weight: 400;
        margin: 0;
    }

    .btn_dots {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
    }
}

.tab_content {
    padding-top: 51px;

    .tab_pane {
        display: none;
        opacity: 0;
        transition: 0.2s $bezier_ease_in_out;
        z-index: -9999;
    }

    .tab_pane {
        &.active {
            position: static;
            display: block;
            opacity: 1;
            z-index: auto;
        }
    }
}

.filters {
    display: flex;
    align-items: center;
    width: 100%;

    li {
        position: relative;
        margin-right: 6px;

        > .btn__secondary {
            text-transform: none;
            padding: 9.5px 20px;
            font-size: 12px;
            color: $gray_tuna;
            max-width: 200px;
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

        // &.is_active.filter_location,
        // &.is_applied.filter_location {
        // 	> .btn {
        // 		> span {
        // 			display: block;

        // 			& + span {
        // 				display: none;
        // 			}
        // 		}
        // 	}
        // }

        &.is_active {
            .tags_dropdown {
                opacity: 1;
                visibility: visible;
                z-index: 10;
            }

            .location_dropdown {
                height: auto;
                max-height: 301px;
                z-index: 99;
                visibility: visible;
                opacity: 1;
            }
        }

        .location_dropdown {
            padding-bottom: 31px;

            input[type="text"] {
                border-color: #e8e8e8;
                margin-top: 10px;
            }

            .search_inside {
                padding: 5px 15px;
            }

            .action_buttons {
                position: absolute;
                display: flex;
                justify-content: space-between;
                bottom: 0;
                background: #fff;
                width: 100%;

                .btn {
                    font-size: 12px;
                    line-height: normal;
                    text-transform: none;
                    transition: 0.2s $bezier_ease_in_out;

                    &:hover {
                        color: $blue_scooter;
                    }
                }
            }
        }
    }

    .icon-location {
        margin-right: 5px;

        &:before {
            // color: $white;
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
            transition: 0.2s $bezier_ease_in_out;

            &:hover {
                color: $blue_scooter;
            }
        }
    }

    .clear_tag {
        color: $gray_tuna;
    }

    .apply_tag {
        color: $blue_scooter;
    }

    .custom-checkbox {
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
            padding-top: 6px;
        }

        .checkmark {
            margin-right: 10px;
            position: static;
            transform: translateY(0);

            &:before {}
        }
    }
}

.trade_simple_search {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    margin-bottom: 41px;

    > input[type="text"],
     > button[type="submit"] {
        margin-bottom: 15px;
    }

    input[type="text"] {
        background: $white;
        border-radius: 20px;
        margin-right: 14px;
        display: block;
        width: 100%;
        max-width: 601px;
        height: 42px;
    }

    button[type="submit"] {
        padding: 11.5px 33px;
    }
}
</style>
