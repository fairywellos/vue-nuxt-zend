const messagesJS = {
	// head: {
	// 	script: [{
	// 		src: 'https://cdn.jsdelivr.net/npm/perfect-scrollbar@0.6.14/dist/js/perfect-scrollbar.min.js',
	// 		body: true
	// 	}]
	// },
	data() {
		return {
			showConversationDetail: false,
			selectedTab: -1,
			channel: null,
		}
	},
	async asyncData({store, params, error}) {
		let conversations = await store.dispatch('apiCalls/getUserConversations');

		let selectedConversation = await store.dispatch('apiCalls/getConversation', params.id).catch(e => {
			error({
				statusCode: 403,
				message: e.response.data.result.message
			});
		});

		return {
			conversations: conversations,
			selectedConversation: selectedConversation
		}
	},
	methods: {
		messagesCustomScrollbarInit() {
			let cS4 = document.getElementById('cS4');

			if (cS4) {
				SimpleScrollbar.initEl(cS4);
			}
		},
		sendMessage() {
			let self = this;
			if (!this.yourMessage && !this.imageData) {
				return
			}

			try {
				this.$axios.post("conversation", {
					conversation_id: this.selectedConversation.id,
					message: this.yourMessage,
				}).then((response) => {
					let promise = new Promise(function (resolve, reject){
						self.selectedConversation.messages.push(response.data.result);
						resolve('done');
					});
					promise.then(function(){
						self.scrollToBottom();
						self.$store.dispatch('apiCalls/getUserConversations')
							.then((response) => {
								self.conversations = response;
							});
					});
				});
			} catch (e) {
				alert('something went wrong')
			}

			this.yourMessage = '';
			this.imageData = '';
		},
		previewImage: function (event) {
			// Reference to the DOM input element
			let self = this,
				input = event.target;
			// Ensure that you have a file before attempting to read it
			if (input.files && input.files[0]) {
				// create a new FileReader to read this image and convert to base64 format
				var reader = new FileReader();
				// Define a callback function to run, when FileReader finishes its job
				reader.onload = (e) => {
					// Note: arrow function used here, so that "this.imageData" refers to the imageData of Vue component
					// Read image as base64 and set to imageData
					this.imageData = e.target.result;

					this.sendMessage();

				}
				// Start the reader job - read file as a data url (base64 format)
				reader.readAsDataURL(input.files[0]);

				this.scrollToBottom();

			}
		},
		scrollToBottom() {
			let scrollB = document.querySelector('#cS4 .ss-content');

			if (scrollB) {
				scrollB.style.height = scrollB.scrollHeight - 600;
				scrollB.scrollTop = scrollB.scrollHeight;
			}
		},
		bindMessage(){
			let self = this;
			this.channel = this.$pusher.channel('user-channel-' + this.$auth.user.id);
			this.channel.bind('message-event', data => {
				if (this.selectedConversation.id == data.conversation){
					let promise = new Promise(function (resolve, reject){
						self.selectedConversation.messages.push(data);
						resolve('done');
					});
					promise.then(function(){
						self.scrollToBottom();
					});

					this.$axios.post("conversation/update-status", {
						conversation_id: self.selectedConversation.id
					}).then( () => {
						this.$store.dispatch('messages/updateConversations');
					})
				}

				this.$store.dispatch('apiCalls/getUserConversations')
					.then((response) => {
						this.conversations = response;
					});
			});
		},
	},
	mounted() {
		this.messagesCustomScrollbarInit();
		this.scrollToBottom();
		this.bindMessage();
	},
	beforeDestroy() {
		//necessary for nuxt because it bind the message-event multiple times when changing the
		if (this.channel){
			this.channel.unbind('message-event');
		}
	}
};

export default messagesJS;
