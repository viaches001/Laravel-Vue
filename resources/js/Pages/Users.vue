<template>
    <app-layout>
        <template #header>
            <span class="md:hidden">Users</span>
            <span class="hidden md:inline-block">
                Users
            </span>
        </template>
        <template #header_buttons>
            <span class="sm:hidden">
                <jet-button type="button" shape="circle" class_type="secondary" @click.native="$inertia.visit(route('user_add'))"><plus-icon></plus-icon></jet-button>
            </span>
            <span class="hidden sm:inline-block">
                <jet-button type="button" class_type="secondary" @click.native="$inertia.visit(route('user_add'))">Add User</jet-button>
            </span>
        </template>
        <div class="bg-white">
            <alert-message class_type="success" v-if="$page.flash.message">
                {{ $page.flash.message }}
            </alert-message>

            <vue-good-table
                :columns="columns"
                :rows="customized_rows"
                :fixed-header="true"
                :search-options="{
                    enabled: true,
                    placeholder: 'Search Users...',
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
                    rowsPerPageLabel: 'Users per page',
                }"
                @on-row-click="on_user_select"
                styleClass="vgt-table"
            >
                <div slot="emptystate" class="text-center text-gray-300">
                    - No Users Found -
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

        props: ['users', 'errors'],

        data() {
            return {
                columns: [
                    {
                        label: 'Type',
                        field: 'type',
                        type: 'text',
                    },
                    {
                        label: 'Name',
                        field: 'name',
                        type: 'text',
                    },
                    {
                        label: 'Username',
                        field: 'username',
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
                ],
                rows: this.users
            }
        },

        computed: {
            customized_rows: function () {
                let customized_rows = [];
                this.rows.forEach((each) => {
                    customized_rows.push({
                        ... each,
                        type: each.customer_id ? "Customer" : "User",
                    })
                })
                return customized_rows;
            }
        },

        methods: {
            on_user_select (params) {
                this.$inertia.visit(route('user_edit', {'user': params.row.id}))
            }
        }
    }
</script>
