import Vue from 'vue'

const state = function () {
    return {
        conversations : []
    }
}

const getters = {
    getConversations (state) {
        return state.conversations
    },
}

const mutations = {
    setConversations (state, value) {
        Vue.set(state,"conversations", value);
    },
}

const actions = {
     updateConversations(store) {
         if(!this.$storeAuthCheck()){
             return false;
         }

         return this.$axios.get('conversation/get-notification-conversations')
            .then((response) => {
                store.commit('setConversations', response.data.result)
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
