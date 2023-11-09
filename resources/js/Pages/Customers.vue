<template>
    <app-layout>
        <template #header>
            <span class="md:hidden">Customers</span>
            <span class="hidden md:inline-block">
                Customers
            </span>
        </template>
        <template #header_buttons>
            <span class="sm:hidden">
                <jet-button type="button" shape="circle" class_type="secondary" @click.native="$inertia.visit(route('customers.customer_add'))"><plus-icon></plus-icon></jet-button>
            </span>
            <span class="hidden sm:inline-block">
                <jet-button type="button" class_type="secondary" @click.native="$inertia.visit(route('customers.customer_add'))">Add Customer</jet-button>
            </span>
        </template>
        <div class="bg-white">
            <alert-message class_type="success" v-if="$page.flash.message">
                {{ $page.flash.message }}
            </alert-message>

            <vue-good-table
                :columns="columns"
                :rows="rows"
                :fixed-header="true"
                :search-options="{
                    enabled: true,
                    placeholder: 'Search Customers...',
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
                @on-row-click="on_customer_select"
                styleClass="vgt-table"
            >
                <template slot="table-row" slot-scope="props">
                    <span v-if="props.column.field == 'status'">
                        <div class="flex justify-between ml-12">
                            <div>
                                {{ customer_status_def[props.formattedRow[props.column.field].name].label }}
                            </div>
                            <div class="flex h-5 w-5 rounded-full" :class="{
                                'bg-gray-800': customer_status_def[props.formattedRow[props.column.field].name].value == customer_status_def.none.value,
                                'bg-blue-500': customer_status_def[props.formattedRow[props.column.field].name].value == customer_status_def.await.value,
                            }"></div>
                        </div>
                    </span>
                    <span v-else>
                        {{ props.formattedRow[props.column.field] }}
                    </span>
                </template>
                <div slot="emptystate" class="text-center text-gray-300">
                    - No Customers Found -
                </div>
            </vue-good-table>
        </div>
    </app-layout>
</template>
<script>
    import AppLayout from '@/Layouts/AppLayout';
    import AlertMessage from '@/Jetstream/AlertMessage';
    import JetButton from '@/Jetstream/Button';

    export default {
        components: {
            AppLayout,
            AlertMessage,
            JetButton,
        },

        props: ['customers', 'customer_status_def', 'errors'],

        data() {
            let customer_status_list = [{value: 'all', label: 'All'}];
            let customer_status_values = Object.keys(this.customer_status_def);
            customer_status_values.map((each) => {
                customer_status_list.push(this.customer_status_def[each]);
            });

            return {
                columns: [
                    {
                        label: 'Name',
                        field: 'name',
                        type: 'text',
                    },
                    {
                        label: 'Email',
                        field: 'email',
                        type: 'text',
                        thClass: 'hidden md:table-cell',
                        tdClass: 'hidden md:table-cell',
                    },
                    {
                        label: 'Phone',
                        field: 'phone',
                        type: 'text',
                        thClass: 'hidden md:table-cell',
                        tdClass: 'hidden md:table-cell',
                    },
                    {
                        label: 'Status',
                        field: 'status',
                        type: 'number',
                        sortable: false,
                        width: '250px',
                        thClass: 'hidden md:table-cell',
                        tdClass: 'hidden md:table-cell',
                    },
                ],
                rows: this.customers,
                customer_status_list: customer_status_list,
            }
        },

        methods: {
            on_customer_select (params) {
                this.$inertia.visit(route('customers.customer_edit', {'customer': params.row.id}))
            }
        }
    }
</script>
