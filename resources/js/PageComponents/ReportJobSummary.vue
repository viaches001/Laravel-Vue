<template>
    <div class="mt-4">
        <div v-if="report_data != null && Object.keys(report_data).length > 0">
            <div class="grid grid-cols-6 gap-x-4 mb-4">
                <div class="col-span-1"></div>
                <div class="col-span-4 text-center">
                    <h3 class="text-2xl font-bold mb-0">Job Summary Report</h3>
                    <p class="mb-0">{{ $luxon(from_date, 'date_med') }} to {{ $luxon(to_date, 'date_med') }}</p>
                    <p class="mb-0" v-if="customer_id != 'all'">{{ customer_def[customer_id] }}</p>
                </div>
                <div class="col-span-1 text-right">
                    <jet-button type="button" shape="circle" class_type="secondary" class="print:hidden" @click.native="print"><printer-icon></printer-icon></jet-button>
                </div>
            </div>

            <div class="px-6 py-4 my-4 border rounded-3xl shadow-sm break-inside-avoid" v-for="(customer_workorders, customer_id) in report_data" :key="customer_id">
                <div class="text-xl font-bold mb-2">{{ customer_def[customer_id] }}</div>

                <div class="px-6 py-4 mt-4 border rounded-3xl shadow-sm break-inside-avoid" v-for="(workorder, workorder_id) in customer_workorders" :key="workorder_id">
                    <div class="text-lg font-bold mb-4">{{ workorder.job_name }}</div>

                    <div class="text-lg font-bold mb-1">Hours</div>
                    <vue-good-table
                        :columns="hours_columns"
                        :rows="[{
                            children: workorder.timesheet_entries
                        }]"
                        :search-options="{
                            enabled: false,
                        }"
                        :sort-options="{
                            enabled: false
                        }"
                        :group-options="{
                            enabled: true,
                            headerPosition: 'bottom',
                        }"
                        @on-row-click="on_timesheet_entry_select"
                        styleClass="vgt-table condensed"
                    >
                    </vue-good-table>

                    <div class="text-lg font-bold mb-1">Materials</div>
                    <vue-good-table
                        :columns="items_columns"
                        :rows="workorder.timesheet_entry_items"
                        :search-options="{
                            enabled: false,
                        }"
                        :sort-options="{
                            enabled: false
                        }"
                        styleClass="vgt-table condensed"
                    >
                    </vue-good-table>
                </div>
            </div>
        </div>

        <div class="text-center text-gray-600" v-else-if="loading">
            - Loading -
        </div>

        <div class="text-center text-gray-600" v-else>
            - No Results -
        </div>
    </div>
</template>

<script>
import { VueGoodTable } from 'vue-good-table';

import JetButton from '@/Jetstream/Button';

export default {
    components: {
        VueGoodTable,
        JetButton,
    },

    props: ['customer_id', 'workorder_id', 'from_date', 'to_date'],

    data () {
        return {
            loading: true,

            customers: [],

            report_data: null,

            hours_columns: [
                {
                    label: 'Date',
                    field: 'date',
                    type: 'text',
                    formatFn: this.date_formatFn,
                },
                {
                    label: 'Employee',
                    field: 'created_by.name',
                    type: 'text',
                },
                {
                    label: 'In',
                    field: 'in_formatted',
                    type: 'text',
                    thClass: 'hidden md:table-cell',
                    tdClass: 'hidden md:table-cell',
                },
                {
                    label: 'Out',
                    field: 'out_formatted',
                    type: 'text',
                    thClass: 'hidden md:table-cell',
                    tdClass: 'hidden md:table-cell',
                },
                {
                    label: 'Lunch',
                    field: 'lunch',
                    type: 'text',
                    formatFn: this.lunch_formatFn,
                },
                {
                    label: 'Hours',
                    field: 'hours',
                    headerField: this.total_hours,
                    type: 'number',
                    formatFn: this.hours_formatFn,
                },
            ],

            items_columns: [
                {
                    label: 'Quantity',
                    field: 'quantity_units',
                    type: 'text',
                },
                {
                    label: 'Part #',
                    field: 'item.part_num',
                    type: 'text',
                },
                {
                    label: 'Description',
                    field: 'item.description',
                    type: 'text',
                },
                {
                    label: 'Extra',
                    field: 'extra',
                    type: 'text',
                    formatFn: this.extra_formatFn,
                },
            ],
        }
    },

    created () {
        window.history.replaceState({}, 'WRC Purifying', this.current_url);

        // Get all customers
        axios.get('/settings/customers/get_all')
        .then(response => {
            this.customers = response.data;
        });

        // Get the report data
        axios.get('/job_summary_report', {
            params: {
                customer_id: this.customer_id,
                workorder_id: this.workorder_id,
                from_date: this.from_date,
                to_date: this.to_date,
            }
        })
        .then(response => {
            this.loading = false;
            this.report_data = response.data;
        });
    },

    computed: {
        current_url () {
            return '/reports?report=job_summary&customer_id=' + this.customer_id + '&workorder_id=' + this.workorder_id + '&from_date=' + this.from_date + '&to_date=' + this.to_date + '&onload=true';
        },

        customer_def () {
            let customer_def = {};
            this.customers.forEach(customer => {
                customer_def[customer.id] = customer.name;
            });

            return customer_def;
        }
    },

    methods: {
        date_formatFn (value) {
            return this.$luxon(value, 'date_med');
        },

        extra_formatFn (value) {
            return value ? 'Yes' : 'No';
        },

        lunch_formatFn (value) {
            if (value == 'yes') return 'Yes';
            else if (value == 'no') return 'No';
            return null;
        },

        hours_formatFn (value) {
            return value == null ? value : value.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        },

        total_hours(rowObj) {
            let sum = 0;
            for (let i = 0; i < rowObj.children.length; i++) {
                sum += Number(rowObj.children[i].hours);
            }
            // Multiply by 4 to round to nearest quarter
            let rounded_sum = Math.round(sum * 4) / 4;
            return rounded_sum.toFixed(2);
        },

        on_timesheet_entry_select (params) {
            this.$inertia.visit(route('timesheets.timesheet_entry_edit', {'timesheet_entry': params.row.id}), {data: { 'timesheet_entry_edit_from': this.current_url }})
        },

        print () {
            window.print();
        },
    }
}
</script>
