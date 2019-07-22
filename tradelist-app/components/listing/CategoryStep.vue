<template>
	<div class="fieldset_container">
		<ul class="categories_list">
			<li v-for="mainCat in optionsMainCats" :key="mainCat.id">
				<input
					type="radio"
					v-bind:id="'main_cat_'+mainCat.id"
					v-bind:value="mainCat.id"
					name="main_category"
					v-model="mainCatSelected"
					v-validate="'required:true|excluded:0'"
					data-vv-as="Main category"
					data-vv-scope="CategoryStep"
				>
				<label v-bind:for="'main_cat_'+mainCat.id" :style="{borderColor: mainCat.colorCode}">
					<span class="checkbox"></span>
					<img v-bind:src="require('~/assets/img/illustrations/' + mainCat.icon)" v-bind:alt="mainCat.name">
					<p :style="{color: mainCat.colorCode}">{{mainCat.name}}</p>
				</label>
			</li>
		</ul>
		<!--<div class="select_subcategory" v-show="mainCatSelected" transition="fade" mode="out-in" v-if="mainCatSelected">-->
			<!--<h2>Sub-category</h2>-->
			<!--<multiselect-->
				<!--placeholder="Please select..."-->
				<!--tag-placeholder="Add this as a new sub-category"-->
				<!--v-model="listingTagsSelected"-->
				<!--:multiple="true"-->
				<!--:taggable="true"-->
				<!--@search-change="findTags"-->
				<!--@tag="addTag"-->
				<!--:close-on-select="false"-->
				<!--:clear-on-select="false"-->
				<!--:options="listingTags"-->
				<!--v-validate="'required:true'"-->
				<!--data-vv-name="listing_tags"-->
				<!--data-vv-as="Tag"-->
				<!--data-vv-scope="CategoryStep"-->
			<!--/>-->
		<!--</div>-->
	</div>
</template>

<script>
	import Multiselect from "vue-multiselect"
	import Dropzone from "nuxt-dropzone"
	
	export default {
		transition: "",
		components: {
			Multiselect,
			Dropzone,
		},
		inject: ['$validator'],
		props: ['listingData'],
		created() {
		},
		data() {
			return {
				optionsMainCats: this.$store.getters['mainCategories/getCategories'],
				listingTags: []
			};
		},
		mounted() {
			this.listingTags = this.$store.getters['listingTags/getTags'];
		},
		computed: {
			mainCatSelected: {
				get: function () {
					return this.listingData.main_category_id;
				},
				set: function (val) {
					this.$emit('update', {main_category_id: val})
				}
			},
			listingTagsSelected: {
				get: function () {
					return this.listingData.listing_tags;
				},
				set: function (val) {
					this.$emit('update', {listing_tags: val})
				}
			},
		},
		methods: {
			findTags(query) {
				if (query.match(/^\s*$/)) {    // prevent white space requests
					return
				}
				
				this.isLoading = true;  // use it to say to multiselect to show loading spinner
				clearTimeout(this.debounceTimer);   // clearing debounceTimer
				this.debounceTimer = setTimeout(() => {   // set timer 500 ms, if not reset with line above (by new search-change event), the function will be executes the ajax request to server
					this.$store.dispatch('listingTags/searchTags', query).then((response) => {
						
						this.listingTags = this.$store.getters['listingTags/getTags'];
						this.isLoading = false  // say to multiselect to hide loading spinner
					});
				}, 500)
			},
			addTag(newTag) {
				this.$store.dispatch('listingTags/addTag', newTag).then((response) => {
				});
				
				this.listingTagsSelected.push(newTag)
			}
		}
	};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style src="vue2-dropzone/dist/vue2Dropzone.min.css"></style>

<style lang="scss" scoped>
	@import "~assets/scss/abstracts/_abstracts.scss";
	
	.step_content {
		.categories_list {
			flex-wrap: wrap;
			justify-content: flex-start;
			padding: 0;
			
			li {
				label {
					img {
						max-width: 50px;
						max-height: 50px;
					}
				}
			}
			
			input[type="radio"] {
				position: absolute;
				z-index: -1;
				opacity: 0;
				visibility: hidden;
				
				&:checked {
					& + label {
						box-shadow: -1px 2px 30px rgba(0, 0, 0, 0.1),
						-1px 2px 30px rgba(0, 0, 0, 0.1);
						
						.checkbox {
							opacity: 1;
						}
					}
				}
			}
			
			label {
				display: flex;
				padding: 15px 17px;
				flex-wrap: wrap;
				align-items: center;
				justify-content: center;
				border: 2px solid;
				width: 100%;
				height: 100%;
				cursor: pointer;
				position: relative;
				transition: 0.2s $bezier_ease_in_out;
				
				.checkbox {
					width: 26px;
					height: 26px;
					border-radius: 5px;
					position: absolute;
					top: 12px;
					left: 13px;
					box-shadow: 0 1px 5px rgba(0, 0, 0, 0.5),
					0 1px 5px rgba(0, 0, 0, 0.5);
					opacity: 0;
					transition: 0.2s $bezier_ease_in_out;
					
					&:after {
						content: "";
						display: block;
						position: absolute;
						top: 6px;
						left: 10px;
						width: 6px;
						height: 12px;
						border: solid $gray_aluminium;
						border-width: 0 2px 2px 0;
						transform: rotate(45deg);
					}
				}
			}
			
			li {
				margin: 0 19px 19px 0;
				width: 187px;
				height: 187px;
				
				@media only screen and (max-width: 1530px) {
					width: 23%;
					height: 160px;
					margin: 0 14px 14px 0;
				}
				
				@media only screen and (max-width: 1199px) {
					width: 30%;
					height: 165px;
				}
				
				&:nth-child(5n) {
					margin-right: 0;
					
					@media only screen and (max-width: 1530px) {
						margin-right: 14px;
					}
				}
			}
			
			p {
				@include sintony(700);
				font-size: 14px;
				font-weight: 700;
				line-height: 19px;
				text-transform: uppercase;
			}
			
			.color_blue,
			.color_green,
			.color_red,
			.color_yellow {
				border-color: inherit;
			}
		}
	}
	
	.select_subcategory {
		padding: 40px 31px 12px;
		border-top: 1px solid rgba($gray_aluminium, 0.17);
		
		.multiselect__tags-wrap {
			padding-top: 12px;
		}
		
		.multiselect__tags-wrap {
			display: block;
		}
		
		h2 {
			margin-bottom: 15px;
		}
	}
	

</style>
