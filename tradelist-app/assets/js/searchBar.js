let typingTimer;
let doneTypingInterval = 1000;

const searchBarJs = {
	methods: {
		nameWithLang({
						 name,
						 abbreviation
					 }) {
			return `${name},  ${abbreviation}`
		},
		openDropdown(e) {
			let parent = this;
			if (this.states.length === 0) {
				this.$axios.get("location/all").then((response) => {
					parent.states = response.data.result;

					e.classList.add('is-visible');

					if (e.value == '') {
						e.classList.add('is-empty');
					} else {
						e.classList.remove('is-empty');
					}
				});
			} else {
				e.classList.add('is-visible');

				if (e.value == '') {
					e.classList.add('is-empty');
				} else {
					e.classList.remove('is-empty');
				}
			}

		},
		closeDropdown() {
			document.querySelector('input.is-visible').classList.remove('is-visible');
		},
		updateVal(e) {
			let parent = this;
			let query = e.value;
			clearTimeout(typingTimer);
			typingTimer = setTimeout(function () {
				parent.$axios.get("location/search", {
					params: {
						query: query
					}
				}).then((response) => {
					parent.states = response.data.result;
				});
			}, doneTypingInterval);


			if (e.value !== '') {
				e.classList.remove('is-empty');
			} else {
				e.classList.add('is-empty');
			}
		},
		closeEvent() {
			window.addEventListener('mouseup', function (event) {
				let self = event;
				let visibleInput = document.querySelector('input.is-visible');
				let targetE = document.querySelectorAll('.location_dropdown');

				if (visibleInput) {
					if (self.target != targetE && self.target.parentNode.parentNode != targetE) {
						document.querySelector('input.is-visible').classList.remove('is-visible');
					}
				}
			});
		},
		removeRecents(e) {
			e.parentNode.parentNode.parentNode.removeChild(e.parentNode.parentNode);
		},
		getGeolocation() {
			if (this.$geolocation.supported && this.browserLocation.set === false) {
				if(this.$geolocation.coords){
					var self = this;
					this.browserLocation.set = true;
					this.browserLocation.latitude = this.$geolocation.coords.latitude;
					this.browserLocation.longitude = this.$geolocation.coords.longitude;
					this.$axios.get("location/search", {
						params: {
							coords: true,
							latitude: this.browserLocation.latitude,
							longitude: this.browserLocation.longitude
						}
					}).then((response) => {
						let location = {
							id: response.data.result.id,
							name: response.data.result.city,
							abbreviation: response.data.result.state
						};
						self.browserLocation.location = location;
						self.locationText = location.name + ", " + location.abbreviation;
						self.location = location.id;
						if(self.user){
							self.user.locationText = self.locationText;
							self.user.location = self.location;
						}

					});
				}else {
					alert("You need to activate your location in order for this page to work");
				}
			}
			else if(this.browserLocation.set) {
				this.locationText = this.browserLocation.location.name + ", " + this.browserLocation.location.abbreviation;
				this.location = this.browserLocation.location.id;
				if(this.user){
					this.user.locationText = this.locationText;
					this.user.location = this.location;
				}
			}else {
				console.log("Your browser does not support geolocation");
			}

		},
		openMenuSearch() {
			this.searchToggleVisible = true;

			let inputFocus = document.querySelector('.search_toggle input');

			setTimeout(function () {
				inputFocus.focus();
			}, 500);

		},
		closeMenuSearch() {
			this.searchToggleVisible = false;
		},
		returnKeySearch() {
			this.$router.push({path: '/search', query: {...this.$route.query, q: this.term, location: this.location}});
		    this.$gtag('event', 'search', { search_term : this.term });
		    this.$fb('track', 'Search', { search_string: this.term });
		}
	},
};

export default searchBarJs;
