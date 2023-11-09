<template>
    <div class="mt-4">
        <div v-if="report_data != null && Object.keys(report_data).length > 0">
            <div class="grid grid-cols-6 gap-x-4 mb-4">
                <div class="col-span-1"></div>
                <div class="col-span-4 text-center">
                    <h3 class="text-2xl font-bold mb-0">Payroll Report</h3>
                    <p class="mb-0">{{ $luxon(from_date, 'date_med') }} to {{ $luxon(to_date, 'date_med') }}</p>
                    <p class="mb-0" v-if="user_id != 'all'">{{ user_def[user_id] }}</p>
                </div>
                <div class="col-span-1 text-right">
                    <jet-button type="button" shape="circle" class_type="secondary" class="print:hidden" @click.native="print"><printer-icon></printer-icon></jet-button>
                </div>
            </div>

            <div class="px-6 py-4 my-4 border rounded-3xl shadow-sm break-inside-avoid" v-for="(employee_timesheet_entries, employee_id) in report_data" :key="employee_id">
                <div class="text-xl font-bold mb-2">{{ user_def[employee_id] }}</div>

                <vue-good-table
                    :columns="columns"
                    :rows="[{
                        children: employee_timesheet_entries
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
                <template slot="table-row" slot-scope="props">
                    <span v-if="props.column.field == 'late_clock_out'">
                        <div v-if="props.row.late_clock_out" class="flex items-center justify-center h-6 w-6 rounded-full bg-red-100" :title="'Late clock out on ' + props.row.late_clock_out">
                            <alert-circle-outline-icon class="text-red-400" :size="20"  :title="'Late clock out on ' + props.row.late_clock_out"></alert-circle-outline-icon>
                        </div>
                    </span>
                </template>
                </vue-good-table>
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

    props: ['user_id', 'from_date', 'to_date'],

    data () {
        return {
            loading: true,

            users: [],

            report_data: null,

            columns: [
                {
                    label: '',
                    field: 'late_clock_out',
                    type: 'text',
                    thClass: 'hidden md:table-cell',
                    tdClass: 'hidden md:table-cell',
                    width: '1px',
                },
                {
                    label: 'Date',
                    field: 'date',
                    type: 'text',
                    formatFn: this.date_formatFn,
                },
                {
                    label: 'Job',
                    field: 'workorder.job_name',
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
        }
    },

    created () {
        window.history.replaceState({}, 'WRC Purifying', this.current_url);

        // Get all users
        axios.get('/settings/users/get_all')
        .then(response => {
            this.users = response.data;
        });

        // Get the report data
        axios.get('/payroll_report', {
            params: {
                user_id: this.user_id,
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
            return '/reports?report=payroll&user_id=' + this.user_id + '&from_date=' + this.from_date + '&to_date=' + this.to_date + '&onload=true';
        },

        user_def () {
            let user_def = {};
            this.users.forEach(user => {
                user_def[user.id] = user.name;
            });

            return user_def;
        }
    },

    methods: {
        date_formatFn (value) {
            return this.$luxon(value, 'date_med');
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
