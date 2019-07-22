import Cookies from 'js-cookie'

const initialState = {
	userListings: [],
	userCurrentListing: {},
	isBetaAuth: false,
};
const state = function () {
	return Object.assign({}, initialState)
};
const getters = {
	isAuthenticated: function (state, getters, rootState) {
		return rootState.auth.loggedIn
	},

	loggedInUser: function (state, getters, rootState) {
		return rootState.auth.user
	},
	listings: function (state) {
		return state.userListings
	},
	currnetListing: function (state) {
		return state.userCurrentListing
	},
};

const mutations = {
	setUserListings(state, value) {
		state.userListings = value;
	},
	setUserCurrentListing(state, value) {
		state.userCurrentListing = value;
	},
	removeUserListing (state, id) {
		state.userListings = state.userListings.filter(function( listing ) {
			return listing.id !== id;
		});
	},
	setLoggedIn(state) {
		state.isBetaAuth = true;
	}

};

const actions = {
	updateUserListingsAsync(store) {

		return this.$axios.get('listing/feed/user', {
			params: {

				fields: "id,title,description,price,category_id,main_photo,trade_offers_count"
			}
		})
			.then((response) => {
				store.commit('setUserListings', response.data.result.data)
			});
	},
	updateUserCurrentListingAsync(store, id) {

		return this.$axios.get('listing/feed/user', {
			params: {

				fields: "id,title,main_photo,price,trade_offers_count",
				id: id,

			}
		})
			.then((response) => {
				store.commit('setUserCurrentListing', response.data.result.data[0])
			});
	},
	getUserCurrentListingTradesAsync(store, id) {
		store.dispatch('updateUserCurrentListingAsync', id);

		return this.$axios.get('listing/get-trades', {
			params: {
				id: id,
			}
		}).then((response) => {
				return response.data.result.data;
			});
	},
	setBetaLoginCookie(){
		Cookies.set("TradelistBetaLoggedIn","true",{expires:365});
	},
	checkIfBetaLoggedIn(store) {
		let isBetaLoggedIn =this.$cookies.get("TradelistBetaLoggedIn");
		if(isBetaLoggedIn){
			store.commit("setLoggedIn");
		}
	}


};
export default {
	namespaced: true,
	getters,
	mutations,
	actions,
	state
}
