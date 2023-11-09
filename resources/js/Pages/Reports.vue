<template>
    <app-layout>
        <template #header>
            Reports
        </template>
        <div class="bg-white mb-4">
            <ValidationObserver v-slot="{ invalid }" class="print:hidden">
                <div class="flex gap-x-4">
                    <div class="flex-grow mb-2">
                        <jet-label for="search" value="Search" />
                        <ValidationProvider name="report" :rules="{'required': true}" v-slot="{ errors }">
                            <jet-input id="search" v-model="form.search" placeholder="Search..." :errors="form.error('search') || errors.length > 0" />
                            <jet-input-error :message="form.error('search')" v-if="form.error('search')"/>
                            <jet-input-error :message="errors[0]" v-else/>
                        </ValidationProvider>
                    </div>

                    <div class="flex-grow mb-2">
                        <jet-label for="customer_id" value="Customer" />
                        <ValidationProvider name="customer_id" :rules="{'required': true}" v-slot="{ errors }">
                            <jet-select id="customer_id" v-model="form.customer_id" :options="customers_with_all" value_field="id" label_field="name" :errors="form.error('customer_id') || errors.length > 0" :clearable="false"/>
                            <jet-input-error :message="form.error('customer_id')" v-if="form.error('customer_id')"/>
                            <jet-input-error :message="errors[0]" v-else/>
                        </ValidationProvider>
                    </div>

                    <div class="flex-shrink mb-2">
                        <jet-label for="from_date" value="From" />
                        <ValidationProvider name="from_date" rules="required" v-slot="{ errors }">
                            <jet-date-picker v-model="form.from_date" :errors="form.error('from_date') || errors.length > 0" />
                            <jet-input-error :message="form.error('from_date')" v-if="form.error('from_date')"/>
                            <jet-input-error :message="errors[0]" v-else/>
                        </ValidationProvider>
                    </div>

                    <div class="flex-shrink mb-2">
                        <jet-label for="to_date" value="To" />
                        <ValidationProvider name="to_date" rules="required" v-slot="{ errors }">
                            <jet-date-picker v-model="form.to_date" :errors="form.error('from_date') || errors.length > 0" />
                            <jet-input-error :message="form.error('to_date')" v-if="form.error('to_date')"/>
                            <jet-input-error :message="errors[0]" v-else/>
                        </ValidationProvider>
                    </div>

                    <div class="flex-shrink mb-2">
                        <jet-button type="button" shape="circle" class_type="secondary" class="mt-6" :disabled="invalid" @click.native="run_report()"><arrow-right-icon></arrow-right-icon></jet-button>
                    </div>
                </div>
            </ValidationObserver>
        </div>

        <div class="">
            <div class="bg-gray-200 p-2">
                <div class="grid grid-cols-2 justify-items-stretch items-center mb-1">
                    <div class="justify-self-start">
                        <p class="font-bold text-xl md:text-2xl lg:text-3xl"> Even Park </p>
                    </div>
                    <div class="justify-self-end flex gap-4 items-center">
                        <p class="font-normal text-base md:text-lg"> Reported </p>
                        <p class="flex h-5 w-5 rounded-full border-blue bg-blue-300"> </p>
                    </div>
                </div>
                <div class="mb-2">
                    <p class="font-normal text-base md:text-lg"> Reported Jul. 10, 2022 by Jim Greer </p>
                </div>
                <div class="mb-3">
                    <p class="justify-self-stretch font-normal text-base md:text-lg"> Some description of the issue that continues on and on. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla commodo mauris a turpis commodo, et mattis ante tristique. Curabitur pharetra convallis tortor facilisis scelerisque. Curabitur eu dapibus tortor. Suspendisse venenatis </p>
                </div>
                <div class="grid grid-cols-2 justify-items-stretch items-center">
                    <div class="justify-self-start">
                        <jet-button type="button" class_type="secondary" class="bg-slate-600 hover:bg-slate-700" @click.native="run_report()">Dismiss</jet-button>
                    </div>
                    <div class="justify-self-end">
                        <jet-button type="button" class_type="secondary" class="bg-blue-600 hover:bg-blue-700" @click.native="run_report()">Create Workorder</jet-button>
                        <jet-button type="button" class_type="secondary" class="bg-green-600 hover:bg-green-700" @click.native="run_report()">Resolve</jet-button>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout';
    import AlertMessage from '@/Jetstream/AlertMessage';
    import JetButton from '@/Jetstream/Button';
    import JetFormSection from '@/Jetstream/FormSection';
    import JetLabel from '@/Jetstream/Label';
    import JetInput from '@/Jetstream/Input';
    import JetSelect from '@/Jetstream/Select';
    import JetDatePicker from '@/Jetstream/DatePicker';
    import JetInputError from '@/Jetstream/InputError';

    // import ReportCustomerSummary from '@/PageComponents/ReportCustomerSummary';

    import { ValidationObserver, ValidationProvider } from 'vee-validate';

    export default {
        components: {
            AppLayout,
            AlertMessage,
            JetButton,
            JetFormSection,
            JetLabel,
            JetInput,
            JetSelect,
            JetDatePicker,
            JetInputError,
            // ReportCustomerSummary,
            ValidationObserver,
            ValidationProvider
        },

        props: ['customers', 'initial_report_data', 'errors'],

        data() {
            return {
                initial_load: true,
                loading: false,

                workorders: [],

                form: this.$inertia.form({
                    search: this.initial_report_data.search,
                    customer_id: this.initial_report_data.customer_id,
                    workorder_id: this.initial_report_data.workorder_id,
                    user_id: this.initial_report_data.user_id,
                    from_date: this.initial_report_data.from_date,
                    to_date: this.initial_report_data.to_date,

                    search_run: null,
                    customer_id_run: 'all',
                    from_date_run: null,
                    to_date_run: null,
                })
            }
        },

        mounted() {
            if (this.initial_report_data.onload) this.run_report();

            this.initial_load = false;
        },

        methods: {
            run_report () {
                this.form.search_run = null;
                this.form.customer_id_run = 'all';
                this.form.from_date_run = null;
                this.form.to_date_run = null;

                this.$nextTick(() => {
                    this.form.search_run = this.form.search;
                    this.form.customer_id_run = this.form.customer_id;
                    this.form.from_date_run = this.form.from_date;
                    this.form.to_date_run = this.form.to_date;
                })

                // In the future, add the report filters to the HTTP header
            },
        },

        computed: {
            customers_with_all () {
                return [{id: 'all', name: 'All'}].concat(this.customers);
            },
        },

        watch: {
        }
    }
</script>
