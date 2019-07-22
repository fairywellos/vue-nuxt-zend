<template>
	<div class="messages__page dashboard">
		<Header v-if="$device.isDesktop" />
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<div class="container">
			<div class="dashboard_content">
				<DashboardAside v-if="$device.isDesktop" />
				<fieldset>
					<div class="breadcrumb">
						<ul>
							<li>Messages</li>
						</ul>
					</div>
					<div id="cS3" class="list_body list_body_messages">
						<InboxMessage
							v-for="(conversation, key) in conversations"
							:status="conversation.status"
							:convID="conversation.id"
							:key="key"
							:conversationPhoto="conversation.photo"
							:firstName="conversation.first_name"
							:name="conversation.name"
							:text="conversation.text"
							:dateAdded="conversation.date_added"
						/>
					</div>
				</fieldset>
			</div>
		</div>
	</div>
</template>

<script>
	import Header from "~/components/Header";
	import HeaderMobile from "~/components/HeaderMobile";
	import DashboardAside from "~/components/DashboardAside";
	import Modal from "~/components/ModalTemplate";
	import InboxMessage from "~/components/InboxMessage";
	import bgUser from "~/assets/img/user.png";
	import bgUser2 from "~/assets/img/rating-user.png";
	import car from "~/assets/img/dashboard/message-car.png";
	import {mapGetters} from "vuex";

	export default {
		middleware: "auth",
		components: {
			Header,
			HeaderMobile,
			DashboardAside,
			Modal,
			InboxMessage
		},
		head: {
			title: "Messages"
		},
		async asyncData({store, params}) {
			let conversations = await store.dispatch('apiCalls/getUserConversations');
			return {conversations: conversations}
		},
		data() {
			return {
				bgUser,
				bgUser2,
				car,
				imageData: "",
				yourMessage: "",
				channel: null,
			};
		},
		computed: {
			...mapGetters("auth", ["isAuthenticated", "loggedInUser"])
		},
		methods: {
			bindMessage(){
				let self = this;
				this.channel = this.$pusher.channel('user-channel-' + this.$auth.user.id);
				this.channel.bind('message-event', data => {
					this.$store.dispatch('apiCalls/getUserConversations')
						.then((response) => {
							this.conversations = response;
						});
				});
			},
		},
		mounted() {
			this.bindMessage();
		},
		beforeDestroy() {
			//necessary for nuxt because it bind the message-event multiple times when changing the
			if (this.channel){
				this.channel.unbind('message-event');
			}
		}
	};
</script>

<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";

	fieldset {
		@include clearfix();
	}

	.breadcrumb {
		.action_buttons {
			.btn__primary {
				min-width: 166px;
				padding: 17px 23px;
				display: flex;
				align-items: center;
				justify-content: center;

				i {
					font-size: 16px;
					color: $white;
					margin-right: 4px;
				}
			}
		}
	}

	.messages__page {
		.list_body {
			width: 362px;
			height: calc(100vh - 154px);
			box-shadow: 10px 0 31px 5px rgba($black, 0.06);
			float: left;

			.message_preview {
				width: 362px;
			}
		}
	}
</style>
