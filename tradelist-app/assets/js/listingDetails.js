import axios from "axios";

const listingDetails = {
	head() {
		return {
			title: "Listing Details",
			meta: [
				{
					hid: "description",
					name: "description",
					content: "Listing details description"
				}
			]
		};
	},
	asyncData({params, error}) {
		return axios
			.get(process.env.API_URL + `/listing?id=${params.id}`)
			.then(res => {		
				gtag('event', 'conversion', {'send_to': 'AW-729655576/Am5fCI-N66YBEJjS9tsC'});
					var listitem = [           
					{
						"id": res.data.result.data.id,
						"name": res.data.result.data.title,
						"list": res.data.result.data.user.name,
						"category": res.data.result.data.mainCategory.name,
						"listing_type": res.data.result.data.listingType,
						"price": res.data.result.data.price,
						"location": res.data.result.data.location
					}
					];
					gtag('event', 'view_item', { items : listitem });
					fbq('track', 'ViewContent', {
					value: 1,
					currency: 'USD',
					content_ids: res.data.result.data.id,
					content_type: 'product',
					});  
				return {listing: res.data.result.data};
			})
			.catch(e => {
				error({
					statusCode: 404,
					message: e.response.data.result.message
				});
			});
	},
	methods: {
		makeAnOffer: function () {
			let listingIds = [];
			let self = this;
			this.checkedNames.forEach(function (value) {
				listingIds.push(self.allListings[value].id);
			});
			this.$axios
				.post("listing/trade-offer", {
					params: {
						user: this.$auth.user.id,
						offerType: this.mkOfferRadio,
						listingId: this.listing.id,
						listingsOffered: listingIds,
						cashOffer: this.cashOffer
					}
				})
				.then(response => {
					this.mkOfferRadio = 0;
					this.checkedNames = [];
					this.cashOffer = 0;
					this.tradeValue = 0;
					this.$gtag('event', 'conversion', {'send_to': 'AW-729655576/b2r6CMbM9qkBEJjS9tsC'});
					this.$fb('track', 'Lead', { value: 20, currency: 'USD'   });
					this.$fb('track', 'TradeOffer');					
					this.openModalMakeOfferSuccess();
				})
				.catch(error => {
					console.log(error.response.data.result.message);
				});
		},
		updateTradeValue: function () {
			if (this.mkOfferRadio == 0 || this.mkOfferRadio == 2) {
				var listingsCopy = this.allListings;
				this.tradeValue = 0;
				for (var i = 0; i < this.checkedNames.length; i++) {
					this.tradeValue += parseFloat(
						listingsCopy[this.checkedNames[i]].price
					);
				}
				this.tradeValue += parseFloat(this.cashOffer);
			} else if (this.mkOfferRadio == 1) {
				this.cashOffer = 0;
				this.checkedNames = [];
				this.tradeValue = this.listing.price;
			}
		},
		triggerModal: function () {
			this.$axios
				.get("listing/feed/user", {
					params: {
						fields: "id,title,price,main_photo",
						status: 1
					}
				})
				.then(response => {
					this.allListings = response.data.result.data;
				});
			this.mkOfferRadio = 2;
			this.showModalListings = true;
		},
		sliderSimilarListingInit() {
			let similarSlider = tns({
				container: ".similar_listing_slider",
				items: 3,
				slideBy: 3,
				speed: 500,
				gutter: 21,
				mouseDrag: true,
				autoplay: true,
				autoplayButtonOutput: false,
				autoplayTimeout: 10000,
				autoplayButton: false,
				responsive: {
					1600: {
						items: 4,
						slideBy: 4,
						speed: 500,
						gutter: 21,
						mouseDrag: true,
						autoplay: true,
						autoplayButtonOutput: false,
						autoplayTimeout: 10000,
						autoplayButton: false
					}
				}
			});
		},
		getPercentageChange: function (oldNumber, newNumber) {
			var decreaseValue = oldNumber - newNumber;
			var percentage = (decreaseValue / oldNumber) * 100;
			var round = percentage.toFixed(2);
			
			return round;
		},
		onTabclick(event) {
			event.preventDefault();
			
			var actives = document.querySelectorAll(".active");
			
			// deactivate existing active tab and panel
			for (var i = 0; i < actives.length; i++) {
				actives[i].className = actives[i].className.replace(
					"active",
					""
				);
			}
			
			// activate new tab and panel
			event.target.parentElement.className += " active";
			document.getElementById(
				event.target.href.split("#")[1]
			).className += " active";
		},
		openModalMakeOfferSuccess() {
			this.showModalMakeOfferSuccess = true;
		},
		closeModalMakeOfferSuccess() {
			this.showModalMakeOfferSuccess = false;
		},
		openModalListings() {
			this.showModalListings = true;
		},
		closeModalListings() {
			this.showModalListings = false;
		},
		openModalMessage() {
			this.showModalMessage = true;
		},
		closeModalMessage() {
			this.showModalMessage = false;
		},
		sendMessage() {
			let self = this;
			try {
				this.$axios.post("conversation", {
					receiver_id: this.listing.user.id,
					message: this.textMessage,
				}).then((response) => {
					self.textMessage = "";
					this.$gtag('event', 'conversion', {'send_to': 'AW-729655576/8cnPCJ_M9qkBEJjS9tsC'});
					this.$fb('track', 'Lead', { value: 2, currency: 'USD' });
					this.$fb('track', 'SendMessage');										
					self.closeModalMessage();
				});
			} catch (e) {
				alert('something went wrong')
			}
		}
	}
};

export default listingDetails;
