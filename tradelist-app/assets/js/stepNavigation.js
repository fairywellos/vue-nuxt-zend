const stepNavigation = {
	computed: {
		listingData() {
			return this.$store.getters['newListing/getData']
		},
	},
	methods: {
		updateListing(data) {
			this.$store.dispatch('newListing/updateListing', data);
		},
		nextStep() {
			this.$validator.validateAll(this.currentStep().ref).then((result) => {
				if (result) {
					this.activeStep++;
					scroll(0, 0);
				}
			});
		},
		prevStep() {
			this.activeStep--;
			scroll(0, 0);
		},
		currentStep() {
			let parent = this;
			return this.stepList.find(function (element) {
				return element.id === parent.activeStep;
			});
		},
		cancelSelections() {
			this.$store.commit('newListing/resetStateData');
		},
		async submitListing() {
			this.errors.clear('server');
			this.$validator.validateAll(this.stepList.map(step => {
				scope: step.ref
			})).then(result => {
				if (result) {
					this.$store.dispatch('newListing/sendListing')
						.then((response) => {
							console.log('am trimis');
						})
						.catch(serverError => {
							serverError = serverError.response.data.result;

							for (let fieldName in serverError) {
								this.errors.add({
									field: fieldName,
									msg: Object.values(serverError[fieldName]).join('; '),
									scope: 'server',
								});
							}
						});
				}
			});
		},
	}
};

export default stepNavigation;
