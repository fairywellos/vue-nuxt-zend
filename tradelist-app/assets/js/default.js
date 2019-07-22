const defaultJs = {
	head: {
		script: [{
				src: 'https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.8.8/min/tiny-slider.js',
				body: true
			},
			{
				src: 'https://cdnjs.cloudflare.com/ajax/libs/tingle/0.13.2/tingle.min.js',
				body: true
			}
		]
	},
	methods: {
		modalInit() {
			var modalSignUp = new tingle.modal({
				stickyFooter: false,
				closeMethods: ['overlay', 'escape'],
				cssClass: ['custom_modal', 'custom_modal_sign_up', 'user_modal']
			});

			var btnSignUp = document.querySelector('.modal_sign_up_btn');

			if (btnSignUp) {
				btnSignUp.addEventListener('click', function() {
					modalSignUp.open();
				});

				modalSignUp.setContent(document.querySelector('.modal_sign_up').innerHTML);
			}

			var modalLogin = new tingle.modal({
				stickyFooter: false,
				closeMethods: ['overlay', 'escape'],
				cssClass: ['custom_modal', 'custom_modal_login', 'user_modal']
			});

			var btnLogin = document.querySelector('.modal_login_btn');

			if (btnLogin) {
				btnLogin.addEventListener('click', function() {
					modalLogin.open();
				});

				modalLogin.setContent(document.querySelector('.modal_login').innerHTML);
			}
		},
		headerChange() {
			window.addEventListener("scroll", function() {
				var elementTarget = document.getElementById("formSearch");
				var mainHeader = document.getElementById('mainHeader');

				if (elementTarget) {
					if (window.scrollY > (elementTarget.offsetTop + 30 + elementTarget.offsetHeight)) {
						mainHeader.classList.add('scrolled');
					} else {
						mainHeader.classList.remove('scrolled');
					}
				}
			});
		},
		preventIosInputZoom() {
			MBP.preventZoom = function() {
				if (MBP.viewportmeta && navigator.platform.match(/iPad|iPhone|iPod/i)) {
					let formFields = document.querySelectorAll('input, textarea');
					let contentString = 'width=device-width,initial-scale=1,maximum-scale=';
					let i = 0;
					let fieldLength = formFields.length;

					let setViewportOnFocus = function() {
						MBP.viewportmeta.content = contentString + '1';
					};

					let setViewportOnBlur = function() {
						MBP.viewportmeta.content = contentString + '10';
					};

					for (; i < fieldLength; i++) {
						formFields[i].onfocus = setViewportOnFocus;
						formFields[i].onblur = setViewportOnBlur;
					}
				}
			};
		},
		bodyPadding() {
			let headerHeight = $('.main_header').height();

			if (headerHeight) {
				$('body').css({
					'padding-top': headerHeight + 'px'
				});
			}
		}
	}
};

export default defaultJs;