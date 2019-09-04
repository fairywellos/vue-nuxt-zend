const headerJs = {
	head: {
		script: [{
				src: "https://cdnjs.cloudflare.com/ajax/libs/cash/3.0.0-beta.1/cash.min.js",
				body: true
			},
			{
				src: "https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.4/js/tether.min.js",
				body: true
			},
			{
				src: "https://cdnjs.cloudflare.com/ajax/libs/tether-select/1.1.1/js/select.min.js",
				body: true
			},
			{
				src: "https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/12.1.0/nouislider.min.js",
				body: true
			},
			{
				src: "https://cdn.jsdelivr.net/npm/simple-scrollbar@0.4.0/simple-scrollbar.min.js",
				body: true
			}
		]
	},
	methods: {
		async register() {
			try {
				await this.$axios.post("register", {
					first_name: this.first_name,
					name: this.name,
					email: this.email,
					username: this.display_name,
					password: this.password
				});
				this.$gtag('event', 'conversion', {'send_to': 'AW-729655576/DNu_COK64qYBEJjS9tsC'});
				this.$gtag('event', 'sign_up');
				this.$fb('track', 'CompleteRegistration', { value: 4, currency: 'USD' });  
				this.showModalSignUp = false;
				this.showModalEmailCode = true;
			} catch (e) {
				this.error = e.response.data.result.message;

				if (e.response.data.result.validation_messages.email !== "undefined") {
					this.errorEmailAuth = e.response.data.result.validation_messages.email[Object.keys(e.response.data.result.validation_messages.email)[0]];
				}
			}
		},
		async login() {
			try {
				await this.$auth.loginWith("local", {
					data: {
						email: this.email,
						password: this.password
					}
				});
				this.$gtag('event', 'conversion', {'send_to': 'AW-729655576/BnsPCNSv3qYBEJjS9tsC'});
				this.$gtag('event', 'login');
				this.$fb('track', 'login');
				this.showModalLogin = false;
				this.refreshPage();

			} catch (e) {
				this.error = e.response.data.result;
				if (e.response.status === 403) {
					this.showModalLogin = false;
					this.showModalEmailCode = true;
				}
				if (e.response.status === 400) {
					this.errorEmailLog = e.response.data.result.message;
				}
			}
		},
		async logout() {
			await this.$auth.logout();

			this.refreshPage();
		},
		async activate() {
			try {
				await this.$axios.post("activate", {
					email: this.email,
					code: this.digit1 +
						this.digit2 +
						this.digit3 +
						this.digit4 +
						this.digit5 +
						this.digit6
				});

				this.showModalEmailCode = false;
				await this.$auth.loginWith("local", {
					data: {
						email: this.email,
						password: this.password
					}
				});
			} catch (e) {
				this.error = e.response.data.result;

				if (e.response.status === 400) {
					this.resendActivationEmail = false;
					this.errorActivateCode = e.response.data.result.message[0];
				}
			}
		},
		async resendActivationCode() {
			try {
				let response = await this.$axios.post(
					"resend-activation-code", {
						email: this.email
					}
				);

				if (response) {
					this.errorActivateCode = false;
					this.resendActivationEmail = response.data.result.message;
					this.digit1 = "";
					this.digit2 = "";
					this.digit3 = "";
					this.digit4 = "";
					this.digit5 = "";
					this.digit6 = "";
				}
			} catch (e) {
				this.error = e.response.data.result;

				if (
					e.response.data.result.validation_messages.email
					.emailAddressInvalidHostname !== "undefined"
				) {
					this.errorEmailLog =
						e.response.data.result.validation_messages.email.emailAddressInvalidHostname;
				}
			}
		},
		focusSiblings: function(event) {
			if (event.target.value !== "" && event.keyCode !== 8) {
				if (event.target.nextElementSibling) {
					event.target.nextElementSibling.focus();
				}
			} else if (event.keyCode === 8) {
				if (event.target.previousElementSibling) {
					event.target.previousElementSibling.focus();
				}
			}
			// this.splitPaste(event);
		},
		splitPaste: function(event) {
			let copiedCode,
				groupInputs = document.querySelectorAll(
					".email_code .form_group input"
				);

			navigator.permissions
				.query({
					name: "clipboard-write"
				})
				.then(result => {
					if (result.state == "granted" || result.state == "prompt") {
						navigator.clipboard
							.readText()
							.then(cText => {
								copiedCode = cText.slice(0, groupInputs.length);

								for (var stringIndex in copiedCode) {
									groupInputs[stringIndex].value =
										copiedCode[stringIndex];
								}
							})
							.catch(err => {
								console.log(
									"Failed to read clipboard contents: ",
									err
								);
							});
					}
				});
		},
		closeNotifications: function() {
			this.activeNotification = false;
		},
		detectClickOutside: function(event) {
			let box = document.querySelector(".nav_notifications"),
				self = this;

			document.addEventListener("click", function(event) {
				if (event.target.closest(".nav_notifications")) return;
				self.activeNotification = false;
			});
		},
		customScrollbarInit() {
			let cS1 = document.getElementById("cS1");
			let cS2 = document.getElementById("cS2");
			let cS3 = document.getElementById("cS3");

			let mainScroll = document.getElementById("mainScroll");

			if (cS1) {
				SimpleScrollbar.initEl(cS1);
			}

			if (cS2) {
				SimpleScrollbar.initEl(cS2);
			}

			if (cS3) {
				SimpleScrollbar.initEl(cS3);
			}

			// if (mainScroll) {
			// 	new SimpleBar(mainScroll);
			// }
		},
		sliderInit() {
			let sliders = document.querySelectorAll(".card_item_slider");

			if (sliders) {
				let forEach = function(array, callback, scope) {
					for (var i = 0; i < array.length; i++) {
						callback.call(scope, i, array[i]);
					}
				};

				forEach(sliders, function(index, value) {
					let thumbnail = value.dataset.name;
					let slider = tns({
						container: value,
						items: 2,
						slideBy: 2,
						speed: 700,
						gutter: 21,
						mouseDrag: true,
						autoplay: true,
						autoplayButtonOutput: false,
						autoplayTimeout: 10000,
						responsive: {
							1260: {
								items: 3,
								slideBy: 3,
								speed: 500
							},
							1600: {
								items: 4,
								slideBy: 4
							}
						}
					});
				});
			}
		},
		openModalSignUp() {
			this.showModalSignUp = true;
		},
		closeModalSignUp() {
			this.showModalSignUp = false;
		},
		openModalLogin() {
			this.showModalLogin = true;
		},
		closeModalLogin() {
			this.showModalLogin = false;
		},
		openModalEmailCode() {
			this.showModalEmailCode = true;
		},
		closeModalEmailCode() {
			let groupInputs = document.querySelectorAll(
				".email_code .form_group input"
			);

			for (let i = 0; i < groupInputs.length; i++) {
				console.log(groupInputs[i].value);
				groupInputs[i].value = "";
			}

			this.errorActivateCode = false;
			this.resendActivationEmail = false;
			this.showModalEmailCode = false;
			this.digit1 = "";
			this.digit2 = "";
			this.digit3 = "";
			this.digit4 = "";
			this.digit5 = "";
			this.digit6 = "";
		},
		switchModalLogin() {
			this.showModalSignUp = false;
			this.showModalLogin = true;
		},
		switchModalSignUp() {
			this.showModalLogin = false;
			this.showModalSignUp = true;
		},
		refreshPage() {
			window.location.reload();
		},
		changeHover() {
			this.customScrollbarInit();
			this.wasHovered = true;
		},
		toggleActiveNotification(num) {
			this.activeNotification = num;
		},
		toggleMenuMobile() {
			this.menuVisible = !this.menuVisible;
		},
		bindHeaderMessage() {
			this.$pusher.bind("message-event", data => {
				this.$store.dispatch("messages/updateConversations");
			});

			this.$pusher.bind("notification-event", data => {
				if (this.seeAllNotifications) {
					this.$store.dispatch(
						"notifications/updateAllNotifications"
					);
				} else {
					this.$store.dispatch("notifications/updateNotifications");
				}
			});
		},
		toggleSeeAllNotifications() {
			this.seeAllNotifications = !this.seeAllNotifications;

			if (this.seeAllNotifications) {
				this.$store.dispatch("notifications/updateAllNotifications");
			} else {
				this.$store.dispatch("notifications/updateNotifications");
			}
		},
		markAsRead(notification) {
			if(parseInt(notification.status) !== 1 ){

				this.$store.dispatch("notifications/updateNotificationStatus",notification.id);
			}
		}
	},
	mounted() {
		this.customScrollbarInit();
		this.sliderInit();

		if(this.$auth.loggedIn){
			this.$pusher.subscribe("user-channel-" + this.$auth.user.id);
			this.bindHeaderMessage();
		} else {
			this.$pusher.disconnect();
		}
	},
	beforeDestroy() {
		//necessary for nuxt because it bind the message-event multiple times when changing the
		this.$pusher.unbind("message-event");
	}
};

export default headerJs;
