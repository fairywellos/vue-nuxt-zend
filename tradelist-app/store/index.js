import newListing from './modules/newListing'
import editListing from './modules/editListing'
import auth from './modules/auth'
import mainCategories from './modules/mainCategories'
import listingTags from './modules/listingTags'
import listingItem from './modules/listingItem'
import searchResults from './modules/searchResults'
import searchQueriesSaved from './modules/searchQueriesSaved'
import watchList from './modules/watchList'
import apiCalls from './modules/apiCalls'
import messages from './modules/messages'
import notifications from './modules/notifications'

export default {
    modules: {
        auth,
        newListing,
        editListing,
        mainCategories,
        listingTags,
        listingItem,
        searchResults,
        watchList,
		apiCalls,
        messages,
		searchQueriesSaved,
        notifications,
    },
    actions: {
        async nuxtServerInit ({ dispatch }, { req }) {
            await dispatch('mainCategories/updateCategoriesAsync');
            await dispatch('watchList/updateWatchListResultAsync');
            await dispatch('searchQueriesSaved/updateSearchQueriesResultAsync');
            await dispatch('messages/updateConversations');
            await dispatch('notifications/updateNotifications');
            await dispatch('auth/checkIfBetaLoggedIn');
        }
    },
    strict: 0,
}
