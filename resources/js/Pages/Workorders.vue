<template>
    <app-layout>
        <template #header>
            Workorders
        </template>
        <template #header_buttons>
            <span class="sm:hidden">
                <jet-button type="button" shape="circle" class_type="secondary" @click.native="$inertia.visit(route('workorders.workorder_add'))"><plus-icon></plus-icon></jet-button>
            </span>
            <span class="hidden sm:inline-block">
                <jet-button type="button" class_type="secondary" @click.native="$inertia.visit(route('workorders.workorder_add'))">Add Workorder</jet-button>
            </span>
        </template>
        <template #filters>
            <div class="mb-2">
                <jet-label for="workoder_filter_search" value="Search" />
                <jet-input type="text" id="workoder_filter_search" v-model="workoder_filter_search" placeholder="Search Workorders..." />
            </div>
            <div class="flex gap-x-4 mb-2">
                <div class="flex-shrink" v-show="workorder_filter_date_type=='custom'">
                    <jet-label for="workoder_filter_date_from" value="From" />
                    <jet-date-picker id="workoder_filter_date_from" v-model="workoder_filter_date_from" />
                </div>

                <div class="flex-shrink" v-show="workorder_filter_date_type=='custom'">
                    <jet-label for="workoder_filter_date_to" value="From" />
                    <jet-date-picker id="workoder_filter_date_to" v-model="workoder_filter_date_to" />
                </div>
            </div>
            <div class="mb-2">
                <jet-label for="workorder_filter_status" value="Status" />
                <jet-select id="workorder_filter_status" v-model="workorder_filter_status" :options="[{value: 'all', label: 'All'}, {value: 'incomplete', label: 'Incomplete'}, {value: 'completed', label: 'Completed'}]" value_field="value" label_field="label" :clearable="false"/>
            </div>
        </template>
        <div class="bg-white">
            <alert-message class_type="success" v-if="$page.flash.message">
                {{ $page.flash.message }}
            </alert-message>

            <div class="hidden md:block md:grid md:grid-cols-3 md:gap-4">
                <div class="mb-2">
                    <jet-label for="workoder_filter_search" value="Search" />
                    <jet-input type="text" id="workoder_filter_search" v-model="workoder_filter_search" placeholder="Search Workorders..." />
                </div>
                <div class="flex gap-x-4 mb-2">
                    <div class="flex-shrink" v-show="workorder_filter_period=='custom'">
                        <jet-label for="workorder_filter_from_date" value="From" />
                        <jet-date-picker id="workorder_filter_from_date" v-model="workorder_filter_from_date" />
                    </div>

                    <div class="flex-shrink" v-show="workorder_filter_period=='custom'">
                        <jet-label for="workorder_filter_to_date" value="To" />
                        <jet-date-picker id="workorder_filter_to_date" v-model="workorder_filter_to_date" />
                    </div>
                </div>
                <div class="mb-2">
                    <jet-label for="workorder_filter_status" value="Status" />
                    <jet-select id="workorder_filter_status" v-model="workorder_filter_status" :options="[{value: 'all', label: 'All'}, {value: 'incomplete', label: 'Incomplete'}, {value: 'completed', label: 'Completed'}]" value_field="value" label_field="label" :clearable="false" autocomplete="workorder_filter_status"/>
                </div>
            </div>

            <vue-good-table
                :columns="columns"
                :rows="rows"
                :fixed-header="true"
                :search-options="{
                    enabled: true,
                    // placeholder: 'Search Workorders...',
                    externalQuery: workoder_filter_search,
                }"
                :sort-options="{
                    enabled: true,
                    initialSortBy: {
                        field: 'customer.name',
                        type: 'asc'
                    }
                }"
                :pagination-options="{
                    enabled: true,
                    mode: 'records',
                    dropdownAllowAll: false,
                    rowsPerPageLabel: 'Workorders per page',
                }"
                @on-row-click="on_workorder_select"
                styleClass="vgt-table"
            >
                <div slot="emptystate" class="text-center text-gray-300">
                    - No Workorders Found -
                </div>
            </vue-good-table>
        </div>
    </app-layout>
</template>
<script>
    import AppLayout from '@/Layouts/AppLayout';
    import AlertMessage from '@/Jetstream/AlertMessage';
    import JetButton from '@/Jetstream/Button';
    import JetLabel from '@/Jetstream/Label';
    import JetSelect from '@/Jetstream/Select';
    import JetInput from '@/Jetstream/Input';
    import JetDatePicker from '@/Jetstream/DatePicker';

    export default {
        components: {
            AppLayout,
            AlertMessage,
            JetButton,
            JetSelect,
            JetLabel,
            JetInput,
            JetDatePicker,
        },

        props: ['workorders', 'period', 'from_date', 'to_date', 'status', 'errors'],

        data() {
            return {
                workoder_filter_search: '',
                workorder_filter_period: this.period,
                workorder_filter_from_date: this.from_date,
                workorder_filter_to_date: this.to_date,
                workorder_filter_status: this.status,

                workorder_status_def: {
                    0: 'Draft',
                    1: 'Finalized',
                    2: 'Completed',
                },

                columns: [
                    {
                        label: 'Customer',
                        field: 'customer.name',
                        type: 'text',
                        thClass: 'hidden md:table-cell',
                        tdClass: 'hidden md:table-cell',
                    },
                    {
                        label: 'Date',
                        field: 'date',
                        type: 'date',
                        dateInputFormat: 'yyyy-MM-dd',
                        dateOutputFormat: 'MMM do, yyyy',
                        thClass: 'hidden md:table-cell md:text-center',
                        tdClass: 'hidden md:table-cell md:text-center',
                    },
                    {
                        label: 'Description',
                        field: 'description',
                        type: 'text',
                        thClass: 'hidden md:table-cell',
                        tdClass: 'hidden md:table-cell',
                    },
                    {
                        label: 'Status',
                        field: 'status',
                        type: 'text',
                        formatFn: this.status_formatFn,
                    },
                ],
                rows: this.workorders
            }
        },

        methods: {
            status_formatFn (value) {
                return this.workorder_status_def[value];
            },

            on_workorder_select (params) {
                this.$inertia.visit(route('workorders.workorder_edit', {'workorder': params.row.id}))
            },

            reload_page () {
                this.$inertia.visit(route('workorders.workorders') + '?workorder_filter_status=' + this.workorder_filter_status + '&workorder_filter_job_type_id=' + this.workorder_filter_job_type_id)
            },
        },

        computed: {
            job_types_with_all () {
                return [{id: 'all', type: 'All'}].concat(this.job_types);
            }
        },

        watch: {
            workorder_filter_status () {
                this.reload_page();
            },

            workorder_filter_job_type_id () {
                this.reload_page();
            }
        }
    }
</script>
