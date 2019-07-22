<template>
	<div class="card_item">
		<div class="save_btn" v-if="isAuthenticated && listing.user_id !== loggedInUser.id">
			<label :for="'svd-' + listing.id">
				<input type="checkbox" :id="'svd-' + listing.id" name="state" v-model="listing_saved">
				<span class="icon"></span>
			</label>
		</div>
		<nuxt-link
			class="card_item_thumbnail"
			:to="'/listing-details/' + listing.id"
			:style="{ backgroundImage: 'url('+listing.mainPhotoUrl+')' }"
			:title="listing.title">
			<div v-if="parseInt(listing.sample_account) === 1" class="sample_tag">
				Sample <br> account
			</div>
	</nuxt-link>
		<nuxt-link :to="'/listing-details/' + listing.id" class="card_item__info" :title="listing.title">
			<div class="card_item__cat" :style="{color:category.colorCode}">{{ category.name }}</div>
			<h3>{{ listing.title }}</h3>
			<div class="card_item_price">
				<div class="value">${{listing.price}}</div>
				<div class="location" v-if="listing.location">{{listing.location}}</div>
			</div>
			<div class="card_item_transaction_type">
				<div class="name">
					<div class="user_image" v-if="listing.user_photo"
						 :style="{ backgroundImage: 'url('+ listing.user_photo + ')' }"></div>
					<div class="user_image" v-else ></div>

					{{listing.user_name}}
				</div>
				<ul class="transaction_type" role="listbox" v-if="trade_type">
					<li>{{trade_type}}</li>
				</ul>
			</div>
		</nuxt-link>
	</div>
</template>

<script>
import Header from "~/components/Header.vue";
import ListingConfig from "../mixins/listingConfig.js";
import { mapGetters } from "vuex";

export default {
	mixins: [ListingConfig],
	components: {
		Header
	},
	props: ["listing"],
	data() {
		return {
            listing_saved: this.$store.getters['watchList/getListing'](this.listing.id) !== undefined,
		};
	},
    watch: {
        listing_saved: function (saved) {
            this.$store.dispatch('watchList/setSaved', {listing: this.listing, status: saved});
        },
    },
    computed: {
		...mapGetters("auth", ["isAuthenticated", "loggedInUser"]),
        category() {
            let parent = this;
            return this.$store.getters['mainCategories/getCategories'].find(function(element) {return element.id === parent.listing.category_id;}) || {slug: '', name: ''};
        },
        trade_type(){
            let parent = this;

            let listingType = this.listingTradeType.find(function (element) {
                return element.id === parseInt(parent.listing.trade_type);
            });

            return listingType ? listingType.name : null;
		}
    },
};
</script>
