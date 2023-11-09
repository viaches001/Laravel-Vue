<template>
    <app-layout>
        <template #header>
            <span class="md:hidden">Readings</span>
            <span class="hidden md:inline-block">
                Readings
            </span>
        </template>
        <template #header_buttons>
            <span class="sm:hidden">
                <jet-button type="button" shape="circle" class_type="secondary" @click.native="$inertia.visit(route('readings.reading_add'))"><plus-icon></plus-icon></jet-button>
            </span>
            <span class="hidden sm:inline-block">
                <jet-button type="button" class_type="secondary" @click.native="$inertia.visit(route('readings.reading_add'))">Add Reading</jet-button>
            </span>
        </template>
        <div class="bg-white">
            <alert-message class_type="success" v-if="$page.flash.message">
                {{ $page.flash.message }}
            </alert-message>

            <div class="grid grid-cols-4 gap-4 justify-items-stretch">
                <div class="mb-2">
                    <jet-input type="text" v-model="search.term" placeholder="Search..." />
                </div>
                <div class="mb-2">
                </div>
                <div class="mb-2">
                    <jet-select v-model="search.status" :options="customer_status_list" value_field="value" label_field="label" />
                </div>
                <div class="justify-self-end mb-2">
                    <jet-button type="button" class_type="secondary">{{ "Switch to Approval Mode" }}</jet-button>
                </div>
            </div>
            <vue-good-table
                :columns="columns"
                :rows="rows"
                :fixed-header="true"
                :search-options="{
                    enabled: true,
                    // placeholder: 'Search...',
                    externalQuery: search.term,
                }"
                :sort-options="{
                    enabled: true,
                    initialSortBy: {
                        field: 'name',
                        type: 'asc'
                    }
                }"
                :pagination-options="{
                    enabled: true,
                    mode: 'records',
                    dropdownAllowAll: false,
                    rowsPerPageLabel: 'Customers per page',
                }"
                @on-row-click="on_item_select"
                styleClass="vgt-table"
            >
                <!-- <template slot="table-row" slot-scope="props">
                    <span v-if="props.column.field == 'status'">
                        <div class="flex justify-between ml-12">
                            <div>
                                {{ customer_status_def[props.column.field].label }}
                            </div>
                            <div class="flex h-5 w-5 rounded-full" :class="{
                                'bg-gray-800': customer_status_def[props.column.field].value == customer_status_def.none.value,
                                'bg-blue-500': customer_status_def[props.column.field].value == customer_status_def.await.value,
                            }"></div>
                        </div>
                    </span>
                    <span v-else>
                        {{ props.formattedRow[props.column.field] }}
                    </span>
                </template> -->
                <div slot="emptystate" class="text-center text-gray-300">
                    - No Readings Found -
                </div>
            </vue-good-table>
        </div>
    </app-layout>
</template>
<script>
    import AppLayout from '@/Layouts/AppLayout';
    import AlertMessage from '@/Jetstream/AlertMessage';
    import JetButton from '@/Jetstream/Button';
    import JetInput from '@/Jetstream/Input';
    import JetSelect from '@/Jetstream/Select';

    export default {
        components: {
            AppLayout,
            AlertMessage,
            JetButton,
            JetInput,
            JetSelect,
        },

        props: ['customers', 'readings', 'customer_status_def', 'errors'],

        data() {
            let customer_status_list = [{value: 'all', label: 'All'}];
            let customer_status_names = Object.keys(this.customer_status_def);
            customer_status_names.map((customer_status_name) => {
                customer_status_list.push(this.customer_status_def[customer_status_name]);
            });

            return {
                columns: [
                    {
                        label: 'Customer',
                        field: 'name',
                        type: 'text',
                    },
                    {
                        label: 'Notified',
                        field: 'notified_at',
                        type: 'date',
                        dateInputFormat: 'yyyy-MM-dd',
                        dateOutputFormat: 'MMM do, yyyy',
                    },
                    {
                        label: 'Submitted',
                        field: 'submitted_at',
                        type: 'date',
                        dateInputFormat: 'yyyy-MM-dd',
                        dateOutputFormat: 'MMM do, yyyy',
                        thClass: 'hidden md:table-cell',
                        tdClass: 'hidden md:table-cell',
                    },
                    {
                        label: 'Verified',
                        field: 'verified_at',
                        type: 'date',
                        dateInputFormat: 'yyyy-MM-dd',
                        dateOutputFormat: 'MMM do, yyyy',
                        thClass: 'hidden md:table-cell',
                        tdClass: 'hidden md:table-cell',
                    },
                    {
                        label: 'Status',
                        field: 'status',
                        type: 'number',
                        width: '250px',
                        thClass: 'hidden md:table-cell',
                        tdClass: 'hidden md:table-cell',
                    },
                ],
                rows: this.customers.map((customer) => {
                    return {
                        ...customer,
                        status: customer.status.name,
                        notified_at: customer.status.notified_at,
                        submitted_at: customer.status.submitted_at,
                        verified_at: customer.status.verified_at,
                    }
                }),
                search: {
                    term: '',
                    status: 'all'
                },
                customer_status_list: customer_status_list,
            }
        },

        methods: {
            on_item_select (params) {
                this.$inertia.visit(route('readings.reading_edit', {'customer': params.row.id}))
            }
        }
    }
</script>
