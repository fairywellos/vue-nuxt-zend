
const initialState = {
    data : {},
};

const state = function () {
    return Object.assign({}, initialState)
};

const getters = {
    getData (state) {
        return state.data
    },
};

const mutations = {
    setData (state, value) {
        state.data = value
    },
    resetStateData(state){
        state.data = {}
    }
};

const actions = {
    setListingAsync({ commit, rootState, router}, listingId){
        return this.$axios
            .get('/listing', {
                params: {
                    id: listingId
                }
            })
            .then(res => {
                let listing = res.data.result.data;

                if(typeof listing !== "undefined" && listing.user.id === rootState.auth.user.id){
                    listing.listing_tags = [];
                    if(listing.tags.length > 0){
                        listing.listing_tags = listing.tags.map(function (tag) {
                            return tag.name;
                        });
                    }

                    listing.shipping = listing.shipping === true ? 1 : 0;
                    listing.localTradesOnly = listing.localTradesOnly === true ? 1 : 0;

                    commit('setData', listing);
                }
            })
    },
    sendListing(store){
        let data = store.getters['getData'];

        data.photos = data.photos.filter(function( fileObj ) {
            return fileObj["id"] === undefined;
        });

        return this.$axios.post('listing?XDEBUG_SESSION_START=1', data);
    },
    updateListing(store, newData){
        let currentData = store.getters['getData'];

        store.commit('setData', {...currentData, ...newData});
    }
};

export default {
    namespaced: true,
    state,
    mutations,
    getters,
    actions
}
