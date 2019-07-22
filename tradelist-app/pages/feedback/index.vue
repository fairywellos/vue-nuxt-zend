<template>
	<div class="feedback informational_page">
		<Header v-if="$device.isDesktop"/>
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<div class="hero_section"
		     :style="{ backgroundImage: 'url('+ bgField + ')' }">
			<div class="container">
				<div class="hero_inner">
					<h1>Product Feedback</h1>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="centered_preview">
				<i class="icon-red-line"></i>
				<h2>
					Good, bad, or ugly, we’d like to hear <br> what you think of our website. <br>
					Please fill out the form below…
				</h2>
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
	import bgField from "~/assets/img/informational-pages/bg-field.jpg";
	import contactJs from "~/assets/js/contact.js";


	export default {
		components: {
			Header, HeaderMobile
		},
		head() {
			return {
				title: "Feedback",
				meta: [
					{
						hid: "description",
						name: "description",
						content: "Feedback description"
					}
				]
			};
		},
		extends: contactJs,

		data() {
			return {
				bgField,
				firstName: "",
				lastName: "",
				email: "",
				phoneNumber: "",
				companyName: "",
				message: "",
				form_type: "product_feedback",
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
	
	.hero_section {
		margin-bottom: 92px;
		
		@media only screen and (max-width: 1260px) {
			margin-bottom: 52px;
		}
	}
	
	.hero_inner{
		display: flex;
		align-items: center;
		justify-content: center;
		text-align: center;
		min-height: 512px;
		padding: 45px 15px;
		position: relative;
		z-index: 5;
		
		@media only screen and (max-width: 1260px) {
			min-height: 150px;
		}
		
		h1 {
			margin-bottom: 0;
		}
	}
	
	
</style>
