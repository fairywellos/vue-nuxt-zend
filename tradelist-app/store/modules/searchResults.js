const _ = require('lodash');

const initialState = {
    searches : [],
    searchResult : {
        query: {
            limit: 0,
            page: 0,
            user: 0,
            category: 0,
            fields: '',
            q: '',
            order_by: '',
            price_min: 0,
            price_max: 0,
        },
        favorite: 0,
        totalResults: 0,
        listings: []
    }
};

const state = function () {
    return Object.assign({}, initialState)
};

const getters = {
    getSearches (state) {
        return state.searches
    },
    getByQuery(state) {
        return function(query) {
            return state.searches.find(function (element) {
                return _.isEqual(element.query, query);
            });
        };
    },
    listings(state){
        return state.searchResult.listings;
    }
};

const mutations = {
    addSearchResult (state, searchResult) {
        state.searches.push(searchResult);
    },
    setSearchResult(state, searchResult){
        state.searches.map(element => {
            if (_.isEqual(element.query, searchResult.query) !== undefined) {
                return searchResult;
            } else {
                return element;
            }
        });
    }
};

const actions = {
    updateSearchResultNewPageAsync(store, query){
        let customQuery = query;
        query = {...query, fields: 'id,title,description,main_photo,price,location,user_name,user_id,user_photo,sample_account,saved,trade_type,category_id'};
        return this.$axios.get('listing/feed', {
            params: query
        })
        .then((response) => {
            // store.state.searchResult.query = customQuery;
            store.state.searchResult.totalResults += response.data.result.total_results;
            response.data.result.data.map(item=>{
                store.state.searchResult.listings.push(item);
            });
            window.isNewPageLoading = false
            store.commit('addSearchResult', store.state.searchResult)
        })
        .catch(serverError => {
            window.isNewPageLoading = false
        });
    },
    updateSearchResultAsync(store, query) {
        if(store.getters['getByQuery'](query) !== undefined){
            return false;
        }

        let customQuery = query;
        query = {...query, fields: 'id,title,description,main_photo,price,location,user_name,user_id,user_photo,sample_account,saved,trade_type,category_id'};

        
        return this.$axios.get('listing/feed', {
            params: query
        })
        .then((response) => {
            store.state.searchResult.query = customQuery;
            store.state.searchResult.totalResults = response.data.result.total_results;
            console.log("++++++++++++++++searchResult.js+++++++++++++++", response.data.result.data);
            store.state.searchResult.listings = response.data.result.data;

            store.commit('addSearchResult', store.state.searchResult)
        })
        .catch(serverError => {
            store.state.searchResult.query = customQuery;
            store.state.searchResult.listings = [];

            store.commit('addSearchResult', store.state.searchResult)
        });
    },
    setFavoriteSearch(state, query, status){
        let searchResult = store.getters['getByQuery'](query);
        searchResult.favorite = status;

        if(searchResult !== undefined){
            store.commit('setSearchResult', searchResult)
        }
    }
};

export default {
    namespaced: true,
    state,
    mutations,
    getters,
    actions
}

