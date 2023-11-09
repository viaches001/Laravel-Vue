<template>
    <app-layout>
        <template #header>
            <span class="md:hidden">Customer</span>
            <span class="hidden md:inline-block">
                <span v-if="customer.id === null"><span class="text-gray-400">Customers</span><span class="text-gray-500"> / </span>Add</span>
                <span v-else><span class="text-gray-400">Customers</span><span class="text-gray-500"> / </span>{{ customer.name }}</span>
            </span>
        </template>
        <template #header_buttons>
            <span class="sm:hidden">
                <jet-button type="button" shape="circle" class_type="primary" @click.native="submit_form()" :disabled="form.processing"><content-save-outline-icon></content-save-outline-icon></jet-button>
                <jet-button type="button" shape="circle" class_type="secondary" @click.native="$inertia.visit(route('customers.customers'))" :disabled="form.processing"><close-icon></close-icon></jet-button>
            </span>
            <span class="hidden sm:inline-block">
                <jet-button type="button" class_type="primary" @click.native="submit_form()" :disabled="form.processing">Save</jet-button>
                <jet-button type="button" class_type="secondary" @click.native="$inertia.visit(route('customers.customers'))" :disabled="form.processing">Cancel</jet-button>
            </span>
        </template>

        <ValidationObserver ref="observer">
            <form action="#" method="post" @submit.prevent="handle_submit_form" ref="customer_form">
                <input type="submit" value="Save" style="display: none;" ref="submit_button" />

                <div class="grid grid-cols-12 gap-4 mb-12">
                    <div class="col-span-3">
                        <div class="font-bold text-xl md:text-2xl lg:text-3xl text-gray-800 leading-none mx-3 mb-4">Customer Info</div>

                        <div class="mb-2">
                            <jet-label for="name" value="Name" />
                            <ValidationProvider name="name" rules="required" v-slot="{ errors }">
                                <jet-input id="name" type="text" v-model="form.name" :errors="form.error('name') || errors.length > 0" autocomplete="name"/>
                                <jet-input-error :message="form.error('name')" v-if="form.error('name')"/>
                                <jet-input-error :message="errors[0]" v-else/>
                            </ValidationProvider>
                        </div>

                        <div class="mb-2">
                            <jet-label for="street1" value="Street 1" />
                            <jet-input id="street1" type="text" v-model="form.street1" :errors="form.error('street1')" autocomplete="street1"/>
                            <jet-input-error :message="form.error('street1')" v-if="form.error('street1')"/>
                        </div>

                        <div class="mb-2">
                            <jet-label for="street2" value="Street 2" />
                            <jet-input id="street2" type="text" v-model="form.street2" :errors="form.error('street2')" autocomplete="street2"/>
                            <jet-input-error :message="form.error('street2')" v-if="form.error('street2')"/>
                        </div>

                        <div class="mb-2">
                            <jet-label for="city" value="City" />
                            <jet-input id="city" type="text" v-model="form.city" :errors="form.error('city')" autocomplete="city"/>
                            <jet-input-error :message="form.error('city')" v-if="form.error('city')"/>
                        </div>

                        <div class="mb-2">
                            <jet-label for="prov_state" value="Province" />
                            <jet-select id="prov_state" type="text" v-model="form.prov_state" :options="[{value: 'AB', label: 'AB - Alberta'}, {value: 'BC', label: 'BC - British Columbia'}, {value: 'MB', label: 'MB - Manitoba'}, {value: 'NB', label: 'NB - New Brunswick'}, {value: 'NL', label: 'NL - Newfoundland and Labrador'}, {value: 'NT', label: 'NT - Northwest Territories'}, {value: 'NS', label: 'NS - Nova Scotia'}, {value: 'NU', label: 'NU - Nunavut'}, {value: 'ON', label: 'ON - Ontario'}, {value: 'PE', label: 'PE - Prince Edward Island'}, {value: 'QC', label: 'QC - Quebec'}, {value: 'SK', label: 'SK - Saskatchewan'}, {value: 'YT', label: 'YT - Yukon'}]" value_field="value" label_field="label"  :errors="form.error('prov_state')" autocomplete="prov_state"/>
                            <jet-input-error :message="form.error('prov_state')" v-if="form.error('prov_state')"/>
                        </div>

                        <div class="mb-2">
                            <jet-label for="postal_zip" value="Postal" />
                            <jet-input id="postal_zip" type="text" v-model="form.postal_zip" :errors="form.error('postal_zip')" autocomplete="postal_zip"/>
                            <jet-input-error :message="form.error('postal_zip')" v-if="form.error('postal_zip')"/>
                        </div>
                    </div>
                    <div class="col-span-9">
                        <div class="mb-12">
                            <div class="font-bold text-xl md:text-2xl lg:text-3xl text-gray-800 leading-none mx-6 mb-1">Readings</div>
                            <div class="mb-4">
                                <LineChartGenerator
                                    :chart-options="{
                                        responsive: true,
                                        maintainAspectRatio: false
                                    }"
                                    :chart-data="{
                                        labels: [
                                            'January',
                                            'February',
                                            'March',
                                            'April',
                                            'May',
                                            'June',
                                            'July'
                                        ],
                                        datasets: [{
                                            label: 'Data One',
                                            backgroundColor: '#f87979',
                                            data: [40.9, 39, 10, 40, 39, 80, 40]
                                        }]
                                    }"
                                    :chart-id="'line-chart'"
                                    :dataset-id-key="'label'"
                                    :width="400"
                                    :height="200"
                                />
                            </div>
                            <div class="flex flex-col" v-for="(reading, index) in form.readings" :key="index">
                                <div class="flex flex-wrap flex-row gap-x-4" :class="{ 'mb-8': index !== (form.readings.length - 1) }">
                                    <div class="flex-grow flex-shrink w-1/5 mb-2">
                                        <jet-label :key="'reading_type_' + index" :index="index" hidden_classes="3xl:hidden" :for="'reading_type_' + index" value="Type" />
                                        <ValidationProvider :name="'reading_type_' + index" rules="required" v-slot="{ errors }">
                                            <jet-input :id="'reading_type_' + index" type="text" v-model="reading.type" :errors="form.error('reading_type_' + index) || errors.length > 0" autocomplete="type"/>
                                            <jet-input-error :message="form.error('reading_type_' + index)" v-if="form.error('reading_type_' + index)"/>
                                            <jet-input-error :message="errors[0]" v-else/>
                                        </ValidationProvider>
                                    </div>

                                    <div class="flex-grow flex-shrink w-auto mb-2">
                                        <jet-label :key="'reading_location_' + index" :index="index" hidden_classes="3xl:hidden" value="Location" />
                                        <ValidationProvider :name="'reading_location_' + index" rules="required" v-slot="{ errors }">
                                            <jet-input :id="'reading_location_' + index" type="text" v-model="reading.location" :errors="form.error('reading_location_' + index) || errors.length > 0" autocomplete="location"/>
                                            <jet-input-error :message="form.error('reading_location_' + index)" v-if="form.error('reading_location_' + index)"/>
                                            <jet-input-error :message="errors[0]" v-else/>
                                        </ValidationProvider>
                                    </div>

                                    <div class="flex-grow flex-shrink w-1/5 mb-2">
                                        <jet-label :key="'reading_description_' + index" :index="index" hidden_classes="3xl:hidden" value="Description" />
                                        <ValidationProvider :name="'reading_description_' + index" rules="required" v-slot="{ errors }">
                                            <jet-input :id="'reading_description_' + index" type="text" v-model="reading.description" :errors="form.error('reading_description_' + index) || errors.length > 0" autocomplete="description"/>
                                            <jet-input-error :message="form.error('reading_description_' + index)" v-if="form.error('reading_description_' + index)"/>
                                            <jet-input-error :message="errors[0]" v-else/>
                                        </ValidationProvider>
                                    </div>

                                    <div class="flex-grow flex-shrink w-1/5 mb-2">
                                        <jet-label :for="'reading_frequency_type_' + index" :index="index" hidden_classes="3xl:hidden" value="Frequency Type" />
                                        <ValidationProvider :name="'reading_frequency_type_' + index" rules="required" v-slot="{ errors }">
                                            <jet-select :id="'reading_frequency_type_' + index" v-model="form.frequency_type" :options="[]" value_field="value" label_field="label" :errors="form.error('reading_frequency_type_' + index) || errors.length > 0" autocomplete="frequency_type" />
                                            <jet-input-error :message="form.error('reading_frequency_type_' + index)" v-if="form.error('reading_frequency_type_' + index)"/>
                                            <jet-input-error :message="errors[0]" v-else/>
                                        </ValidationProvider>
                                    </div>

                                    <div class="flex-grow flex-shrink w-1/5 mb-2">
                                        <jet-label :for="'reading_frequency_days_' + index" :index="index" hidden_classes="3xl:hidden" value="Frequency Days" />
                                        <ValidationProvider :name="'reading_frequency_days_' + index" rules="required" v-slot="{ errors }">
                                            <jet-select :id="'reading_frequency_days_' + index" v-model="form.frequency_days" :options="[]" value_field="value" label_field="label" :errors="form.error('reading_frequency_days_' + index) || errors.length > 0" autocomplete="frequency_days" />
                                            <jet-input-error :message="form.error('reading_frequency_days_' + index)" v-if="form.error('reading_frequency_days_' + index)"/>
                                            <jet-input-error :message="errors[0]" v-else/>
                                        </ValidationProvider>
                                    </div>

                                    <div class="flex-grow flex-shrink w-1/5 mb-2">
                                        <jet-label :for="'reading_min_hours_' + index" :index="index" hidden_classes="3xl:hidden" value="Min Hours" />
                                        <ValidationProvider :name="'reading_min_hours_' + index" rules="required" v-slot="{ errors }">
                                            <jet-input :id="'reading_min_hours_' + index" v-model="form.min_hours" :errors="form.error('reading_min_hours_' + index) || errors.length > 0" autocomplete="min_hours" />
                                            <jet-input-error :message="form.error('reading_min_hours_' + index)" v-if="form.error('reading_min_hours_' + index)"/>
                                            <jet-input-error :message="errors[0]" v-else/>
                                        </ValidationProvider>
                                    </div>

                                    <div class="flex-grow flex-shrink w-1/5 mb-2">
                                        <jet-label :for="'reading_max_hours_' + index" :index="index" hidden_classes="3xl:hidden" value="Max Hours" />
                                        <ValidationProvider :name="'reading_max_hours_' + index" rules="required" v-slot="{ errors }">
                                            <jet-input :id="'reading_max_hours_' + index" v-model="form.max_hours" :errors="form.error('reading_max_hours_' + index) || errors.length > 0" autocomplete="max_hours" />
                                            <jet-input-error :message="form.error('reading_max_hours_' + index)" v-if="form.error('reading_max_hours_' + index)"/>
                                            <jet-input-error :message="errors[0]" v-else/>
                                        </ValidationProvider>
                                    </div>

                                    <div class="flex-grow w-1/5 mb-2">
                                        <jet-label :for="'reading_for_' + index" :index="index" hidden_classes="3xl:hidden" value="Assigned To" />
                                        <ValidationProvider :name="'reading_for_' + index" rules="required" v-slot="{ errors }">
                                            <jet-select :id="'reading_for_' + index" type="text" v-model="reading.for" :options="[]" value_field="value" label_field="label" :errors="form.error('reading_for_' + index)" autocomplete="for"/>
                                            <jet-input-error :message="form.error('reading_for_' + index)" v-if="form.error('reading_for_' + index)"/>
                                            <jet-input-error :message="errors[0]" v-else/>
                                        </ValidationProvider>
                                    </div>

                                    <div class="flex-grow lg:flex-grow-0 lg:flex-shrink mb-2" style="max-width: calc(100% - 42px - 1rem)">
                                        <jet-label :for="'reading_units_' + index" :index="index" hidden_classes="3xl:hidden" value="Units" />
                                        <jet-input :id="'reading_units_' + index" type="text" v-model="reading.units" :errors="form.error('reading_units_' + index)" autocomplete="units"/>
                                        <jet-input-error :message="form.error('reading_units_' + index)" v-if="form.error('reading_units_' + index)"/>
                                    </div>

                                    <div class="flex-shrink mb-2">
                                        <jet-button type="button" shape="circle" class_type="danger" class="mt-6" :disabled="form.readings.length == 1" @click.native="delete_reading(index)"><trash-can-outline-icon></trash-can-outline-icon></jet-button>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <jet-button type="button" shape="circle" class_type="secondary" @click.native="add_reading()"><plus-icon></plus-icon></jet-button>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="font-bold text-xl md:text-2xl lg:text-3xl text-gray-800 leading-none mx-6 mb-4">Supervisors</div>
                            <div class="flex flex-col" v-for="(supervisor, index) in form.supervisors" :key="index">
                                <div class="flex flex-wrap flex-row gap-x-4" :class="{ 'mb-8 md:mb-12 2xl:mb-0': index !== (form.supervisors.length - 1) }">
                                    <div class="flex-grow w-1/5 mb-2">
                                        <jet-label :key="'supervisor_name_' + index" :index="index" hidden_classes="2xl:hidden" value="Name" />
                                        <ValidationProvider :name="'supervisor_name_' + index" :rules="{'required': (supervisor.name != null && supervisor.name != '') || (supervisor.method != null && supervisor.method != '') || (supervisor.email != null && supervisor.email != '') || (supervisor.phone != null && supervisor.phone != '') }" v-slot="{ errors }">
                                            <jet-input :id="'supervisor_name_' + index" type="text" v-model="supervisor.name" :errors="form.error('supervisor_name_' + index) || errors.length > 0" autocomplete="name"/>
                                            <jet-input-error :message="form.error('supervisor_name_' + index)" v-if="form.error('supervisor_name_' + index)"/>
                                            <jet-input-error :message="errors[0]" v-else/>
                                        </ValidationProvider>
                                    </div>

                                    <div class="flex-grow w-1/5 mb-2">
                                        <jet-label :for="'supervisor_method_' + index" :index="index" hidden_classes="2xl:hidden" value="Method" />
                                        <jet-input :id="'supervisor_method_' + index" type="text" v-model="supervisor.method" :errors="form.error('supervisor_method_' + index)" autocomplete="method"/>
                                        <jet-input-error :message="form.error('supervisor_method_' + index)" v-if="form.error('supervisor_method_' + index)"/>
                                    </div>

                                    <div class="flex-grow w-1/5 mb-2">
                                        <jet-label :for="'supervisor_email_' + index" :index="index" hidden_classes="2xl:hidden" value="Email" />
                                        <jet-input :id="'supervisor_email_' + index" type="text" v-model="supervisor.email" :errors="form.error('supervisor_email_' + index)" autocomplete="email"/>
                                        <jet-input-error :message="form.error('supervisor_email_' + index)" v-if="form.error('supervisor_email_' + index)"/>
                                    </div>

                                    <div class="flex-grow lg:flex-grow-0 lg:flex-shrink mb-2" style="max-width: calc(100% - 42px - 1rem)">
                                        <jet-label :for="'supervisor_phone_' + index" :index="index" hidden_classes="2xl:hidden" value="Phone" />
                                        <jet-input :id="'supervisor_phone_' + index" type="text" v-model="supervisor.phone" :errors="form.error('supervisor_phone_' + index)" autocomplete="phone"/>
                                        <jet-input-error :message="form.error('supervisor_phone_' + index)" v-if="form.error('supervisor_phone_' + index)"/>
                                    </div>

                                    <div class="flex-shrink mb-2">
                                        <jet-button type="button" shape="circle" class_type="danger" :class="index === 0 ? 'mt-6' : 'mt-6 2xl:mt-0'" :disabled="form.supervisors.length == 1" @click.native="delete_supervisor(index)"><trash-can-outline-icon></trash-can-outline-icon></jet-button>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <jet-button type="button" shape="circle" class_type="secondary" @click.native="add_supervisor()"><plus-icon></plus-icon></jet-button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-right my-6" v-if="customer.id != null">
                    <jet-button type="button" class_type="danger" @click.native="confirming_delete = true" :disabled="form.processing">Delete Customer</jet-button>
                </div>

                <!-- Modals -->
                <jet-confirm-modal :show="confirming_delete">
                    <template #title>
                        Delete Customer?
                    </template>
                    <template #content>
                        This will delete this record forever. Are you sure?
                    </template>
                    <template #footer>
                        <jet-button type="button" class_type="primary" @click.native="delete_customer();">Delete</jet-button>
                        <jet-button type="button" class_type="secondary" @click.native="confirming_delete = false">Cancel</jet-button>
                    </template>
                </jet-confirm-modal>
            </form>
        </ValidationObserver>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetButton from '@/Jetstream/Button';
