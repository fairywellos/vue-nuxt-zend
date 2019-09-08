<template>
	<div class="splash_page">
		<HeaderSplash v-if="$device.isDesktop"/>
		<HeaderMobile v-if="$device.isMobileOrTablet"/>

		<div class="container">
			<form method="post" @submit.prevent="betaLogin" :class="{'form_invalid' : errorLogin}">
				<div class="main_logo">
					<img src="~/assets/img/logo-svg.svg" alt="main logo"><i>Tradelist</i>
					<span>beta</span>
				</div>
				<div class="flt">
					<p>Find, List, Trade</p>
				</div>
				<div class="form_group">
					<input type="text" placeholder="Username" name="username" v-model="username"
						   v-validate="'required:true|min:5'">
					<i class="icon-user"></i>
					<span v-if="errors" class="is_danger error_message">{{ errors.first('username') }}</span>
				</div>
				<div class="form_group">
					<input type="password" placeholder="Password" name="password" v-model="password"
						   v-validate="'required:true|min:5'">
					<i class="icon-lock"></i>
					<span v-if="errors" class="is_danger error_message">{{ errors.first('password') }}</span>
				</div>
				<div v-if="errorLogin" class="help is_danger">{{ errorLogin }}</div>
				<button type="submit" class="btn btn__primary" role="button">Sign in</button>
			</form>
		</div>
	</div>
</template>

<script>
	import HeaderSplash from "~/components/HeaderSplash";
	import HeaderMobile from "~/components/HeaderMobile";
	import Cookies from 'js-cookie'

	export default {
		components: {
			HeaderSplash, HeaderMobile
		},
		created() {
			this.$route.push('/');
		},
		inject: ["$validator"],
		head() {
			return {
				title: "Splash Page",
				meta: [
					{
						hid: "description",
						name: "description",
						content: "Splash Page description"
					}
				]
			};
		},
		data() {
			return {
				password: "",
				username: "",
				errorLogin: false,
				error: {
					username: false,
					password: false
				}
			}
		},
		methods: {
			betaLogin() {
				this.$validator.validateAll().then(result => {
					if (result) {
						this.$axios.post("beta-login", {
							username: this.username,
							password: this.password
						}).then(()=>{
							this.$store.commit("auth/setLoggedIn");
							this.$store.dispatch("auth/setBetaLoginCookie").then(()=>{
								this.$gtag('event', 'conversion', {'send_to': 'AW-729655576/BnsPCNSv3qYBEJjS9tsC'});
		    					this.$fb('track', 'login');
								this.$router.push("/");

							});
						}).catch((e) => {
							if (e.response.status === 403 || e.response.status === 400) {
								this.errorLogin = e.response.data.result.message;

							}
						});


					}
				});

			}
		},
		mounted() {
			let isBetaLoggedIn = Cookies.get("TradelistBetaLoggedIn");
			console.log("isBeta:" + isBetaLoggedIn);
			console.log(this.$cookies.get("TradelistBetaLoggedIn"));

		}
	}
</script>

<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";

	.splash_page {
		text-align: center;

		input[type=text]:not(.multiselect__input),
		input[type=email],
		input[type=tel],
		input[type=password],
		input[type=search],
		input[type=number] {
			height: 50px;
			text-align: center;
		}

		.is_danger {
			margin: 0 0 15px;
		}

		.form_invalid {
			input[type=text],
			input[type=password] {
				border-color: $valencia;
			}
		}

		form {
			width: 298px;
			margin: 170px auto;
			text-align: center;

			@media only screen and (max-width: 576px) {
				width: 100%;
			}
		}

		.main_logo {
			display: inline-flex;
			flex-wrap: wrap;
			position: relative;

			span {
				margin-left: 10px;
				font-size: 15px;
				line-height: 20px;
				color: #AAAEB3;
				margin-bottom: 10px;
				align-self: flex-end;
			}
		}

		.flt {
			margin-bottom: 30px;
			p {
				font-size: 15px;
				line-height: 20px;
				color: #AAAEB3;
				margin-bottom: 10px;
			}
		}

		.form_group {
			position: relative;
			margin-bottom: 10px;

			.icon-user {
				font-size: 14px;
			}

			.icon-envelope {
				font-size: 11px;
			}

			.icon-lock {
				font-size: 15px;
			}

			i {
				position: absolute;
				left: 10px;
				color: #707070;
				font-size: 12px;
				top: 50%;
				transform: translateY(-50%);
			}
		}

		.modal_body {
			padding: 47px 32px;
			text-align: center;

			@media only screen and (max-width: 1260px) {
				padding: 40px;
			}

			input {
				text-align: center;
			}

			input[type="text"]:not(.multiselect__input),
			input[type="email"],
			input[type="email"],
			input[type="tel"],
			input[type="password"],
			input[type="search"],
			input[type="number"] select {
				margin-bottom: 0;

				&:focus {
					& + i:before {
						color: $black;
					}
				}
			}
		}

		p {
			font-size: 17px;
			line-height: 22px;
			margin-bottom: 30px;
			color: $gray_aluminium;

			@media only screen and (max-width: 968px) {
				font-size: 14px;
				line-height: 20px;
				margin-bottom: 20px;
			}
		}

		.btn {
			margin: 5px auto 30px;
			display: block;
			box-shadow: 0 10px 20px 0 rgba($black, 0.2);

			@media only screen and (max-width: 968px) {
				margin: 5px auto 20px;
				padding: 13.5px 20px;
			}

			&:hover {
				box-shadow: 0 13px 23px 0 rgba($black, 0.3);
			}
		}

		.extra_option {
			p {
				font-size: 12px;
				line-height: 16px;
				color: $gray_tuna;
				margin: 0;

				& + p {
					margin-top: 10px;
				}
			}

			button {
				font-weight: 700;
				padding: 0;
				color: #393C40;
			}
		}
	}
</style>
