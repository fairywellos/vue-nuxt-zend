import moment from "moment";
import searchBar from "~/assets/js/searchBar.js";


const editProfile = {
	extends: searchBar,
	methods: {
		previewImage: function(event) {
			// Reference to the DOM input element
			var input = event.target;
			// Ensure that you have a file before attempting to read it
			if (input.files && input.files[0]) {
				console.log(input.files[0]);
				// create a new FileReader to read this image and convert to base64 format
				var reader = new FileReader();
				// Define a callback function to run, when FileReader finishes its job
				reader.onload = e => {
					console.log(e);
					// Note: arrow function used here, so that "this.imageData" refers to the imageData of Vue component
					// Read image as base64 and set to imageData
					this.user.photo = e.target.result;
				};
				// Start the reader job - read file as a data url (base64 format)
				reader.readAsDataURL(input.files[0]);
				this.profilePhoto = input.files[0];
			}
		},
		updateProfile: function() {
			let parent = this;
			let formData = new FormData();
			for (var key in this.user) {
				formData.append(key, this.user[key]);
			}
			formData.append("photoFile", this.profilePhoto);
			try {
				this.$axios
					.post("user", formData, {
						headers: {
							"Content-Type": "multipart/form-data"
						}
					})
					.then(response => {
						this.$auth.fetchUser();
					});
			} catch (e) {
				// console.log()
				this.error = e.response.data.result;
				if (e.response.status === 403) {
				}
				if (e.response.status === 400) {
				}
			}
		},
		repostListing(listing) {
			try {
				this.$axios
					.post("listing/repost?XDEBUG_SESSION_START=1", {
						id: listing.id
					})
					.then((response) => {
						listing.status = 1;
						listing.availability =response.data.result.data;;
					})
					.catch(error => {
						console.log(error.response);
					});
			} catch (e) {
				console.log(e);
			}
		},
		copyToClipboard: function() {
			document.querySelector("#linkText").select();
			document.execCommand("copy");

			if (document.selection) {
				document.selection.empty();
			} else if (window.getSelection) {
				window.getSelection().removeAllRanges();
			}
		},
		currentTime: function() {
			let now = new Date();
			let TwentyFourHour = now.getHours();
			let hour = now.getHours();
			let min = now.getMinutes();
			let mid = "pm";
			let el = document.getElementById("currentTime");

			if (min < 10) {
				min = "0" + min;
			}
			if (hour > 12) {
				hour = hour - 12;
			}
			if (hour == 0) {
				hour = 12;
			}
			if (TwentyFourHour < 12) {
				mid = "am";
			}

			if (el) {
				el.innerHTML = hour + ":" + min + mid;

				setTimeout(this.currentTime, 1000);
			}
		},
		formatDate: function(date) {
			return moment(date).format("MM/DD/YYYY");
		},
		listingAvailable(date) {
			let currentDate = new Date();
			let newdate = new Date(date.date);
			return newdate > currentDate;
		}
	},
};

export default editProfile;
