const initialState = {
    listing : {
        id: 0,
        title: '',
        description: '',
        price: 0,
        qty: 0,
    }
};

const state = function () {
    return Object.assign({}, initialState)
};

const getters = {
    getListing (state) {
        return state.listing
    },
};

export default {
    namespaced: true,
    state,
    getters,
}