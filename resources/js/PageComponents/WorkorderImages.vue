<template>

<ValidationObserver>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
        <div class="max-w-sm rounded-3xl overflow-hidden border shadow-sm" :class="{' flex items-center justify-center cursor-pointer text-gray-800 hover:text-gray-600 active:text-gray-900 focus:outline-none transition ease-in-out duration-150': image.filename === null}" v-for="(image, index) in object.images" :key="index"  @click="image.filename === null ? upload_image(index) : ''">
            <input type="file" accept="image/*,video/*" v-show="false" v-on:change="change_image($event, index)" :ref="'choose_image'" autocomplete="none">
            <template v-if="image.filename === null">
                <div class="py-10">
                    <camera-icon :size="120"></camera-icon>
                </div>
            </template>
            <div class="h-full relative" v-else>
                <div class="absolute top-0 m-2 z-50" v-if="!view_only && (image.created_by == null || $page.user.role == 'admin' || $page.user.role == image.created_by.role)">
                    <jet-button type="button" shape="circle" class_type="danger" @click.native.stop="delete_image(index)"><trash-can-outline-icon></trash-can-outline-icon></jet-button>
                </div>

                <img v-if="image.type == 'image'" class="w-full cursor-pointer" :src="'/get_workorder_image?filename=' + image.filename" alt="Uploaded Image" @click="lightbox_image_index = index">
                <video v-else class="w-full cursor-pointer" :src="(image.filename != null && image.filename != '') ? ('/get_workorder_image?filename=' + image.filename) : ''" alt="Uploaded Video" controls></video>
                <template v-if="!view_only && (image.created_by == null || $page.user.role == 'admin' || $page.user.role == image.created_by.role)">
                    <jet-textarea :id="'image_description_' + index" v-model="image.description" class_override="w-full px-6 pt-4 pb-6 focus:outline-none" placeholder="Type a description..." autocomplete="image.description"/>
                </template>
                <template v-else>
                    <div class="pt-2 pb-8 px-6">{{ image.description }}</div>
                </template>
                <div v-if="image.created_by_user_id != null" class="absolute bottom-0 right-0 mt-3 mb-2 mx-6 text-sm text-gray-600 whitespace-no-wrap">- {{ image.created_by.name }}</div>
            </div>
        </div>
    </div>

    <jet-image-modal
        :show="lightbox_image_index !== null"
        @close="lightbox_image_index = null"
        :img_src="lightbox_image_index !== null ? '/get_workorder_image?filename=' + object.images[lightbox_image_index].filename : null"
        :img_description="lightbox_image_index !== null ? object.images[lightbox_image_index].description : null"
    ></jet-image-modal>
</ValidationObserver>

</template>

<script>
    import JetButton from '@/Jetstream/Button';
    import JetLabel from '@/Jetstream/Label';
    import JetTextarea from '@/Jetstream/Textarea';
    import JetInputError from '@/Jetstream/InputError';
    import JetImageModal from '@/Jetstream/ImageModal';

    import { ValidationObserver, ValidationProvider } from 'vee-validate';

    import Compressor from 'compressorjs';

    const empty_image = {
        'id': null,
        'workorder_id': null,
        'created_by_user_id': null,
        'created_by_user_id': null,
        'description': null,
        'type': null,
        'filename': null,
        'created_at': null,
        'updated_at': null,
        // With
        'created_by': null,
    };

    export default {
        props: {
            object: {
                type: Object,
                default: {},
            },
            
            view_only: {
                type: Boolean,
                default: false,
            },
        },

        components: {
            JetButton,
            JetLabel,
            JetTextarea,
            JetInputError,
            JetImageModal,
            ValidationObserver,
            ValidationProvider
        },

        data () {
            return {
                lightbox_image_index: null,
            }
        },

        created() {
            // Always add an empty image
            if (!this.view_only && (this.object.images.length == 0 || this.object.images[this.object.images.length - 1].filename != null)) this.add_image();
        },

        methods: {
            add_image () {
                let new_image = JSON.parse(JSON.stringify(empty_image));
                this.object.images.push(new_image);
            },

            delete_image (index) {
                console.log("Index: " + index);
                if (this.object.deleted_images != undefined) this.object.deleted_images.push(this.object.images[index].id)
                this.object.images.splice(index, 1);
                
                // If no images, add an empty one
                if (this.object.images.length == 0) this.add_image();
                console.log("deleted image");
            },

            upload_image: function (index) {
                this.$refs['choose_image'][index].click();
            },

            change_image: function (event, index) {
                this.upload_temp_image(event.target.files[0], index);
            },

            upload_temp_image: function (file, index) {
                let vm = this;

                if ((file.type).includes('image/'))
                {
                    if (file.type != "image/png" && file.type != "image/jpeg" && file.type != 'image/gif')
                    {
                        alert('File type not allowed. Must be one of ".png", ".jpg", ".jpeg", or ".gif"');
                        return false;
                    }

                    let compress = new Compressor(file, {
                        checkOrientation: true,
                        maxWidth: 1000,
                        maxHeight: 1000,
                        quality: 0.8,
                        success: function (result) {
                            let reader = new FileReader();
                            reader.readAsDataURL(result); 
                            reader.onloadend = function() {
                                let base64data = reader.result;    
                                
                                let data = [];
                                data.push({'compressed_file': base64data});

                                axios.post('/upload_temp_workorder_image', data)
                                .then(function (retval) {
                                    if (retval.data.result == 'success')
                                    {
                                        vm.object.images[index].filename = retval.data.filename;
                                        vm.object.images[index].type = 'image';

                                        // Reset the Image upload field
                                        // this.$refs['choose_image'][0].value = '';

                                        // Add an empty Image
                                        vm.add_image();
                                    }
                                    else if (retval.data.result == 'fail')
                                    {
                                        vm.object.images[index].filename = null;

                                        // vm.alert_message = d.data.msg;
                                        // vm.alert_modal = true;
                                    }
                                })
                                .catch(function (error) {
                                    vm.object.images[index].filename = null;
                                    
                                    // vm.alert_message = error;
                                    // vm.alert_modal = true;
                                });
                            }
                        },
                        error: function (error) {
                            vm.object.images[index].filename = null;

                            // vm.alert_message = error;
                            // vm.alert_modal = true;
                        }
                    });
                }
                else if ((file.type).includes('video/'))
                {
                    let reader = new FileReader();
                    reader.readAsDataURL(file); 
                    reader.onloadend = function() {
                        let base64data = reader.result;    
                        
                        let data = [];
                        data.push({'compressed_file': base64data});

                        axios.post('/upload_temp_workorder_image', data)
                        .then(function (retval) {
                            if (retval.data.result == 'success')
                            {
                                vm.object.images[index].filename = retval.data.filename;
                                vm.object.images[index].type = 'video';

                                // Reset the Image upload field
                                // this.$refs['choose_image'][0].value = '';

                                // Add an empty Image
                                vm.add_image();
                            }
                            else if (retval.data.result == 'fail')
                            {
                                vm.object.images[index].filename = retval.data.filename;

                                // vm.alert_message = d.data.msg;
                                // vm.alert_modal = true;
                            }
                        })
                        .catch(function (error) {
                            vm.object.images[index].filename = null;
                            
                            // vm.alert_message = error;
                            // vm.alert_modal = true;
                        });
                    }
                }
            },
        }
    }
</script>

