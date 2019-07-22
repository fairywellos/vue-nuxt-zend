
const actions = {
	getUserTradeOffers(store) {

		return this.$axios.get('trades/get-trade-offers')
			.then((response) => {
				return response.data.result.data;
			});

	},
	getUserAcceptedTrades(store) {

		return this.$axios.get('trades/get-accepted-trades')
			.then((response) => {
				return response.data.result.data;
			});

	},
	getUserConversations(store) {
		return this.$axios.get('conversation/all')
			.then((response) => {
				return response.data.result;
			});
	},
	getConversation(store, id) {
		return this.$axios.get('conversation', {
				params: {
					conversation_id: id
				}
			}).then((response) => {
				this.dispatch('messages/updateConversations');
				return response.data.result;
			});
	},
	getTrade(store,id) {

		return this.$axios.get('trades/get-trade',{
			params:{
				id:id,
			}
		}).then((response) => {
				return response.data.result.data;
			});
	},
	getMyProfile(store) {

		return this.$axios.get('user/my-profile')
			.then((response) => {
				return response.data.result;
			});
	},
	getUserProfile(store,id) {

		return this.$axios.get('user/profile',{
			params:{
				id:id,
			}
		}).then((response) => {
			return response.data.result;
		});
	},

	getBrowseLocal(store, query) {
		if (this.$geolocation.supported) {
			if(this.$geolocation.coords){
				return this.$axios.post("/browse-local", {
					lat: this.$geolocation.coords.latitude,
					long: this.$geolocation.coords.longitude,
				}).then((result) => {
					let location = result.data.result;

					let query_browse_local = {...query};
					query_browse_local['location'] = result.data.result.id;

					return this.$axios.get("/browse-local/all", {
						params: query_browse_local
					}).then((result => {
						return {
							location: location,
							allProducts: result.data.result
						};
					}));
				});
			}else {
				return this.$axios.get("/browse-local/get-default?limit=8").then((result => {
					return {
						location: false,
						allProducts: result.data.result
					};
				}));
			}
		}
		else {
			return this.$axios.get("/browse-local/get-default?limit=8").then((result => {
				return {
					location: false,
					allProducts: result.data.result
				};
			}));
		}
	},
	getDefaultListings(){
		return this.$axios.get("/browse-local/get-default?limit=24").then((result => {
			return {
				allProducts: result.data.result
			};
		}));
	},
	getUserTradePartners(){
		return this.$axios.get("/follow/get-trade-partners")
		.then(response => {
			return response.data.result;
		});
	},
	getLocationText(store,query) {
		if(query.location){
			return this.$axios.get("/location",{
				params :{
					id: query.location
				}
			}).then((response) => {
				return response.data.result.text;
			})
		}else {
			return "";
		}

	},
	getTradePartners(store) {
		return this.$axios.post("/find-trade-partners",{
			offset: 8
		}).then((response) => {
			return response.data.result;
		})
	}

};
export default {
	namespaced: true,
	actions
}
