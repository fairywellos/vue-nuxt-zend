const state = function () {
    return {
        all : []
    }
}

const getters = {
    getCategories (state) {
        return state.all
    },
	getCategory(state) {
		return function(id) {
			return state.all.find(function (element) {
				return element.id == id;
			});
		};
	}
}

const mutations = {
    setCategories (state, value) {
        state.all = value;
    },
}

const actions = {
     updateCategoriesAsync(store) {
         if(store.getters['getCategories'].length > 0){
             return false;
         }

         return this.$axios.get('main-category')
            .then((response) => {
                store.commit('setCategories', response.data.result)
            });
    },
}

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}
