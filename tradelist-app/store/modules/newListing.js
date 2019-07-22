import Vue from 'vue'
const initialState = {
	data: {
		main_category_id: 0,
		listing_tags: [],
		listingType: 1,
		title: '',
		description: '',
		price: 0,
		condition: '',
		tradeOrCash: 3,
		location: null,
		locationText: "",
		year: "",
		brand: "",
		color: "",
		material: "",
		notes: "",
		meta_tags: "",
		available_date: "",
		qty: 0,
		shipping: 0,
		localTradesOnly: 0,
		photos: [],
	},
};

const state = function () {
	return Object.assign({}, initialState)
};

const getters = {
	getData(state) {
		return state.data
	},
};

const mutations = {
	setData(state, value) {
		state.data = value
	},
	resetStateData(state) {
		Vue.set(state,'data',initialState.data);
		Vue.set(state.data,'listing_tags',[]);
		Vue.set(state.data,'photos',[]);
	}
};

const actions = {
	sendListing(store) {
		return this.$axios.post('listing', store.getters['getData']);
	},
	updateListing(store, newData) {
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
