<template>
	<header class="main_header" role="banner" id="mainHeader">
		<div class="container">
			<div class="main_header__main_inner">
				<nuxt-link to="/" class="main_header__logo main_logo" title="Homepage">
					<img src="~/assets/img/logo-svg.svg" alt="main logo"><i>Tradelist</i>
				</nuxt-link>
			</div>
		</div>
	</header>
</template>

<script>
	import SearchBar from "~/components/SearchBar.vue";
	import headerJs from "~/assets/js/header.js";
	import {
		mapGetters
	} from "vuex";
	
	export default {
		components: {
			SearchBar
		},
		data() {
			return {
				activeNotification: false,
				max: 1,
				limitText: "",
				showModalSignUp: false,
				showModalLogin: false,
				showModalEmailCode: false,
				showNotifications: false,
				first_name: "",
				display_name: "",
				name: "",
				password: "",
				email: "",
				digit1: "",
				digit2: "",
				digit3: "",
				digit4: "",
				digit5: "",
				digit6: "",
				error: "null",
				errorEmailAuth: false,
				errorEmailLog: false,
				errorActivateCode: false,
				resendActivationEmail: false,
				seeAllNotifications: false,
			};
		},
		mounted() {
			this.$nuxt.$on('toSignUp', (bool) => {
				this.showModalSignUp = bool;
			})
		},
		computed: {
			...mapGetters("auth", ["isAuthenticated", "loggedInUser"]),
			...mapGetters("messages", ["getConversations"]),
			...mapGetters("notifications", ["getNotifications"])
		},
		extends: headerJs,
	};
</script>

<style lang="scss">
	@import "~assets/scss/abstracts/_abstracts.scss";
	
	.main_header {
		background: $gray_albaster;
		border-bottom: 1px solid $gray_aluminium2;
		position: fixed;
		top: 0;
		right: 0;
		left: 0;
		z-index: 999;
	}
	
	.main_header__logo {
		padding: 15px 0;
	}
	
	.main_header__main_inner {
		height: 81px;
	}
</style>
