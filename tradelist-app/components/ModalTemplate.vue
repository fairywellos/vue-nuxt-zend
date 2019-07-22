<template>
	<transition name="modal">
		<div class="modal" @keydown.esc="$emit('close')">
			<div class="modal_container_wrapper" @click="$emit('close')">
				<div class="modal_container" @click.stop>
					<div class="modal_header">
						<slot name="header"></slot>
					</div>
					<div class="modal_body">
						<button
							type="button"
							@click="$emit('close')"
							role="button"
							class="close_modal_mobile"
							aria-label="Close modal"
						>
							<i class="icon-close"></i>
						</button>
						<slot name="body"></slot>
					</div>
					<div class="modal_footer">
						<slot name="footer"></slot>
					</div>
				</div>
			</div>
		</div>
	</transition>
</template>

<style lang="scss">
@import "~assets/scss/abstracts/_abstracts.scss";

.modal-enter-active,
.modal-leave-active {
	transition: opacity 0.3s $bezier_ease_in_out;
}

.modal-enter,
.modal-leave-active {
	opacity: 0;
}

.modal-enter-active {
	.modal_container {
		animation: zoomIn 0.2s both;
	}
}

.modal-leave-active {
	.modal_container {
		animation: zoomOut 0.2s both;
	}
}

.modal {
	position: fixed;
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	z-index: 1000;
	overflow: hidden;
	-webkit-overflow-scrolling: touch;
	background: rgba(57, 60, 64, 0.48);
	transition: 0.2s ease;
	
	.main_logo {
		justify-content: center;
		
		i {
			@media only screen and (max-width: 968px) {
				font-size: 23px;
				line-height: 31px;
			}
		}
		
		img {
			
			@media only screen and (max-width: 968px) {
				max-width: 33px;
				max-height: 32px;
				margin-right: 7px;
			}
		}
	}
	
	&_body {
		position: relative;
	}
	
	.close_modal_mobile {
		display: none;
		position: absolute;
		top: 15px;
		right: 15px;
		color: #393C40;
		font-size: 15px;
		
		@media only screen and (max-width: 968px) {
			display: block;
		}
	}
}

.custom_modal {
	.modal_logo {
		margin: 0 auto;
		display: block;
		max-width: 100%;
	}
}

.modal_sign_up {
	.terms {
		margin-bottom: 25px;
		
		label a {
			font-size: 12px;
			line-height: 16px;
			color: $gray_aluminium;
			
			&:hover {
				text-decoration: underline;
			}
		}
	}
	
	.modal_body {
		.btn {
			margin: 10px auto 25px;
		}
	}
	
}

.success_modal{
	.modal_body {
		width: 412px;
		border-radius: 4px;
		padding: 43px 30px 45px;
		text-align: center;
		position: relative;
		
		h3, p {
			color: $gray_tuna;
		}
		
		 h3 {
			 @include sintony(700);
			 font-size: 18px;
			 line-height: 24px;
			 margin-bottom: 12px;
		 }
		
		p {
			font-size: 19px;
			line-height: 25px;
			margin-bottom: 30px;
		}
	}
	
	.success_icon {
		width: 103px;
		height: 70px;
		display: flex;
		justify-content: center;
		align-items: center;
		margin: 0 auto 20px;
		
		i {
			font-size: 61px;
		}
	}
	
	&.modal_offer_success {
		.close_modal_mobile {
			display: block;
			
			i {
				font-size: 13px;
			}
		}
		
		p {
			margin-top: 0;
		}
	}
	
	&.modal_listing_success {
		
		.modal_body {
			width: 362px;
			padding: 78px 30px 51px;
			
			h3, p {
				font-size: 16px;
				line-height: 21px;
			}
			
			h3 {
				color: $blue_scooter;
			}
			
			p {
				color: $gray_aluminium;
				margin-bottom: 51px;
			}
		}
		
		.product_image {
			width: 96px;
			height: 96px;
			background-repeat: no-repeat;
			background-position: center;
			background-size: cover;
			border-radius: 4px;
			margin: 0 auto 23px;
		}
		
		.success_icon {
			background: $white;
			border-radius: 50%;
			width: 103px;
			height: 103px;
			position: absolute;
			top: -51.5px;
			left: 50%;
			transform: translateX(-50%);
			display: flex;
			justify-content: center;
			align-items: center;
			
			i {
				font-size: 50px;
			}
		}
	}
}

