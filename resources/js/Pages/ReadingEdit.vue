<template>
    <app-layout>
        <template #header>
            <span class="md:hidden">Readings</span>
            <span class="hidden md:inline-block">
                <span class="text-gray-400">Readings</span><span class="text-gray-500"> / </span>{{ customer.name }}
                <div class="mb-1"> </div>
                <span class="text-3xl text-gray-700"> {{ formatted_date }} </span>
            </span>
        </template>
        <template #header_buttons>
            <span class="sm:hidden">
                <jet-button type="button" shape="circle" class_type="secondary" @click.native="$inertia.visit(route('readings.readings'))"><close-icon></close-icon></jet-button>
            </span>
            <span class="hidden sm:inline-block">
                <jet-button type="button" class_type="secondary" @click.native="$inertia.visit(route('readings.readings'))">Close</jet-button>
            </span>
        </template>

        <ValidationObserver ref="observer">
            <form v-for="(reading_form, index) in reading_forms" :key="index" action="#" method="post" @submit.prevent="handle_submit_form(index)" :ref="'reading_form_'+index" class="mb-8">
                <input type="submit" value="Submit" style="display: none;" :ref="'submit_button' + index" />

                <jet-form-section>
                    <template slot="content">
                        <div class="font-bold text-xl md:text-2xl lg:text-3xl text-gray-800 leading-none mb-2">
                            <span> {{ reading_form.type }} </span>
                            <span> - </span>
                            <span> {{ reading_form.description }} </span>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-auto w-32 mb-2">
                                <jet-label :for="'distribution_location_'+index" value="Location" />
                                <ValidationProvider name="distribution_location" rules="required" v-slot="{ errors }">
                                    <jet-input :id="'distribution_location_'+index" type="text" v-model="reading_form.distribution_location" :disabled="reading_form.location=='primary'" :errors="reading_form.error('distribution_location') || errors.length > 0" autocomplete="distribution_location"/>
                                    <jet-input-error :message="reading_form.error('distribution_location')" v-if="reading_form.error('distribution_location')"/>
                                    <jet-input-error :message="errors[0]" v-else/>
                                </ValidationProvider>
                            </div>

                            <div class="flex-auto w-64 mb-2">
                                <jet-label :for="'reading_'+index" :value="'Reading(' + reading_form.units + ')'" />
                                <ValidationProvider name="reading" rules="required" v-slot="{ errors }">
                                    <jet-input :id="'reading_'+index" type="number" v-model="reading_form.reading" :errors="reading_form.error('reading') || errors.length > 0" autocomplete="reading"/>
                                    <jet-input-error :message="reading_form.error('reading')" v-if="reading_form.error('reading')"/>
                                    <jet-input-error :message="errors[0]" v-else/>
                                </ValidationProvider>
                            </div>

                            <div class="flex-none mb-2">
                                <jet-button type="button" class="mt-6 bg-green-500 hover:bg-green-700" @click.native="confirming_submit = true" :disabled="reading_form.processing"><check-icon/></jet-button>
                            </div>
                        </div>
                    </template>
                </jet-form-section>
            </form>

            <!-- Modals -->
            <jet-confirm-modal :show="confirming_submit">
                <template #title>
                    Submit Reading?
                </template>
                <template #content>
                    Are you sure?
                </template>
                <template #footer>
                    <jet-button type="button" class_type="primary" @click.native="submit_reading();">Submit</jet-button>
                    <jet-button type="button" class_type="secondary" @click.native="confirming_submit = false">Cancel</jet-button>
                </template>
            </jet-confirm-modal>
        </ValidationObserver>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetButton from '@/Jetstream/Button';
import JetFormSection from '@/Jetstream/FormSection';
import JetLabel from '@/Jetstream/Label';
import JetInput from '@/Jetstream/Input';
import JetInputError from '@/Jetstream/InputError';
import JetConfirmModal from '@/Jetstream/ConfirmModal';

import { ValidationObserver, ValidationProvider } from 'vee-validate';

export default {
    components: {
        AppLayout,
        JetButton,
        JetFormSection,
        JetLabel,
        JetInput,
        JetInputError,
        JetConfirmModal,

        ValidationObserver,
        ValidationProvider
    },

    props: ['customer', 'date'],

    data () {
        return {
            loading: true,

            confirming_submit: false,

            reading_forms: [
                this.$inertia.form({
                    type: 'Free Chlorine',
                    description: 'Tank1',
                    location: 'primary',
                    distribution_location: 'Pump House',
                    reading: '0',
                    units: 'Litres',
                }),
                this.$inertia.form({
                    type: 'Free Chlorine',
                    description: 'Distribution1',
                    location: 'distribution',
                    distribution_location: 'location1',
                    reading: '0',
                    units: 'Litres',
                }),
            ]
        }
    },

    computed: {
        formatted_date () {
            return moment(this.date).format("MMM DD, YYYY");
        },
    },

    methods: {
        // General
        scroll_to_first_error () {
            setTimeout(() => {
                let errors = Object.entries(this.$refs.observer.errors).map(([key, value]) => ({ key, value })).filter(error => error["value"].length);
                let ref_needed = this.$refs.observer.refs[errors[0]["key"]];
                if (ref_needed == undefined)
                {
                    this.$refs.observer.observers.forEach(nested_observer => {
                        let cur_ref_needed = nested_observer.refs[errors[0]["key"]];
                        if (cur_ref_needed != undefined)
                        {
                            ref_needed = cur_ref_needed;
                            return false
                        }
                    });
                }

                ref_needed.$el.scrollIntoViewIfNeeded(true);
            },100);
        },

        submit_reading () {
            this.$refs.submit_button.click();
        },
        // delete_item () {
        //     this.$inertia.visit(route('settings.item_delete', {'item': this.item.id}));
        // },

        handle_submit_form () {
            this.$refs.observer.validate().then((result) => {
                if (result)
                {
                    if (this.item.id === null) this.form.post(route('settings.item_add_store'));
                    else this.form.post(route('settings.item_edit_store', {'item': this.item.id}));
                }
                else this.scroll_to_first_error();
            });
        },
    }
}
</script>
