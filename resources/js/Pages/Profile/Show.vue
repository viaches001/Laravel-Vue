<template>
    <app-layout>
        <template #header>
            Profile
        </template>
        <template #header_buttons>
            <span class="sm:hidden">
                <jet-button type="button" shape="circle" class_type="primary" @click.native="submit_form()" :disabled="form.processing"><content-save-outline-icon></content-save-outline-icon></jet-button>
            </span>
            <span class="hidden sm:inline-block">
                <jet-button type="button" class_type="primary" @click.native="submit_form()" :disabled="form.processing">Save</jet-button>
            </span>
        </template>
        
        <alert-message class_type="success" class="mt-0" v-if="$page.flash.message">
            {{ $page.flash.message }}
        </alert-message>

        <ValidationObserver>
            <form action="#" method="post" @submit.prevent="handle_submit_form" ref="user_form">
                <input type="submit" value="Save" style="display: none;" ref="submit_button" />

                <div class="mb-12">
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
                        <ValidationProvider name="email" :rules="{'required': true, 'email': true}" v-slot="{ errors }">
                            <jet-input id="email" type="text" v-model="form.email" :errors="form.error('email') || errors.length > 0" autocomplete="email"/>
                            <jet-input-error :message="form.error('email')" v-if="form.error('email')"/>
                            <jet-input-error :message="errors[0]" v-else/>
                        </ValidationProvider>
                    </div>

                    <div class="mb-2">
                        <jet-label for="phone" value="Phone" />
                        <jet-input id="phone" type="text" v-model="form.phone" :errors="form.error('phone')" autocomplete="phone"/>
                        <jet-input-error :message="form.error('phone')" v-if="form.error('phone')"/>
                    </div>
                </div>

                <div>
                    <div class="font-bold text-xl md:text-2xl lg:text-3xl text-gray-800 leading-none mx-6 mb-4">Login</div>

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
                                <jet-input-error :message="form.error('password_confirmation')" v-if="form.error('password_confirmation')"/>
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
                            <jet-input id="password_confirmation" type="password" placeholder="Confirm password..." v-model="form.password_confirmation" :errors="form.error('password_confirmation')" autocomplete="none"/>
                            <jet-input-error :message="form.error('password_confirmation')" v-if="form.error('password_confirmation')"/>
                        </div>
                    </div>
                </div>
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
import AlertMessage from '@/Jetstream/AlertMessage';
import { ValidationObserver, ValidationProvider } from 'vee-validate';

export default {
    components: {
        AppLayout,
        JetButton,
        JetLabel,
        JetInput,
        JetSelect,
        JetInputError,
        AlertMessage,
        ValidationObserver,
        ValidationProvider
    },

    props: ['user'],

    data () {
        return {
            loading: true,

            form: this.$inertia.form({
                profile_update: true,
                name: this.user.name,
                email: this.user.email,
                phone: this.user.phone,
                username: this.user.username,
                password: null,
                password_confirmation: null,
                role: this.user.role,
            }, {
                resetOnSuccess: false,
            })
        }
    },

    methods: {
        submit_form () {
            this.$refs.submit_button.click();
        },

        handle_submit_form () {
            this.form.post(route('settings.user_edit_store', {'user': this.user.id}), {
                onFinish: () => {
                    this.form.password = null;
                    this.form.password_confirmation = null;
                }
            });

        },
    }
}
</script>