<template>
	<div class="partner_relations informational_page">
		<Header v-if="$device.isDesktop"/>
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<div class="hero_section hero_section__text_center"
		     :style="{ backgroundImage: 'url('+ bgHipster + ')' }">
			<div class="hero_inner">
				<h1>
					Partner Relations
				</h1>
				<p>
					Tradelist was created as a network that empowers people. A network that supports and improves human relations, without relying on money. It’s a trading and bartering platform that enables people to create value, network, solve problems and to ultimately be successful.
				</p>
			</div>
		</div>
		
		<div class="container">
			<div class="centered_preview">
				<i class="icon-red-line"></i>
				<h2>
					Thanks for your interest in partnering with us! Fill out the form below to connect with us and learn about our partnerships.
				</h2>
			</div>
			
			<div class="col_three">
				<div class="row">
					<div class="col-sm-6 col-md-4">
						<i class="icon-e-commerce"></i>
						<h2>
							eCommerce Partners
						</h2>
						<p>
							Put Tradelist to work for your website. Generate leads via Tradelist and complete the transaction on your site.
						</p>
					</div>
					<div class="col-sm-6 col-md-4">
						<i class="icon-shipping-partners"></i>
						<h2>
							Shipping Partners
						</h2>
						<p>
							Many of our users have items to trade, but no way to ship the them. Sign up as a shipping partner and we’ll send you leads on Trades that need someone to pickup and deliver goods.
						</p>
					</div>
					<div class="col-sm-6 col-md-4">
						<i class="icon-photographs"></i>
						<h2>
							Local Photographers & Artists
						</h2>
						<p>
							Gain exposure and followers while helping Tradelist add local photos to our webpages. Contact us if you’d like your pictures or art displayed on local Tradelist pages.
						</p>
					</div>
				</div>
			</div>

			<form class="informational_form"  @submit.prevent="sendMail()">
				<div class="form_group__row">
					<div class="form_group">
						<label for="firstName">First Name</label>
						<input type="text" id="firstName" placeholder="What is your first name?" v-model="firstName"
							   :class="{ 'validation_error': formErrors.first_name }">
						<div v-if="formErrors.first_name" class="help is_danger">{{ formErrors.first_name }}</div>
					</div>

					<div class="form_group">
						<label for="lastName">Last Name</label>
						<input type="text" id="lastName" placeholder="What is your last name?" v-model="lastName"
							   :class="{ 'validation_error': formErrors.name }">
						<div v-if="formErrors.first_name" class="help is_danger">{{ formErrors.first_name }}</div>

					</div>
				</div>
				<div class="form_group__row">
					<div class="form_group">
						<label for="email">Your Email</label>
						<input type="email" id="email" placeholder="What is your email adress?" v-model="email"
							   :class="{ 'validation_error': formErrors.email }">
						<div v-if="formErrors.email" class="help is_danger">{{ formErrors.email }}</div>

					</div>
					<div class="form_group">
						<label for="phoneNumber">Your Phone</label>
						<input type="tel" id="phoneNumber" placeholder="(123) 456-7890" v-model="phoneNumber"
							   :class="{ 'validation_error': formErrors.phone_number }">
						<div v-if="formErrors.phone_number" class="help is_danger">{{ formErrors.phone_number }}</div>

					</div>
				</div>
				<div class="form_group__row row_full">
					<div class="form_group">
						<label for="companyName">Company (If applicable)</label>
						<input type="text" id="companyName" placeholder="What company are you with?"
							   v-model="companyName" :class="{ 'validation_error': formErrors.company }">
						<div v-if="formErrors.company" class="help is_danger">{{ formErrors.company }}</div>

					</div>
				</div>
				<div class="form_group__row row_full">
					<div class="form_group">
						<label for="message">YOUR MESSAGE</label>
						<textarea id="message" placeholder="Type your message here…" v-model="message"
								  :class="{ 'validation_error': formErrors.message }"></textarea>
						<div v-if="formErrors.message" class="help is_danger">{{ formErrors.message }}</div>

					</div>
				</div>
				<button type="submit" class="btn btn__primary">Send Message</button>
			</form>
		</div>
	</div>
</template>

<script>
	import Header from "~/components/Header.vue";
	import HeaderMobile from "~/components/HeaderMobile.vue";
	import bgHipster from "~/assets/img/informational-pages/bg-hipster.jpg";
	import contactJs from "~/assets/js/contact.js";


	export default {
		components: {
			Header, HeaderMobile, bgHipster
		},
		head() {
			return {
				title: "Partner Relations",
				meta: [
					{
						hid: "description",
						name: "description",
						content: "Partner relations description"
					}
				]
			};
		},
		extends: contactJs,
		data() {
			return {
				bgHipster,
				firstName: "",
				lastName: "",
				email: "",
				phoneNumber: "",
				companyName: "",
				message: "",
				form_type: "partner_relations",
				formErrors: {
					first_name: false,
					name: false,
					email: false,
					phone_number: false,
					company: false,
					message: false,
				}
			}
		},
		mounted() {
			if(this.$auth.loggedIn){
				this.firstName = this.$auth.user.firstName;
				this.lastName = this.$auth.user.name;
				this.email = this.$auth.user.email;
			}
		}

	};
</script>

<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";
	@import "~assets/scss/components/_informational-pages.scss";
	
	.hero_section__text_center {
		min-height: 746px;
		display: flex;
		justify-content: center;
		align-items: center;
		text-align: center;
		margin-bottom: 57px;
		
		@media only screen and (max-width: 576px) {
			min-height: auto;
			padding: 35px 15px;
		}
		
		h1, p {
			width: 100%;
			max-width: 600px;
			margin: 0 auto;
			color: $white;
			letter-spacing: 0.75px;
		}
		
		h1 {
			margin-bottom: 58px;
		}
		
		p {
			font-size: 24px;
			line-height: 52px;
		}
	}
	
	.col_three {
		text-align: center;
		
		i {
			width: 80px;
			margin: 0 auto 27px;
		}
		
		h2, p {
			text-align: center;
		}
	}
	
	.hero_inner {
		align-items: center;
		position: relative;
		z-index: 5;
	}
	
</style>
