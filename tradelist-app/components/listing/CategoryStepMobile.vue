<template>
    <div class="fieldset_container">
	    <div class="select_category" transition="fade" mode="out-in">
		    <h2>Category</h2>
		    <multiselect
			    placeholder="Please select..."
			    v-model="listingMainCats"
			    :close-on-select="true"
			    :options="optionsMainCats"
			    :searchable="false"
		    />
	    </div>
        <div class="select_subcategory" :class="{'main_selected' : listingMainCats}">
            <h2>Sub-Category</h2>
            <multiselect
                    placeholder="Please select..."
                    tag-placeholder="Add this as a new sub-category"
                    v-model="listingTagsSelected"
                    :multiple="true"
                    :taggable="true"
                    @search-change="findTags"
                    @tag="addTag"
                    :close-on-select="false"
                    :clear-on-select="false"
                    :options="listingTags"
                    v-validate="'required:true'"
                    data-vv-name="listing_tags"
                    data-vv-as="Tag"
                    data-vv-scope="CategoryStep"
            />
        </div>
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
        created () {
        },
        data() {
            return {
                optionsMainCats: this.$store.getters['mainCategories/getCategories'],
                listingTags: [],
	            listingMainCats: []
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
            addTag (newTag) {
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
	
	.categories_list {
		li {
			label {
				img {
					max-width: 50px;
					max-height: 50px;
				}
			}
		}
	}
	
	.categories_list {
		flex-wrap: wrap;
		justify-content: flex-start;
		padding: 0;
		
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
	
	.is_mobile {
		
		.multiselect__placeholder {
			padding-top: 0 !important;
		}
	
		.multiselect {
			height: auto;
			min-height: 45px;
		}
		
		.fieldset_container {
			padding: 0;
			display: flex;
			align-items: center;
			
			@media only screen and (max-width: 576px) {
				flex-wrap: wrap;
			}
		}
		
		.select_category, .select_subcategory {
			padding: 0;
			flex: 1;
			
			@media only screen and (max-width: 576px) {
				width: 100%;
				flex: none;
			}
			
			h2 {
				font-size: 10px;
				line-height: 13px;
				text-transform: uppercase;
				letter-spacing: 2px;
				color: $gray_aluminium;
				margin-bottom: 9px;
			}
		}
		
		.select_category {
			margin-right: 15px;
		}
		
		.select_subcategory {
			border-top: none;
			
			@media only screen and (max-width: 576px) {
				margin-top: 20px;
			}
			
			h2, .multiselect {
				opacity: .6;
			}
		}
		
		.main_selected {
			h2, .multiselect {
				opacity: 1;
			}
		}
	}
	
</style>
