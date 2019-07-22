<template>
	<div class="messages__page dashboard">
		<Header v-if="$device.isDesktop"/>
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<div class="container">
			<div class="dashboard_content">
				<DashboardAside v-if="$device.isDesktop"/>
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
							:selectedConv="selectedConversation.id"
							:key="key"
							:conversationPhoto="conversation.photo"
							:firstName="conversation.first_name"
							:name="conversation.name"
							:text="conversation.text"
							:dateAdded="conversation.date_added"
						/>
					</div>
					<div class="chat" id="cS4" ref="chatArea">
						<div>
							<ul class="messages_content">
								<li
									v-for="msg in selectedConversation.messages"
									:key="msg.id"
									:class="'sender_message ' + (msg.user.user_id === loggedInUser.id ? 'to' : 'from')"
								>
									<div
										v-if="msg.user.photo"
										class="avatar"
										:title="msg.user.publicName"
										:style="{ backgroundImage: 'url('+ msg.user.photo + ')' }"
									></div>
									<div v-else class="avatar" :class="msg.user.photo" :title="msg.user.publicName"></div>
									<div class="message">
										<p>{{ msg.text }}</p>
										<img v-if="msg.image" :src="msg.image" :alt="msg.author">
									</div>
								</li>
							</ul>
							<form @submit.prevent="sendMessage()" class="message_box">
								<div class="input_icon">
									<div class="input_upload">
										<label for="chatUploadPhoto">
											<i class="icon-upload"></i>
										</label>
										<input type="file" id="chatUploadPhoto" @change="previewImage" accept="image/*">
									</div>
									<input
										type="text"
										v-model="yourMessage"
										v-on:keyup.enter="scrollToBottom()"
										placeholder="Type to reply..."
									>
								</div>
								<button
									type="submit"
									role="button"
									aria-label="send"
									class="btn btn_send"
									v-on:mousemove="scrollToBottom()"
									@click.exact="scrollToBottom()"
								>
									<i class="icon-reply"></i>
								</button>
							</form>
						</div>
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
	import Modal from "~/components/ModalTemplate.vue";
	import InboxMessage from "~/components/InboxMessage";
	import messagesJS from "~/assets/js/messages.js";
	import bgUser from "~/assets/img/user.png";
	import bgUser2 from "~/assets/img/rating-user.png";
	import car from "~/assets/img/dashboard/message-car.png";
	import { mapGetters } from "vuex";

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
		data() {
			return {
				bgUser,
				bgUser2,
				car,
				imageData: "",
				yourMessage: ""
			};
		},
		computed: {
			...mapGetters("auth", ["isAuthenticated", "loggedInUser"])
		},
		extends: messagesJS
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

	.user_image {
		margin-right: 16px;
	}

	.chat {
		padding: 0 27px 97px;
		margin: 5px 0;
		width: calc(100% - 362px);
		float: right;
		height: calc(100vh - 164px);
		position: relative;
	}

	.message_box {
		width: calc(100% - 54px);
		display: flex;
		background: $white;
		box-shadow: 0 1px 4px rgba(53, 56, 63, 0.1);
		border-radius: 3px;
		position: absolute;
		bottom: 27px;
		left: 27px;

		.input_icon {
			width: calc(100% - 69px);
			position: relative;

			.input_upload {
				position: absolute;
				right: 30px;
				top: 50%;
				transform: translateY(-50%);
				cursor: pointer;

				input[type="file"] {
					visibility: hidden;
					position: absolute;
					z-index: -99;
					left: -999px;
					opacity: 0;
				}
			}

			i {
				font-size: 20px;
				cursor: pointer;
			}
		}

		input[type="text"],
		.btn_send {
			border: none;
			border-radius: 0;
		}

		input[type="text"] {
			height: 70px;
			width: 100%;
			display: block;
			padding: 25px 55px 25px 25px;
			border-top-left-radius: 3px;
			border-bottom-left-radius: 3px;
		}

		.btn_send {
			display: flex;
			align-items: center;
			justify-content: center;
			width: 69px;
			border-left: 1px solid #ebedf4;
			border-top-right-radius: 3px;
			border-bottom-right-radius: 3px;
			transition: 0.2s $bezier_ease_in_out;

			&:active {
				opacity: 0.5;
			}

			i {
				font-size: 18px;
				color: $shamrock;
			}
		}
	}

	.messages_content {
		overflow: hidden;
	}

	.sender_message {
		width: 100%;
		display: flex;
		align-items: center;
		margin-bottom: 29px;
		margin-bottom: 0px;
		animation: slideInUp 0.5s 1;

		&:first-child {
			margin-top: 20px;
		}

		&:last-child {
			margin-bottom: 20px;
		}

		> span {
			display: flex;
			width: 100%;
		}

		.avatar {
			width: 50px;
			height: 50px;
			border-radius: 50%;
			background-size: cover;
			background-repeat: no-repeat;
			background-position: center;
			margin-right: 36px;
			background-image: url("~assets/img/user_default.png");
		}
	}

	.message {
		@include clearfix();
		max-width: 436px;

		img {
			border: 5px solid $white;
			border-radius: 3px;
			display: block;
			max-width: 370px;
			clear: both;
			margin: 10px 0;
		}

		p {
			@include open_sans(600);
			font-size: 16px;
			line-height: 24px;
			padding: 14px;
			margin: 0 0 10px;
			display: inline-block;
			border-radius: 5px;
			clear: both;
			position: relative;
			word-break: break-all;

			&:last-child {
				&:after {
					content: "";
					position: absolute;
					top: 50%;
					transform: translateY(-50%);
				}
			}
		}
	}

	.from {
		> span {
			flex-direction: row;
		}

		.message {
			p {
				background: $shamrock;
				color: $white;
				float: left;

				&:after {
					left: -10px;
					width: 0;
					height: 0;
					border-top: 6px solid transparent;
					border-bottom: 6px solid transparent;

					border-right: 10px solid $shamrock;
				}
			}

			img {
				float: left;
			}
		}
	}

	.to {
		flex-direction: row-reverse;

		> span {
			flex-direction: row-reverse;
		}

		.avatar {
			margin-left: 36px;
			margin-right: 20px;
		}

		.message {
			p {
				background: $white;
				color: #31394d;
				box-shadow: 0 1px 4px rgba(229, 233, 242, 0.5);
				max-width: 500px;
				float: right;

				&:after {
					right: -10px;
					width: 0;
					height: 0;
					border-top: 6px solid transparent;
					border-bottom: 6px solid transparent;

					border-left: 10px solid $white;
				}
			}

			img {
				float: right;
			}
		}
	}
</style>
