<template>
	<div class="saved__page dashboard">
		<Header v-if="$device.isDesktop"/>
		<HeaderMobile v-if="$device.isMobileOrTablet"/>
		<div class="container">
			<div class="dashboard_content">
				<DashboardAside v-if="$device.isDesktop"/>
				<fieldset>
					<div class="breadcrumb">
						<ul>
							<li>
								Saved searches
							</li>
						</ul>
					</div>
					<table class="table table_saved">
						<tr>
							<th>Name</th>
							<th>Location</th>
							<th>Price Range</th>
							<th>Category</th>
							<!--<th>Alerts</th>
							<th>Emails</th>-->
							<th>&nbsp;</th>
						</tr>
						<tr v-for="data in queries">
							<td>
								<div class="name">
									{{ data.q }}
									<div class="badge" v-if="data.new">
										{{ data.new.items }} new
									</div>
								</div>
							</td>
							<td>
								<strong>{{ data.locationText }}</strong>
							</td>
							<td>
								<div v-if="data.price_min && data.price_max">
									<strong>{{ data.price_min }}</strong> to <strong>{{ data.price_max }}</strong>
								</div>
							</td>
							<td>
								<strong :style="{color:findCategory(data.category).colorCode}">
									{{findCategory(data.category).name }}
								</strong>

							</td>
							<!--<td>
								<div class="btn__switch">
									<label :for="'switchAlerts-'">
										<input type="checkbox" :id="'switchAlerts-'"
										value="None"
										       name="check">
										<span>On</span>
										<span>Off</span>
										<span class="toggle"></span>
									</label>
								</div>
							</td>
							<td>
								<div class="btn__switch">
									<label :for="'switchEmails'">
										<input type="checkbox" :id="'switchEmails'"
										       value="None"
										       name="check">
										<span>On</span>
										<span>Off</span>
										<span class="toggle"></span>
									</label>
								</div>
							</td>-->
							<td>
								<div class="actions">

									<nuxt-link class="btn btn__icon"
											   title="View"
											   :to="{path: '/search', query: data}">
										<i class="icon-eye"></i>
									</nuxt-link>
									<button class="btn btn__icon"
											type="button"
											role="button"
											@click="removeQuery(data)">
										<i class="icon-close"></i>
									</button>
									<!--<button class="btn_dots btn_dots__horizontal&#45;&#45;mod" type="button" role="button">
										<span></span><span></span><span></span>
									</button>-->
								</div>
							</td>
						</tr>
					</table>

				</fieldset>
			</div>
		</div>
	</div>
</template>

<script>
	import Header from "~/components/Header"
	import HeaderMobile from "~/components/HeaderMobile"
	import DashboardAside from "~/components/DashboardAside"

	export default {
		middleware: 'auth',
		components: {
			Header,
			HeaderMobile,
			DashboardAside
		},
		head: {
			title: "Saved searches"
		},
		data() {
			return {
				queries: this.$store.getters['searchQueriesSaved/getQueries'],
			}
		},
		methods: {
			async removeQuery(query) {
				this.queries = await this.$store.dispatch('searchQueriesSaved/setSaved', {query: query, status: 0})
					.then((response) => {
						return this.$store.getters['searchQueriesSaved/getQueries'];
					});
			},
			findCategory(id) {
				console.log(id);
				console.log(this.$store.getters['mainCategories/getCategory'](id));
				return this.$store.getters['mainCategories/getCategory'](id) || {colorCode: "", name: ""};
			},
		}
	}
</script>

<style lang="scss" scoped>
	.create_search {
		margin: 26px 0 0 30px;
	}
</style>
