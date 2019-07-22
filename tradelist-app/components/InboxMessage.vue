<template>
	<nuxt-link
		class="message_preview"
		:class="[status != 1 ? 'unread' : '', selectedConv == convID ? 'active' : '']"
		:to="{name: 'dashboard-messages-id', params: {id: convID}}"
	>
		<span v-if="conversationPhoto" class="user_image"
			  :style="{ backgroundImage: 'url('+ conversationPhoto + ')' }"></span>
		<span v-else class="user_image"></span>
		<span class="info">
			<h4>{{ firstName }} {{ name }}</h4>
			<p>{{ text }}</p>
		</span>
		<span class="date_time"><!--{{ dateAdded }}--></span>
	</nuxt-link>
</template>

<script>
	export default {
		props: [
			"convID", "status", "selectedConv", "condition", "conversationPhoto", "firstName", "name", "text", "dateAdded"
		],
	}
</script>

<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";
</style>
