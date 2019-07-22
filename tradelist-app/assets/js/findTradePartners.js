let typingTimer;
let doneTypingInterval = 1000;

const findTradePartners = {
	methods: {
		openCategory: function(event) {
			this.activeFilterCat = 1;

			if (this.activeFilterPrice !== 2) {
				this.activeFilterPrice = false;
			}

			if (this.activeFilterLocation !== 2) {
				this.activeFilterLocation = false;
			}
		},
		clearCategory: function(el) {
			this.mainCatsSelected = [];
			this.activeFilterCat = false;

			this.searchPartner();
		},
		applyCategory: function(event) {
			let self = this;

			if (
				this.mainCatsSelected === undefined ||
				this.mainCatsSelected.length == 0
			){
				this.activeFilterCat = 1;
			} else {
				setTimeout(function() {
					self.activeFilterCat = 2;
				}, 100);
			}

			this.searchPartner();
		},
		openPrice: function(el) {
			this.activeFilterPrice = 1;

			if (this.activeFilterCat !== 2) {
				this.activeFilterCat = false;
			}

			if (this.activeFilterLocation !== 2) {
				this.activeFilterLocation = false;
			}
		},
		applyPrice(el) {
			let self = this;

			if (this.selectedPriceRange[0] || self.selectedPriceRange[1]) {
				this.searchPartner();

				setTimeout(function() {
					self.activeFilterPrice = 2;
				}, 100);
			} else {
				this.activeFilterPrice = false;
			}
		},
		resetPrice: function(el) {
			let rangeSliderPartners = document.getElementById(
				"rangeSliderPartners"
			);

			this.activeFilterPrice = false;
			this.selectedPriceRange[0] = 0;
			this.selectedPriceRange[1] = 0;

			this.searchPartner();
		},
		applyLocation: function() {
			let self = this;

			if (this.activeFilterLocation) {
				this.searchPartner();

				setTimeout(function() {
					self.activeFilterLocation = 2;
				}, 100);
			} else {
				this.activeFilterLocation = 1;
			}

		},
		openLocation: function(el) {
			this.activeFilterLocation = 1;

			if (this.activeFilterPrice !== 2) {
				this.activeFilterPrice = false;
			}

			if (this.activeFilterCat !== 2) {
				this.activeFilterCat = false;
			}
		},
		clearLocation: function(el) {
			this.activeFilterLocation = false;
			this.location = false;
			this.city = "";
			this.locationText = "";
			this.pickedVal = false;
			this.searchPartner();
		},
		initRangeSliderPartners: function() {
			let rangeSliderPartners = document.getElementById(
				"rangeSliderPartners"
			);
			if (rangeSliderPartners) {
				let minVal = document.querySelector("#minVal");
				let maxVal = document.querySelector("#maxVal");
				let that = this;

				noUiSlider.create(rangeSliderPartners, {
					start: this.selectedPriceRange.length > 0 ?
						this.selectedPriceRange : this.startPrice,
					connect: true,
					step: 25,
					range: {
						min: 0,
						max: 1025
					}
				});

				rangeSliderPartners.noUiSlider.on("update", function(values) {
					let collectVal;

					collectVal = values;
					that.selectedPriceRange = collectVal;

					minVal.innerHTML = Math.round(values[0]);

					if (Math.round(values[1]) > 1000) {
						maxVal.innerHTML = "1000+";
					} else {
						maxVal.innerHTML = Math.round(values[1]);
					}
				});
			}
		},
		addClassFilter: function() {
			let buttons = $(".trade_simple_search .btn");

			buttons.on("click", function() {
				buttons.parent().removeClass("is_active");
				$(this)
					.parent()
					.addClass("is_active");
			});
		},
		detectClickOutside: function(event) {
			let self = this;

			document.addEventListener("click", function(event) {
				if (self.activeFilterCat === 1) {
					if (
						document
						.querySelector("li.is_active")
						.contains(event.target)
					) {
						return;
					} else {
						if (self.mainCatsSelected.length > 0) {
							self.activeFilterCat = 2;
						} else {
							self.activeFilterCat = false;
						}
						self.searchPartner();
					}
				}

				if (self.activeFilterLocation === 1) {
					if (document.querySelector("li.is_active").contains(event.target)) {
						return;
					} else {
						if (self.locationText) {
							self.activeFilterLocation = 2;
							self.searchPartner();
						} else {
							self.activeFilterLocation = false;
						}
					}
				}

				if (self.activeFilterPrice === 1) {
					if (document.querySelector("li.is_active").contains(event.target)) {
						return;
					} else {
						if (self.selectedPriceRange[0].length > 0 || self.selectedPriceRange[1].length > 0) {
							self.activeFilterPrice = 2;
							self.searchPartner();
						} else {
							self.activeFilterPrice = false;
						}
					}
				}
			});
		},
		searchPartner: function() {
			this.$axios
				.post("/find-trade-partners", {
					search: this.searchBar,
					location_id: this.location,
					main_category_id: this.mainCatsSelected,
					selected_price_range: this.selectedPriceRange,
				})
				.then(response => {
					this.tradersData = response.data.result;
				});
		},
		nameWithLang({
			name,
			abbreviation
		}) {
			return `${name},  ${abbreviation}`;
		},
		openDropdown(e) {
			let parent = this;
			if (this.states.length === 0) {
				this.$axios.get("location/all").then(response => {
					parent.states = response.data.result;

					e.classList.add("is-visible");

					if (e.value == "") {
						e.classList.add("is-empty");
					} else {
						e.classList.remove("is-empty");
					}
				});
			} else {
				e.classList.add("is-visible");

				if (e.value == "") {
					e.classList.add("is-empty");
				} else {
					e.classList.remove("is-empty");
				}
			}
		},
		closeDropdown() {
			document
				.querySelector("input.is-visible")
				.classList.remove("is-visible");
		},
		updateVal(e) {
			let parent = this;
			let query = e.value;
			clearTimeout(typingTimer);
			typingTimer = setTimeout(function() {
				parent.$axios
					.get("location/search", {
						params: {
							query: query
						}
					})
					.then(response => {
						parent.states = response.data.result;
					});
			}, doneTypingInterval);

			this.city = e.value;
		},
		removeRecents(e) {
			e.parentNode.parentNode.parentNode.removeChild(
				e.parentNode.parentNode
			);
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
						self.pickedVal = location.name + ", " + location.abbreviation;
						self.location = location.id;

					});
				}else {
					alert("You need to activate your location in order for this page to work");
				}
			}
			else if(this.browserLocation.set) {
				this.locationText = this.browserLocation.location.name + ", " + this.browserLocation.location.abbreviation;
				this.location = this.browserLocation.location.id;
			}else {
				console.log("Your browser does not support geolocation");
			}

		},
		openMenuSearch() {
			this.searchToggleVisible = true;

			let inputFocus = document.querySelector(".search_toggle input");

			setTimeout(function() {
				inputFocus.focus();
			}, 500);
		},
		closeMenuSearch() {
			this.searchToggleVisible = false;
		},
		returnKeySearch() {
			this.$router.push({
				path: "/search",
				query: {
					...this.$route.query,
					q: this.term,
					location: this.location
				}
			});
		}
	},
	mounted() {
		this.addClassFilter();
		this.initRangeSliderPartners();
		this.detectClickOutside();
	}
};

export default findTradePartners;