.user_modal {
	text-align: center;
	
	input[type=text]:not(.multiselect__input),
	input[type=email],
	input[type=tel],
	input[type=password],
	input[type=search],
	input[type=number] {
		height: 50px;
	}
	
	.is_danger {
		margin: 0 0 15px;
	}
	
	.form_invalid {
		input[type=email],
		input[type=password] {
			border-color: $valencia;
		}
	}

	.modal_container {
		width: 362px;
		
		@media only screen and (max-width: 576px) {
			width: 100%;
		}

		.email_code {
			.form_group {
				display: flex;
				justify-content: space-between;
				align-items: center;

				input[type="number"]::-webkit-outer-spin-button,
				input[type="number"]::-webkit-inner-spin-button {
					-webkit-appearance: none;
					margin: 0;
				}

				input[type="number"] {
					-moz-appearance: textfield;
				}

				input[type="text"],
				input[type="number"] {
					width: 41px;
					height: 50px;
					text-align: center;
					padding: 0 5px;
				}
			}
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

.modal_message {
	.user_image {
		width: 49px;
		height: 49px;
		border-radius: 50%;
		overflow: hidden;
		background-size: cover;
		background-position: center;
		background-repeat: no-repeat;
		margin-right: 16px;
	}

	.message_box__to {
		p {
			font-size: 12px;
			line-height: normal;
			color: $gray_aluminium;
			width: 333px;
			margin: 0;
			& + p {
				padding-top: 10px;
			}

			strong {
				text-transform: uppercase;
				width: 60px;
				display: inline-block;
				padding-right: 3px;
			}
		}
	}

	.message_box_modal {
		background: $white;
		width: 602px;
		border-radius: 3px;
		margin-top: 1px;

		header {
			display: flex;
			align-items: center;
			padding: 20px 24px;
		}

		.btn {
			align-self: flex-end;
		}

		::-webkit-input-placeholder {
			color: $black;
		}
		::-moz-placeholder {
			color: $black;
		}
		:-ms-input-placeholder {
			color: $black;
		}
		:-moz-placeholder {
			color: $black;
		}

		textarea {
			width: 100%;
			height: 265px;
			background: $white;
			border: 1px solid $gray_aluminium2;
			border-radius: 0;
			margin: 0;
			display: block;
			padding: 34px 26px 20px;
		}
	}
}

.close_modal {
	position: absolute;
	top: 15px;
	right: 15px;
	color: $gray_tuna;
	font-size: 15px;
}

.rating_modal {
	.modal_container {
		width: 411px;
		
		@media only screen and (max-width: 576px) {
			width: 100%;
		}
	}

	form {
		@include clearfix;
	}

	.rating {
		position: relative;
		display: inline-block;
		height: 50px;
		line-height: 50px;
		font-size: 50px;
		margin-bottom: 20px;
		width: 225px;

		label {
			position: absolute;
			top: 0;
			left: 0;
			height: 100%;
			cursor: pointer;
			margin-bottom: 0 !important;
			line-height: 50px !important;

			&:last-child {
				position: static;

				.star {
					color: #9b9b9b;

					&:last-child {
						margin-right: 0;
					}
				}
			}

			&:nth-child(1) {
				z-index: 5;
			}

			&:nth-child(2) {
				z-index: 4;
			}

			&:nth-child(3) {
				z-index: 3;
			}

			&:nth-child(4) {
				z-index: 2;
			}

			&:nth-child(5) {
				z-index: 1;
			}

			input {
				position: absolute;
				top: 0;
				left: 0;
				opacity: 0;
			}

			.star {
				float: left;
				color: transparent;
				font-size: 35px;
				margin-right: 5px;

				&:before {
					content: "\e913";
					font-family: "tradelist-font-icon" !important;
					speak: none;
					font-style: normal;
					font-weight: normal;
					font-variant: normal;
					text-transform: none;
					line-height: 1;

					/* Better Font Rendering =========== */
					-webkit-font-smoothing: antialiased;
					-moz-osx-font-smoothing: grayscale;
				}
			}
		}

		&:not(:hover) label input:checked ~ .star,
		&:hover label:hover input ~ .star {
			color: $saffron;
			margin-right: 6.8px;
			position: relative;
			top: -1px;

			&:before {
				content: "\e908";
			}
		}

		label input:focus:not(:checked) ~ .star:last-child {
			color: #9b9b9b;
		}
	}

	.modal_body {
		padding: 0 24px 25px;
		text-align: center;
		position: relative;

		p {
			@include sintony(400);
			color: $gray_tuna !important;
			font-size: 18px;
			line-height: normal;
			max-width: 269px;
			margin: 0 auto 10px !important;
		}

		textarea {
			height: 175px;
			margin-bottom: 17px;
			text-align: center;
		}

		label {
			@include open_sans(500);
			line-height: 14px;
			letter-spacing: 2px;
			font-size: 10px;
			text-transform: uppercase;
			margin-bottom: 11px;
			color: $gray_aluminium;
			display: block;
		}
	}

	.user_image {
		width: 102px;
		height: 102px;
		border-radius: 50%;
		overflow: hidden;
		background-repeat: no-repeat;
		background-position: center;
		background-size: cover;
		margin: 0 auto -30px;
		position: relative;
		top: -50px;
	}
}

.modal_email_code {
	.form_invalid {
		input[type=text] {
			border-color: $valencia;
		}
	}
	
	.is_danger {
		font-size: 12px;
		margin: 0 0 10px;
	}
	
	.is_sent {
		font-size: 12px;
		line-height: 18px;
		color: $gray_tuna;
		margin: 0 0 15px;
	}
}

.modal_listings {
	.modal_container {
		width: 100%;
		max-width: 1012px;
		border-radius: 4px;
	}

	.header_inner {
		position: relative;
		padding: 38px 31px 30px;
		border-bottom: 1px solid $gray_aluminium;

		h3 {
			font-size: 18px;
			line-height: 24px;
			color: $gray_aluminium;
		}

		button {
			position: absolute;
			top: 39px;
			right: 41px;
			font-size: 16px;
			color: $gray_tuna;
		}
	}
	
	.modal_footer {
		.footer_inner {
			@include clearfix;
			padding: 21px 31px 28px;
			border-top: 1px solid $gray_aluminium;
			
			.btn__primary {
				width: 100%;
				display: inline-block;
				max-width: 147px;
				margin: 0;
			}
		}
	}

	.modal_body {
		padding: 19px 35px 47px;

		h4 {
			margin-bottom: 16px;
		}
	}
}

.modal_container_wrapper {
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	height: 100%;
	cursor: pointer;
}

.modal_container {
	background: $white;
	border-radius: 4px;
	cursor: default;
}

.modal_body {
	input[type="text"],
	input[type="email"],
	input[type="email"],
	input[type="tel"],
	input[type="password"],
	input[type="number"] {
		margin-bottom: 10px;

		&.validation_error {
			margin-bottom: 3px;
			border-color: $valencia;
		}
	}
}
</style>
