const savedJs = {
	head() {
		return {
			title: 'Saved',
			meta: [
				{hid: 'description', name: 'description', content: 'Saved page description'}
			]
		}
	},
	methods: {
		onTabclick(event) {
			event.preventDefault();

			var actives = document.querySelectorAll('.active');

			// deactivate existing active tab and panel
			for (var i = 0; i < actives.length; i++) {
				actives[i].className = actives[i].className.replace('active', '');
			}

			// activate new tab and panel
			event.target.parentElement.className += ' active';
			document.getElementById(event.target.href.split('#')[1]).className += ' active';
		}
	}
};

export default savedJs;
