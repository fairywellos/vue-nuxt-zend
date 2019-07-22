import Vue from 'vue'

const state = function () {
    return {
        notifications : []
    }
}

const getters = {
    getNotifications (state) {
        return state.notifications
    },
}

const mutations = {
    setNotifications (state, value) {
        Vue.set(state,"notifications", value);
    },

}

const actions = {
     updateNotifications(store) {
         if(!this.$storeAuthCheck()){
             return false;
         }

         return this.$axios.get('notification')
            .then((response) => {
                store.commit('setNotifications', response.data.result)
            });
    },
    updateAllNotifications(store) {
        if(!this.$storeAuthCheck()){
            return false;
        }

        return this.$axios.get('notification/all')
            .then((response) => {
                store.commit('setNotifications', response.data.result)
            });
    },
	updateNotificationStatus(store,id) {
     	console.log("teste");
		this.$axios.post('notification/update-status',{
			notification_id: id
		}).then((response) => {
			store.dispatch('updateAllNotifications');
		});
	}
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}
