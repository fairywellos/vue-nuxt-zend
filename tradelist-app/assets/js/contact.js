
const contactJs = {
	methods: {
		sendMail(){
			let self = this;
			this.$axios.post("/contact",{
				first_name: this.firstName,
				name: this.lastName,
				email: this.email,
				phone_number: this.phoneNumber,
				company: this.companyName,
				message: this.message,
				form_type: this.form_type,
			}).then((response) => {
				this.clearErrors();
				this.clearForm();
				console.log(response);
			}).catch(error => {
				this.clearErrors();
				let errorResult = error.response.data.result;
				if(error.response.status === 400 && errorResult.validation_messages){
					let validation_messages = errorResult.validation_messages
					for (var validation_message in validation_messages) {
						if (validation_messages.hasOwnProperty(validation_message)) {
							self.formErrors[validation_message] = validation_messages[validation_message][Object.keys(validation_messages[validation_message])[0]];
						}
					}
				}
			})
		},
		clearErrors() {
			this.formErrors = {
				first_name: false,
				name: false,
				email: false,
				phone_number: false,
				company: false,
				message: false,
			};
		},
		clearForm() {
			this.message = "";
			this.companyName = "";
			this.phoneNumber = "";

			if(!this.$auth.loggedIn){
				this.firstName = "";
				this.lastName = "";
				this.email = "";
			}
		}



	},
};

export default contactJs;
