<template>
<div :class="{'is_mobile' : $device.isMobileOrTablet}">
	<script data-ad-client="ca-pub-1709497292936218" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<nuxt />
					<Footer />
				</div>
</template>

<script>
import Footer from "~/components/Footer"
				import defaultJs from "~/assets/js/default.js";

				export default {
					components: {
						Footer
					},
					extends: defaultJs,
					watch: {
						$route() {
							this.bodyPadding();
							this.$store.dispatch('notifications/updateAllNotifications');
						}
					},
					mounted() {
						this.headerChange();
						this.modalInit();
						// this.bodyPadding();
					},

				};
</script>

<style lang="scss">
.is_mobile {
    .container {
        padding-right: 15px;
        padding-left: 15px;
    }
}

.container {
    @media only screen and (max-width: 1259px) {
        padding-right: 15px;
        padding-left: 15px;
    }
}
</style>
