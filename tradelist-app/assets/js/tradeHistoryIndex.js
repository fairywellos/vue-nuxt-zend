const tradeHistoryIndex = {

	methods: {
		openModalRating(index,edit) {
			let userId = this.trades[index].listingUser;
			if (this.$auth.user.id == this.trades[index].listingUser) {
				userId = this.trades[index].tradeUser
			}
			this.$axios.get('user', {params: {id: userId}}).then((response => {
				this.tradeRatingUser = response.data.result.data;
			}));
			this.tradeRatingId = this.trades[index].id;
			if(edit){
				this.checkedStar = parseInt(this.trades[index].rating);
				this.review = this.trades[index].ratingReview;
			}
			this.showModalRating = true;
		},
		closeModalRating() {
			this.showModalRating = false;
			this.checkedStar = 0;
			this.tradeRatingUser = "";
			this.review = "";
			this.tradeRatingId = 0;

		},
		sendReview() {
			try {
				this.$axios.post("user/review", {
					rating: this.checkedStar,
					review: this.review,
					tradeId: this.tradeRatingId
				});

				this.refreshPage();
			} catch (e) {

			}
		},
		refreshPage() {
			window.location.reload();
		},
	}
};


export default tradeHistoryIndex;
