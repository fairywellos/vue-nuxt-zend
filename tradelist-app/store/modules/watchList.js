const initialState = {
    listings : [],
    totalResults : 0,
    apiUpdated: false,
};

const state = function () {
    return Object.assign({}, initialState)
};

const getters = {
    getListing(state) {
        return function(listingId) {
            return state.listings.find(function (listing) {
                return parseInt(listing.id) === parseInt(listingId);
            });
        };
    },
    getListings (state) {
        return state.listings
    },
    getTotalResults (state) {
        return state.totalResults
    },
};

const mutations = {
    setListings (state, listings) {
        state.listings = listings;
    },
    addListing (state, listing) {
        state.listings.push(listing);
    },
    removeListing (state, removedListing) {
        state.listings = state.listings.filter(function( listing ) {
            return listing.id !== removedListing.id;
        });
    },
    setTotalResults (state, value) {
        state.totalResults = value;
    },
    increaseTotalResults(state){
        ++state.totalResults;
    },
    decreaseTotalResults(state){
        --state.totalResults;
    },
};

const actions = {
    updateWatchListResultAsync(store) {
        if(store.state.apiUpdated){
            return false;
        }

        let query  = {fields: 'id,title,description,main_photo,price,location,user_name,user_id,saved,trade_type'};

        return this.$axios.get('listing/feed/saved', {
            params: query
        })
        .then((response) => {
            store.commit('setListings', response.data.result.data);
            store.commit('setTotalResults', response.data.result.total_results);

            store.state.apiUpdated = true;
        })
        .catch(serverError => {
        });
    },
    setSaved(store, data){
        return this.$axios.post('listing/save', {
            listing_id : data.listing.id,
            status : data.status ? 1 : 0,
        })
        .then((response) => {
            if(data.status){
                store.commit('addListing', data.listing);
                store.commit('increaseTotalResults');
            }
            else{
                store.commit('removeListing', data.listing);
                store.commit('decreaseTotalResults');
            }
        })
        .catch(serverError => {
        });
    },
};

export default {
    namespaced: true,
    state,
    mutations,
    getters,
    actions
}

