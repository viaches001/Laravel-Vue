<template>
    <app-layout>
        <template #header>
            <span class="md:hidden">User</span>
            <span class="hidden md:inline-block">
                <span v-if="user.id === null"><span class="text-gray-400">Users</span><span class="text-gray-500"> / </span>Add</span>
                <span v-else><span class="text-gray-400">Users</span><span class="text-gray-500"> / </span>{{ user.name }}</span>
            </span>
        </template>
        <template #header_buttons>
            <span class="sm:hidden">
                <jet-button type="button" shape="circle" class_type="primary" @click.native="submit_form()" :disabled="form.processing"><content-save-outline-icon></content-save-outline-icon></jet-button>
                <jet-button type="button" shape="circle" class_type="secondary" @click.native="$inertia.visit(route('users'))" :disabled="form.processing"><close-icon></close-icon></jet-button>
            </span>
            <span class="hidden sm:inline-block">
                <jet-button type="button" class_type="primary" @click.native="submit_form()" :disabled="form.processing">Save</jet-button>
                <jet-button type="button" class_type="secondary" @click.native="$inertia.visit(route('users'))" :disabled="form.processing">Cancel</jet-button>
            </span>
        </template>

        <ValidationObserver ref="observer">
            <form action="#" method="post" @submit.prevent="handle_submit_form" ref="user_form">
                <input type="submit" value="Save" style="display: none;" ref="submit_button" />

                <div class="grid grid-cols-12 gap-4 mb-12">
                    <div class="col-span-5">
                        <div class="font-bold text-xl md:text-2xl lg:text-3xl text-gray-800 leading-none mx-3 mb-4">User Info</div>

                        <div class="mb-2">
                            <jet-label for="type" value="Type" />
                            <jet-select id="customer" v-model="form.type" :options="[{value: 'user', label: 'User'}, {value: 'customer', label: 'Customer'}]" value_field="value" label_field="label" autocomplete="type" />
                        </div>

                        <div class="mb-2" v-show="form.type == 'customer'">
                            <jet-label for="customer_id" value="Customer" />
                            <ValidationProvider name="customer_id" rules="form.type == 'customer' ? 'required': ''" v-slot="{ errors }">
                                <jet-select id="customer" v-model="form.customer_id" :options="customers" value_field="id" label_field="name" :errors="form.error('customer_id') || errors.length > 0" autocomplete="customer_id" />
                                <jet-input-error :message="form.error('customer_id')" v-if="form.error('customer_id')"/>
                                <jet-input-error :message="errors[0]" v-else/>
                            </ValidationProvider>
                        </div>

                        <div class="mb-2">
                            <jet-label for="name" value="Name" />
                            <ValidationProvider name="name" rules="required" v-slot="{ errors }">
                                <jet-input id="name" type="text" v-model="form.name" :errors="form.error('name') || errors.length > 0" autocomplete="name"/>
                                <jet-input-error :message="form.error('name')" v-if="form.error('name')"/>
                                <jet-input-error :message="errors[0]" v-else/>
                            </ValidationProvider>
                        </div>

                        <div class="mb-2">
                            <jet-label for="email" value="Email" />
                            <jet-input id="email" type="text" v-model="form.email" :errors="form.error('email')" autocomplete="email"/>
                            <jet-input-error :message="form.error('email')" v-if="form.error('email')"/>
                        </div>

                        <div class="mb-2">
                            <jet-label for="phone" value="Phone" />
                            <jet-input id="phone" type="text" v-model="form.phone" :errors="form.error('phone')" autocomplete="phone"/>
                            <jet-input-error :message="form.error('phone')" v-if="form.error('phone')"/>
                        </div>
                    </div>

                    <div class="col-span-7">
                        <div class="font-bold text-xl md:text-2xl lg:text-3xl text-gray-800 leading-none mx-3 mb-4">Permissions</div>

                        <div class="md:grid md:gap-4 md:grid-cols-3 justify-items-stretch mb-4" v-for="(permission, index) in form.permissions" :key="index">
                            <div class="col-sapn-6">
                                <jet-label :for="'page_' + index" :index="index" value="Page" />
                                <ValidationProvider :name="'page_' + index" rules="required" v-slot="{ errors }">
                                    <jet-select :id="'page_' + index" type="text" v-model="permission.page" :options="pages" value_field="value" label_field="label" :errors="form.error('page_' + index) || errors.length > 0"/>
                                    <jet-input-error :message="form.error('page_' + index)" v-if="form.error('page_' + index)"/>
                                    <jet-input-error :message="errors[0]" v-else/>
                                </ValidationProvider>
                            </div>
                            <div class="col-auto">
                                <jet-label :for="'access_' + index" :index="index" value="Access" />
                                <ValidationProvider :name="'access_' + index" rules="required" v-slot="{ errors }">
                                    <jet-select :id="'access_' + index" type="text" v-model="permission.access" :options="accesses" value_field="value" label_field="label" :errors="form.error('access_' + index) || errors.length > 0"/>
                                    <jet-input-error :message="form.error('access_' + index)" v-if="form.error('access_' + index)"/>
                                    <jet-input-error :message="errors[0]" v-else/>
                                </ValidationProvider>
                            </div>
                            <div class="justify-self-auto">
                                <jet-button type="button" shape="circle" class_type="danger" :class="index === 0 ? 'mt-6' : 'mt-0 2xl:mt-0'" :disabled="form.permissions.length == 1" @click.native="delete_permission(index)"><trash-can-outline-icon></trash-can-outline-icon></jet-button>
                            </div>
                        </div>

                        <div class="text-left">
                            <jet-button type="button" shape="circle" class_type="secondary" @click.native="add_permission()"><plus-icon></plus-icon></jet-button>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="font-bold text-xl md:text-2xl lg:text-3xl text-gray-800 leading-none mx-3 mb-4">Login</div>

                    <div class="mb-2">
                        <jet-label for="role" value="Role" />
                        <div class="md:flex gap-4">
                            <div class="md:flex-grow mb-2 md:mb-0">
                                <ValidationProvider name="role" :rules="{'required': true}" v-slot="{ errors }">
                                    <jet-select id="role" type="text" v-model="form.role" :options="[{value: 'admin', label: 'Admin'}, {value: 'installer', label: 'Installer'}]" value_field="value" label_field="label"  :errors="form.error('role') || errors.length > 0" autocomplete="role"/>
                                    <jet-input-error :message="form.error('role')" v-if="form.error('role')"/>
                                    <jet-input-error :message="errors[0]" v-else/>
                                </ValidationProvider>
                            </div>

                            <div class="md:flex-shrink mb-2 md:mb-0" v-if="form.role == 'installer'">
                                <jet-checkbox @click.native="form.service_calls = !form.service_calls" :value="form.service_calls">Can Manage Service Calls</jet-checkbox>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <jet-label for="username" value="Username" />
                        <ValidationProvider name="username" :rules="{'required': true}" v-slot="{ errors }">
                            <jet-input id="username" type="text" v-model="form.username" :errors="form.error('username') || errors.length > 0" autocomplete="username"/>
                            <jet-input-error :message="form.error('username')" v-if="form.error('username')"/>
                            <jet-input-error :message="errors[0]" v-else/>
                        </ValidationProvider>
                    </div>

                    <div v-if="user.id === null">
                        <div class="mb-2">
                            <jet-label for="password" value="New Password" />
                            <ValidationProvider name="password" rules="required" v-slot="{ errors }">
                                <jet-input id="password" type="password" v-model="form.password" :errors="form.error('password') || errors.length > 0" autocomplete="none"/>
                                <jet-input-error :message="form.error('password')" v-if="form.error('password')"/>
                                <jet-input-error :message="errors[0]" v-else/>
                            </ValidationProvider>
                        </div>

                        <div class="mb-2">
                            <jet-label for="password_confirmation" value="Confirm Password" />
                            <ValidationProvider name="password_confirmation" rules="required" v-slot="{ errors }">
                                <jet-input id="password_confirmation" type="password" v-model="form.password_confirmation" :errors="form.error('password_confirmation') || errors.length > 0" autocomplete="none"/>
                                <jet-input-error :message="form.error('password')" v-if="form.error('password')"/>
                                <jet-input-error :message="errors[0]" v-else/>
                            </ValidationProvider>
                        </div>
                    </div>

                    <div v-else>
                        <div class="mb-2">
                            <jet-label for="password" value="New Password" />
                                <jet-input id="password" type="password" placeholder="Leave blank for no change..." v-model="form.password" :errors="form.error('password')" autocomplete="none"/>
                                <jet-input-error :message="form.error('password')" v-if="form.error('password')"/>
                        </div>
                        <div class="mb-2" v-if="form.password !== null && form.password !== ''">
                            <jet-label for="password_confirmation" value="Confirm Password" />
                            <ValidationProvider name="password_confirmation" :rules="{'required': form.password != null && form.password != ''}" v-slot="{ errors }">
                                <jet-input id="password_confirmation" type="password" placeholder="Confirm password..." v-model="form.password_confirmation" :errors="form.error('password_confirmation') || errors.length > 0" autocomplete="none"/>
                                <jet-input-error :message="form.error('password')" v-if="form.error('password')"/>
                                <jet-input-error :message="errors[0]" v-else/>
                            </ValidationProvider>
                        </div>
                    </div>
                </div>

                <div class="text-right mt-5" v-if="user.id != null">
                    <jet-button type="button" class_type="danger" @click.native="confirming_delete = true" :disabled="form.processing">Delete User</jet-button>
                </div>

                <!-- Modals -->
                <jet-confirm-modal :show="confirming_delete">
                    <template #title>
                        Delete User?
                    </template>
                    <template #content>
                        This will delete this record forever. Are you sure?
                    </template>
                    <template #footer>
                        <jet-button type="button" class_type="primary" @click.native="delete_user();">Delete</jet-button>
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
import JetCheckbox from '@/Jetstream/Checkbox';
import JetInputError from '@/Jetstream/InputError';
import JetConfirmModal from '@/Jetstream/ConfirmModal';

