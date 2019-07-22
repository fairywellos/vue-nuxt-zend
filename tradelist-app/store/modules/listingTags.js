const state = function () {
    return {
        tags : []
    }
}

const getters = {
    getTags (state) {
        return state.tags
    },
}

const mutations = {
    setTags (state, value) {
        state.tags = value
    },
}

const actions = {
    searchTags(store, query) {
        if(query.length < 2){
             return false
        }

        return this.$axios.get('tag/search', {params: {
        	term: query
			}})
            .then((response) => {
                let currentTags = store.getters['getTags'];
                store.commit('setTags', [...new Set([...currentTags, ...response.data.result])]);
            });
    },
    addTag(store, val){
        let currentTags = store.getters['getTags'] || [];
        currentTags.push(val);

        store.commit('setTags', currentTags)
    }
}

export default {
    namespaced: true,
    state,
    mutations,
    getters,
    actions
}
