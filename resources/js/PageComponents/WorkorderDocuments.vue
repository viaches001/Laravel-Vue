<template>

<ValidationObserver>
    <div class="flex flex-col" v-for="(document, index) in object.documents" :key="index">
        <div class="flex flex-row gap-4">
            <div class="flex-grow mb-2">
                <jet-label :for="'document_title_' + index" :index="index" value="Title" />
                <template v-if="!view_only">
                    <ValidationProvider :name="'document_title_' + index" :rules="{'required': document.filename != null && document.filename != ''}" v-slot="{ errors }">
                        <jet-input :id="'document_title_' + index" type="text" v-model="document.title" :errors="object.error('document.title') || errors.length > 0" autocomplete="description"/>
                        <jet-input-error :message="object.error('document.title')" v-if="object.error('document.title')"/>
                        <jet-input-error :message="errors[0]" v-else/>
                    </ValidationProvider>
                </template>
                <template v-else>
                    <jet-input :id="'document_title_' + index" type="text" v-model="document.title" disabled autocomplete="description"/>
                </template>
            </div>

            <div class="hidden md:block flex-grow mb-2">
                <jet-label :for="'document_description_' + index" :index="index" value="Description" />
                <template v-if="!view_only">
                        <jet-input :id="'document_description_' + index" type="text" v-model="document.description" :errors="object.error('document.description')" autocomplete="description"/>
                        <jet-input-error :message="object.error('document.description')" v-if="object.error('document.description')"/>
                </template>
                <template v-else>
                    <jet-input :id="'document_description_' + index" type="text" v-model="document.description" disabled autocomplete="description"/>
                </template>
            </div>

            <div class="flex-shrink mb-2">
                <input type="file" v-show="false" v-on:change="change_document($event, index)" :ref="'choose_file'" autocomplete="none">
                <jet-button type="button" shape="circle" class_type="secondary" :class="{'mt-6': index === 0}" @click.native="upload_document(index)" v-if="document.filename === null"><upload-icon></upload-icon></jet-button>
                <jet-button type="button" shape="circle" class_type="secondary" :class="{'mt-6': index === 0}" @click.native="download_document(index)" v-else><download-icon></download-icon></jet-button>
            </div>

            <div class="flex-shrink mb-2" v-if="!view_only">
                <jet-button type="button" shape="circle" class_type="danger" :class="{'mt-6': index === 0}" :disabled="object.documents.length == 1" @click.native="delete_document(index)"><trash-can-outline-icon></trash-can-outline-icon></jet-button>
            </div>
        </div>
    </div>

    <div class="flex flex-row gap-4" v-if="!view_only">
        <div class="flex-grow"></div>
        <div class="flex-shrink">
            <jet-button type="button" shape="circle" class_type="secondary" @click.native="add_document()"><plus-icon></plus-icon></jet-button>
        </div>
    </div>
</ValidationObserver>

</template>

<script>
    import JetButton from '@/Jetstream/Button';
    import JetLabel from '@/Jetstream/Label';
    import JetInput from '@/Jetstream/Input';
    import JetInputError from '@/Jetstream/InputError';

    import { ValidationObserver, ValidationProvider } from 'vee-validate';
    
    const empty_document = {
        'id': null,
        'workorder_id': null,
        'title': null,
        'description': null,
        'filename': null,
        'created_at': null,
        'updated_at': null,
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
            JetInput,
            JetInputError,
            ValidationObserver,
            ValidationProvider
        },

        created() {
            // If no documents, add an empty one
            if (!this.view_only && this.object.documents.length == 0) this.add_document();
        },

        methods: {
            add_document () {
                let new_document = JSON.parse(JSON.stringify(empty_document));
                this.object.documents.push(new_document);
            },

            delete_document (index) {
                this.object.documents.splice(index, 1);
            },

            upload_document: function (index) {
                this.$refs['choose_file'][index].click();
            },

            change_document: function (e, index) {
                this.upload_temp_document(e.target.files[0], index);
            },

            upload_temp_document: function (file, index) {
                let vm = this;

                let data = new FormData();
                data.append('file', file);
            
                axios.post('/upload_temp_workorder_document', data)
                .then(function(retval){
                    if (retval.data.result == 'success')
                    {
                        vm.object.documents[index].title = retval.data.title;
                        vm.object.documents[index].filename = retval.data.filename;

                        // Reset the File upload field
                        // this.$refs['choose_image'][0].value = '';
                    }
                    else if (retval.data.result == 'fail')
                    {
                        vm.object.documents[index].filename = null;
                        alert(retval.data.msg);
                        // $('#alertModal .modal-body').text(retval.data.msg);
                        // $('#alertModal').modal('show');
                    }
                })
                .catch(function (error) {
                    vm.object.documents[index].filename = null;
                    // $('#alertModal .modal-body').text(error);
                    // $('#alertModal').modal('show');
                });
            },

            download_document: function (index) {
                location.href = '/download_workorder_document?filename=' + encodeURIComponent(this.object.documents[index].filename) + '&title=' + encodeURIComponent(this.object.documents[index].title);
            },
        }
    }
</script>

