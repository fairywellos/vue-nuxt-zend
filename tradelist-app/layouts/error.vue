<template>
	<div>
		<Header/>
		<div class="container">
			<h1 v-if="error.statusCode === 404">Page not found</h1>
			<h1 v-if="error.statusCode === 403">Access denied</h1>
			<h1 v-else>An error occurred</h1>
			<nuxt-link to="/">Homepage</nuxt-link>
		</div>
	</div>
</template>

<script>
	import Header from "~/components/Header"
	
	export default {
		components: {Header},
		props: ['error'],
	}
</script>
