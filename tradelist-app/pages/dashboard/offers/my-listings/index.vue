<template>
	<div class="my_listings__page dashboard">
		<Header v-if="$device.isDesktop"/>
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<div class="container">
			<div class="dashboard_content">
				<DashboardAside/>
				<div class="fieldset_wrapper">
					<fieldset>
						<div class="breadcrumb">
							<ul>
								<li>My listings</li>
							</ul>
						</div>
						<table class="table table_offers" v-if="listings.length !== 0">
							<thead>
								<tr>
									<th v-for="(key, index) in tableHeads" :key="index">{{ key }}</th>
									<th>&nbsp;</th>
									<th>&nbsp;</th>
									<!--<th>&nbsp;</th>-->
								</tr>
							</thead>
							<tbody>
								<tr v-for="(data, index) in listings" :key="index">
									<td>
										<div
											class="product_image"
											v-if="data.mainPhotoUrl"
											:style="{ backgroundImage: 'url('+ data.mainPhotoUrl + ')' }"
										></div>
										<div class="product_image placeholder_image" v-else></div>
									</td>
									<td>
										<nuxt-link :to="{path: '/listing-details/' + data.id}" class="desc desc__lg">
											<p>
												{{ data.title }}
												<small>{{ data.description }}</small>
											</p>
										</nuxt-link>
									</td>
									<td>
										<div
											:style="{color:getCategory(data.category_id).colorCode}"
											class="category"
										>{{ getCategory(data.category_id).name }}</div>
									</td>
									<td>
										<div class="value">${{ data.price }}</div>
									</td>
									<td>
										<nuxt-link
											:to="{name: 'dashboard-offers-my-listings-id', params: {id: data.id}}"
											class="offers"
										>
											<p>
												{{data.tradeOffers}}
												<span class="badge" v-if="false">new</span>
											</p>offers
										</nuxt-link>
									</td>
									<td>
										<div class="actions">
											<button class="btn btn__icon" @click="editListing(data.id)" type="button" role="button">
												<i class="icon-edit"></i>
											</button>
											<button
												class="btn btn__icon btn__close"
												type="button"
												role="button"
												@click="deleteListing(data.id)"
											>
												<i class="icon-close"></i>
											</button>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
						<table class="table table_no-rows" v-else>
							<thead>
								<tr>
									<th v-for="(key, index) in tableHeads" :key="index">{{ key }}</th>
									<th>&nbsp;</th>
									<th>&nbsp;</th>
									<th>&nbsp;</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="7">
										<p>There are no listings to display.</p>
										<nuxt-link to="/create-listing" class="btn btn__primary">Create a new listing</nuxt-link>
									</td>
								</tr>
							</tbody>
						</table>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import Header from "~/components/Header";
	import HeaderMobile from "~/components/HeaderMobile";
	import DashboardAside from "~/components/DashboardAside";
	import { mapGetters } from "vuex";

	export default {
		middleware: "auth",
		components: {
			Header,
			HeaderMobile,
			DashboardAside
		},
		head: {
			title: "My listings"
		},
		data() {
			return {
				tableHeads: ["image", "description", "category", "value"]
			};
		},
		async fetch({ store, params }) {
			await store.dispatch("auth/updateUserListingsAsync");
		},
		computed: {
			...mapGetters("auth", ["isAuthenticated", "loggedInUser", "listings"])
		},
		methods: {
			getCategory: function(id) {
				return this.$store.getters["mainCategories/getCategories"].find(
					function(element) {
						return element.id === id;
					}
				);
			},
			editListing: function(id) {
				this.$router.push({ name: "edit-listing-id", params: { id: id } });
			},
			deleteListing: function(id) {
				try {
					this.$axios
						.post("listing/delete?XDEBUG_SESSION_START=1", {
							id: id
						})
						.then(response => {
							this.$store.commit("auth/removeUserListing", id);
						})
						.catch(error => {
							console.log(error.response);
						});
				} catch (e) {
					console.log(e);
				}
			}
		}
	};
</script>