import JetLabel from '@/Jetstream/Label';
import JetInput from '@/Jetstream/Input';
import JetSelect from '@/Jetstream/Select';
import JetInputError from '@/Jetstream/InputError';
import JetConfirmModal from '@/Jetstream/ConfirmModal';

import { Line as LineChartGenerator } from 'vue-chartjs/legacy'
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, LinearScale, CategoryScale, PointElement } from 'chart.js'

import { ValidationObserver, ValidationProvider } from 'vee-validate';

ChartJS.register(Title, Tooltip, Legend, LineElement, LinearScale, CategoryScale, PointElement);

const empty_supervisor = {
    'id': null,
    'customer_id': null,
    'type': null,
    'name': null,
    'email': null,
    'phone': null,
    'fax': null,
    'created_at': null,
    'updated_at': null,
    'deleted_at': null
};

export default {
    components: {
        AppLayout,
        JetButton,
        JetLabel,
        JetInput,
        JetSelect,
        JetInputError,
        JetConfirmModal,

        LineChartGenerator,

        ValidationObserver,
        ValidationProvider
    },

    props: ['customer'],

    data () {
        return {
            loading: true,

            confirming_delete: false,

            form: this.$inertia.form({
                name: this.customer.name,
                street1: this.customer.street1,
                street2: this.customer.street2,
                city: this.customer.city,
                prov_state: this.customer.prov_state,
                postal_zip: this.customer.postal_zip,
                readings: this.customer.readings !== undefined ? this.customer.readings : [],
                supervisors: this.customer.supervisors !== undefined ? this.customer.supervisors : [],
            })
        }
    },

    created () {
        // If no supervisors, add an empty one
        if (this.form.supervisors.length == 0) this.add_supervisor();
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
            }, 100);
        },

        submit_form () {
            this.$refs.submit_button.click();
        },

        handle_submit_form () {
            this.$refs.observer.validate().then((result) => {
                if (result)
                {
                    if (this.customer.id === null) this.form.post(route('customers.customer_add_store'));
                    else this.form.post(route('customers.customer_edit_store', {'customer': this.customer.id}));
                }
                else this.scroll_to_first_error();
            });
        },

        delete_customer () {
            this.$inertia.visit(route('customers.customer_delete', {'customer': this.customer.id, }));
        },

        add_supervisor () {
            let new_supervisor = JSON.parse(JSON.stringify(empty_supervisor));
            this.form.supervisors.push(new_supervisor);
        },

        delete_supervisor (index) {
            this.form.supervisors.splice(index, 1);
        }
    }
}
</script>
