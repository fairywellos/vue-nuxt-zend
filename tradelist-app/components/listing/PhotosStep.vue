<template>
	<div class="fieldset_container">
		<dropzone
			id="dropzoneCreateListing"
			ref="dropzone"
			:options="dropzoneOptions"
			:destroyDropzone="false"
			:useCustomSlot="true"
			@vdropzone-success="successUpload"
			@vdropzone-removed-file="removeFile"
		>
			<div class="dropzone-custom-content" v-on:click="dropzoneClick">
				<div class="drop_image">
					<i class="icon-upload"></i>
				</div>
				<h3>Drag & drop or upload images here</h3>
				<button type="button" class="uploadButton">Upload</button>
			</div>
		</dropzone>
	</div>
</template>

<script>
import Dropzone from "nuxt-dropzone";
import ListingConfig from "../../mixins/listingConfig.js";

export default {
	mixins: [ListingConfig],
	components: {
		Dropzone
	},
	inject: ["$validator"],
	props: ["listingData"],
	data() {
		return {
			dropzoneOptions: {
				url: process.env.API_URL + "/upload/listing",
				thumbnailWidth: 170,
				addRemoveLinks: true,
				dictDefaultMessage: "",
				acceptedFiles: this.acceptedFiles,
				duplicateCheck: true,
				useCustomSlot: true,
				error: function(file, message) {
					if (typeof message.result !== "undefined") {
						let errors = "";

						for (const [errorKey, errorValue] of Object.entries(
							message.result
						)) {
							errors += errorValue + "\n";
						}

						$(file.previewElement)
							.addClass("dz-error")
							.find(".dz-error-message")
							.text(errors);
					}
				}
			}
		};
	},
	mounted() {
		const dropzoneEl = this.$refs.dropzone;
        if (typeof this.listingData.photos !== "undefined" && this.listingData.photos.length > 0) {
			let dropzoneData = this.listingData.photos.map(function(fileObj){
				return {
					file: {
					    size: 123, //@todo de implementat in db
						type: "image/jpeg", //@todo de implementat in db
					    name: fileObj.name
					},
					url: fileObj.url,
				};
			});

			dropzoneData.forEach(function(value) {
				if (value.url) {
					dropzoneEl.manuallyAddFile(value.file, value.url);
				}
			});
		}
	},
    computed: {
        listingImages: {
            get: function () {
                return this.listingData.photos || [];
            },
            set: function (val) {
                this.$emit('update', {photos: val})
            }
        },
    },
	methods: {
		dropzoneClick: function() {
			document.getElementById("dropzoneCreateListing").click();
		},
		successUpload(file, response) {
			if (typeof response !== "undefined") {
                let file = {
                    name: response.result.name,
                    url: response.result.url,
                    order: 0,
                    main: 0,
                };

                let files = this.listingImages;
                files.push(file);

                this.listingImages = files;
            }
		},
		removeFile(file, error, xhr) {
		    let parent = this;

			if (
				typeof file.manuallyAdded !== "undefined" &&
				file.manuallyAdded === true
			) {
                this.listingImages = this.listingImages.filter(function( fileObj ) {
                    if(fileObj['id'] !== undefined){
                        let unlink_photos = parent.listingData.unlink_photos || [];
                        unlink_photos.push(fileObj);

                        parent.$emit('update', {unlink_photos: unlink_photos})
					}

                    return fileObj.name !== file.name;
                });
			} else if (
				typeof file.xhr !== "undefined" &&
				file.xhr.response !== "undefined"
			) {
				let response = JSON.parse(file.xhr.response);

                this.listingImages = this.listingImages.filter(function( fileObj ) {
                    return fileObj.name !== response.result.name;
                });
			}
		}
	}
};
</script>

<style lang="scss">
@import "~assets/scss/abstracts/_abstracts.scss";

.page_create_listing {
	.vue-dropzone {
		background: $white;
		padding: 0;
		border: none;
		display: flex;
		flex-wrap: wrap;

		&:hover {
			background: $white;
		}

		&.dz-started {
			.dropzone-custom-content {
				padding-top: 58px;
				min-height: 299px;
				margin-top: 20px;
			}

			.dz-remove {
				margin-left: 0;
				top: -7px;
				right: -7px;
				font-size: 0;
				width: 26px;
				height: 26px;
				border-radius: 50%;
				background: $valencia;
				opacity: 1;
				padding: 0;

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

				&:before {
					content: "\e917";
					position: absolute;
					top: 50%;
					left: 54%;
					transform: translate(-50%, -50%);
					color: $white;
					font-size: 11px;
				}
			}
		}
	}
}

.dropzone-custom-content {
	border: 2px dashed #707070;
	border-radius: 4px;
	order: 3;
	width: 100%;
	padding: 98px 20px 20px;
	min-height: 415px;
	position: relative;
	text-align: center;
	cursor: pointer !important;

	i,
	h3,
	.uploadButton,
	.drop_image {
		cursor: pointer !important;
	}

	i {
		font-size: 60px;
	}

	h3 {
		font-size: 18px;
		color: $gray_aluminium;
		line-height: 24px;
		margin-bottom: 29px;
	}

	.uploadButton {
		@include open_sans(700);
		text-transform: uppercase;
		border-radius: 5px;
		border: 1px solid $gray_ghost;
		padding: 17px 10px 19px;
		display: inline-block;
		min-width: 136px;
		font-size: 10px;
		letter-spacing: 1px;
		text-align: center;
		color: $gray_aluminium;
	}
}

.drop_image {
	margin-bottom: 35px;
}
</style>
