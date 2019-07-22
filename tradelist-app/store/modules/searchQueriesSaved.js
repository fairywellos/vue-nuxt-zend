const _ = require('lodash');

const initialState = {
    queries : [],
    apiUpdated: false,
};

const state = function () {
    return Object.assign({}, initialState)
};

const getters = {
	getByQuery(state) {
		return function(query) {
			return state.queries.find(function (element) {
				return _.isEqual(element, query);
			});
		};
	},
    getQueries (state) {
        return state.queries
    },
};

const mutations = {
    setQueries (state, queries) {
        state.queries = queries;
    },
    addQuery (state, query) {
        state.queries.push(query);
    },
    removeQuery (state, removedQuery) {
        state.queries = state.queries.filter(function( element ) {
			return !_.isEqual(element, removedQuery);
        });
    },
};

const actions = {
    updateSearchQueriesResultAsync(store) {
        if(store.state.apiUpdated){
            return false;
        }

        return this.$axios.get('saved-search')
        .then((response) => {
            store.commit('setQueries', response.data.result.data);

            store.state.apiUpdated = true;
        })
        .catch(serverError => {
        });
    },
    setSaved(store, data){
        return this.$axios.post('saved-search', {
			query : Object.keys(data.query).map(key => key + '=' + data.query[key]).join('&'),
            status : data.status ? 1 : 0,
        })
        .then((response) => {
            if(data.status){
                store.commit('addQuery', data.query);
            }
            else{
                store.commit('removeQuery', data.query);
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