import { ValidationObserver, ValidationProvider } from 'vee-validate';

const empty_permission = {
    'id': null,
    'user_id': null,
    'page': null,
    'access': null,
};

export default {
    components: {
        AppLayout,
        JetButton,
        JetLabel,
        JetInput,
        JetSelect,
        JetCheckbox,
        JetInputError,
        JetConfirmModal,

        ValidationObserver,
        ValidationProvider
    },

    props: ['user', 'customers', 'pages', 'accesses'],

    data () {
        return {
            loading: true,

            confirming_delete: false,

            form: this.$inertia.form({
                type: this.user.customer_id ? 'Customer' : 'User',
                customer_id: this.user.customer_id,
                name: this.user.name,
                email: this.user.email,
                phone: this.user.phone,
                username: this.user.username,
                password: null,
                password_confirmation: null,
                role: this.user.role,
                service_calls: this.user.service_calls,

                permissions: this.user.permissions_,
            }),
        }
    },

    created () {
        // If no permissions, add an empty one
        if (this.form.permissions.length == 0) this.add_permission();
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
                if (result) {
                    if (this.user.id === null) this.form.post(route('user_add_store'));
                    else this.form.post(route('user_edit_store', {'user': this.user.id}));
                } else {
                    this.scroll_to_first_error();
                }
            });
        },

        delete_user () {
            this.$inertia.visit(route('user_delete', {'user': this.user.id, }));
        },

        add_permission () {
            let new_permission = JSON.parse(JSON.stringify(empty_permission));
            this.form.permissions.push(new_permission);
        },

        delete_permission (index) {
            this.form.permissions.splice(index, 1);
        },
    }
}
</script>
