import ListingConfig from "./listingConfig.js";

const assocFilters = {
    main_category : 'formatCategory',
    listing_tags : 'formatTags',
    listing_type : 'formatListingType',
    condition : 'formatCondition',
    trade_or_cash : 'formatTradeOrCash',
    shipping : 'formatShipping',
	local_trades_only : 'formatLocalTradesOnly',
    listing_images : 'formatListingImages',
};

const formatListingValue = {
    methods: {
        formatListingValue: function (field, value) {
            if(assocFilters[field] !== undefined && this[assocFilters[field]] !== undefined){
                return this[assocFilters[field]](value);
            }

            return value;
        },

        formatCategory: function (value) {
            //@todo daca nu exista informatii in store atunci ar trebui sa folosim actionul getCategoriesAsync
            let categories = this.$store.getters['mainCategories/getCategories'];

            if(categories !== undefined) {
                let category = categories.find(function (response) {
                    return response.id === parseInt(value);
                });

                return category !== undefined ? category.name : '';
            }

            return value;
        },

        formatTags: function (value) {
            if(value !== undefined && Array.isArray(value)){
                return value.join(', ');
            }

            return value;
        },

        formatListingType: function (value) {
            let listingType = ListingConfig.data().listingType.find(function(response) {
                return response.id === parseInt(value);
            });

            return listingType !== undefined ? listingType.name : '';
        },

        formatCondition: function (value) {
            let condition = ListingConfig.data().listingCondition.find(function(response) {
                return response.id === parseInt(value);
            });

            return condition !== undefined ? condition.name : '';
        },

        formatTradeOrCash: function (value) {
            let listingTradeType = ListingConfig.data().listingTradeType.find(function(response) {
                return response.id === parseInt(value);
            });

            return listingTradeType !== undefined ? listingTradeType.name : '';
        },

        formatShipping: function (value) {
            let shippingStatus = ListingConfig.data().shippingStatus.find(function(response) {
                return response.id === parseInt(value);
            });

            return shippingStatus !== undefined ? shippingStatus.name : '';
        },

		formatLocalTradesOnly: function (value) {
			let localTradeStatus = ListingConfig.data().localTradeStatus.find(function(response) {
				return response.id === parseInt(value);
			});

			return localTradeStatus !== undefined ? localTradeStatus.name : '';
		},

        formatListingImages: function (value) {
            if(value !== undefined && Array.isArray(value)){
                return value.length + ' photos';
            }

            return value;
        },
    },
};

export default formatListingValue;
