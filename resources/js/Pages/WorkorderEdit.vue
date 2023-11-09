<template>
    <app-layout>
        <template #header>
            <span class="md:hidden">Workorder</span>
            <span class="hidden md:inline-block">
                <span v-if="workorder.id === null"><span class="text-gray-400">Workorders</span><span class="text-gray-500"> / </span>Add</span>
                <span v-else><span class="text-gray-400">Workorders</span><span class="text-gray-500"> / </span>Edit</span>
            </span>

            <div class="text-sm text-gray-600 font-bold pl-1 md:pl-2">
                {{ workorder_status_def[workorder.status] }}
            </div>
        </template>
        <template #header_buttons>
            <span class="sm:hidden">
                <jet-button type="button" shape="circle" class_type="primary" @click.native="submit_form()" :disabled="form.processing"><content-save-outline-icon></content-save-outline-icon></jet-button>
                <jet-button type="button" shape="circle" class_type="secondary" @click.native="$inertia.visit(route('workorders.workorders'))" :disabled="form.processing"><close-icon></close-icon></jet-button>
            </span>
            <span class="hidden sm:inline-block">
                <jet-button type="button" class_type="primary" @click.native="submit_form()" :disabled="form.processing">Save</jet-button>
                <jet-button type="button" class_type="secondary" @click.native="$inertia.visit(route('workorders.workorders'))" :disabled="form.processing">Cancel</jet-button>
            </span>
        </template>
        <template #footer_buttons_right>
            <jet-button v-if="workorder.status !== 2" shape="circle" class_type="success" class="p-4" @click.native="confirm_next_status()" :disabled="form.processing"><check-icon></check-icon></jet-button>
        </template>

        <alert-message class_type="success" class="mt-0" v-if="$page.flash.message">
            {{ $page.flash.message }}
        </alert-message>

        <ValidationObserver ref="observer">
            <form action="#" method="post" @submit.prevent="handle_submit_form" ref="workorder_form">
                <input type="submit" value="Save" style="display: none;" ref="submit_button" />

                <div class="mb-12">
                    <div class="md:grid md:gap-4 md:grid-cols-2">
                        <div class="mb-2">
                            <jet-label for="customer_id" value="Customer" />
                            <ValidationProvider name="customer_id" rules="required" v-slot="{ errors }">
                                <jet-select id="customer_id" v-model="form.customer_id" :options="customers" value_field="id" label_field="name" :errors="form.error('customer_id') || errors.length > 0" autocomplete="customer_id"/>
                                <jet-input-error :message="form.error('customer_id')" v-if="form.error('customer_id')"/>
                                <jet-input-error :message="errors[0]" v-else/>
                            </ValidationProvider>
                        </div>

                        <div class="mb-2">
                            <jet-label for="job_type_id" value="Job Type" />
                            <jet-select id="job_type_id" v-model="form.job_type_id" :options="job_types" value_field="id" label_field="type" :errors="form.error('job_type_id')" autocomplete="job_type_id"/>
                            <jet-input-error :message="form.error('job_type_id')" v-if="form.error('job_type_id')"/>
                        </div>
                    </div>

                    <div class="md:grid md:gap-4 md:grid-cols-2">
                        <div class="mb-2">
                            <jet-label for="job_name" value="Job Name" />
                            <ValidationProvider name="job_name" rules="required" v-slot="{ errors }">
                                <jet-input id="job_name" type="text" v-model="form.job_name" :errors="form.error('job_name') || errors.length > 0" autocomplete="job_name"/>
                                <jet-input-error :message="form.error('job_name')" v-if="form.error('job_name')"/>
                                <jet-input-error :message="errors[0]" v-else/>
                            </ValidationProvider>
                        </div>

                        <div class="mb-2">
                            <jet-label for="job_address" value="Job Address" />
                            <div class="flex gap-x-4">
                                <div class="flex-grow">
                                    <jet-input id="job_address" type="text" v-model="form.job_address" :errors="form.error('job_address')" autocomplete="job_address"/>
                                    <jet-input-error :message="form.error('job_address')" v-if="form.error('job_address')"/>
                                </div>
                                <div class="flex-shrink">
                                    <jet-button type="button" shape="circle" class_type="secondary" @click.native="google_maps()"><google-maps-icon></google-maps-icon></jet-button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="md:grid md:gap-4 md:grid-cols-2">
                        <div class="mb-2">
                            <jet-label for="quoted_by" value="Quoted By" />
                            <ValidationProvider name="quoted_by" rules="required" v-slot="{ errors }">
                                <jet-radio-group>
                                    <jet-radio @click.native="form.quoted_by = (form.quoted_by == 'quote' ? null : 'quote')" :value="form.quoted_by" checked_value="quote" :errors="form.error('quoted_by') || errors.length > 0">Quote</jet-radio>
                                    <jet-radio @click.native="form.quoted_by = (form.quoted_by == 'time_and_materials' ? null : 'time_and_materials')" :value="form.quoted_by" checked_value="time_and_materials" :errors="form.error('quoted_by') || errors.length > 0">Time &amp; Materials</jet-radio>
                                </jet-radio-group>
                                <jet-input-error :message="form.error('quoted_by')" v-if="form.error('quoted_by')"/>
                                <jet-input-error :message="errors[0]" v-else/>
                            </ValidationProvider>
                        </div>

                        <div class="mb-2">
                            <jet-label for="po" value="PO #" />
                            <jet-input id="po" type="text" v-model="form.po" :errors="form.error('po')" autocomplete="po"/>
                            <jet-input-error :message="form.error('po')" v-if="form.error('po')"/>
                        </div>
                    </div>

                    <div class="mb-2">
                        <jet-label for="order_date" value="Order Date" />
                        <jet-input id="order_date" type="date" v-model="form.order_date" :errors="form.error('order_date')" autocomplete="order_date"/>
                        <jet-input-error :message="form.error('order_date')" v-if="form.error('order_date')"/>
                    </div>

                    <div class="md:grid md:gap-4 md:grid-cols-2">
                        <div class="mb-2">
                            <jet-label for="job_notes" value="Job Notes" />
                            <jet-textarea id="job_notes" v-model="form.job_notes" :errors="form.error('job_notes')" autocomplete="job_notes"/>
                            <jet-input-error :message="form.error('job_notes')" v-if="form.error('job_notes')"/>
                        </div>

                        <div class="mb-2">
                            <jet-label for="admin_notes" value="Admin Notes" />
                            <jet-textarea id="admin_notes" v-model="form.admin_notes" :errors="form.error('admin_notes')" autocomplete="admin_notes"/>
                            <jet-input-error :message="form.error('admin_notes')" v-if="form.error('admin_notes')"/>
                        </div>
                    </div>
                </div>

                <jet-form-section :collapsible="true" v-if="workorder.id !== null && workorder.timesheet_entries.length > 0">
                    <template slot="title">Job History</template>

                    <template slot="content">
                        <timesheet-entry-history :object="workorder"></timesheet-entry-history>
                    </template>
                </jet-form-section>

                <jet-form-section>
                    <template slot="title">Required Materials</template>

                    <template slot="content">
                        <job-type-required-items v-if="job_type != null && job_type.items.length > 0" :object="job_type" :items="items" :view_only="true"></job-type-required-items>
                        <workorder-items :object="form" :items="items" :hide_labels="job_type != null && job_type.items.length > 0"></workorder-items>
                    </template>
                </jet-form-section>

                <jet-form-section>
                    <template slot="title">Documents</template>

                    <template slot="content">
                        <workorder-documents :object="form"></workorder-documents>
                    </template>
                </jet-form-section>

                <jet-form-section>
                    <template slot="title">Images &amp; Videos</template>

                    <template slot="content">
                        <workorder-images :object="form"></workorder-images>
                    </template>
                </jet-form-section>

                <div class="text-right my-6" v-if="workorder.id != null">
                    <jet-button type="button" class_type="danger" @click.native="confirming_delete = true" :disabled="form.processing">Delete Workorder</jet-button>
                </div>

                <div v-if="workorder.status !== 2" class="hidden lg:block sticky bottom-0 text-right pb-6 mt-6">
                    <jet-button type="button" class_type="success" @click.native="confirm_next_status()" :disabled="form.processing"><check-icon></check-icon> {{ workorder_next_status_def[workorder.status] }} Workorder</jet-button>
                </div>

                <!-- Modals -->
                <jet-confirm-modal :show="confirming_delete">
                    <template #title>
                        Delete Workorder?
                    </template>
                    <template #content>
                        This will delete this record forever. Are you sure?
                    </template>
                    <template #footer>
                        <jet-button type="button" class_type="primary" @click.native="delete_workorder();">Delete</jet-button>
                        <jet-button type="button" class_type="secondary" @click.native="confirming_delete = false">Cancel</jet-button>
                    </template>
                </jet-confirm-modal>

                <jet-confirm-modal :show="confirming_next_status">
                    <template #title>
                        {{ workorder_next_status_def[workorder.status] }} Workorder?
                    </template>
                    <template #content>
                        <div v-if="workorder.status == 0">
                            After finalizing, this workorder will be included in the installer job list.
                        </div>
                        <div v-if="workorder.status == 1">
                            After completing, this workorder will be removed from the workorder list and installer job list.
                        </div>
                    </template>
                    <template #footer>
                        <jet-button type="button" class_type="primary" @click.native="next_status();" :disabled="form.processing">Continue</jet-button>
                        <jet-button type="button" class_type="secondary" @click.native="confirming_next_status = false" :disabled="form.processing">Cancel</jet-button>
                    </template>
                </jet-confirm-modal>
            </form>
        </ValidationObserver>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout';
import AlertMessage from '@/Jetstream/AlertMessage';
import JetButton from '@/Jetstream/Button';
import JetFormSection from '@/Jetstream/FormSection';
import JetLabel from '@/Jetstream/Label';
import JetInput from '@/Jetstream/Input';
import JetRadioGroup from '@/Jetstream/RadioGroup';
import JetRadio from '@/Jetstream/Radio';
import JetSelect from '@/Jetstream/Select';
import JetTextarea from '@/Jetstream/Textarea';
import JetInputError from '@/Jetstream/InputError';
import JetImageModal from '@/Jetstream/ImageModal';
import JetConfirmModal from '@/Jetstream/ConfirmModal';

import TimesheetEntryHistory from '@/PageComponents/TimesheetEntryHistory';
import JobTypeRequiredItems from '@/PageComponents/JobTypeRequiredItems';
import WorkorderDocuments from '@/PageComponents/WorkorderDocuments';
import WorkorderItems from '@/PageComponents/WorkorderItems';
import WorkorderImages from '@/PageComponents/WorkorderImages';

import { ValidationObserver, ValidationProvider } from 'vee-validate';

export default {
    components: {
        AppLayout,
        AlertMessage,
        JetButton,
        JetFormSection,
        JetLabel,
        JetInput,
        JetRadioGroup,
        JetRadio,
        JetSelect,
        JetTextarea,
        JetInputError,
        JetImageModal,
        JetConfirmModal,

        TimesheetEntryHistory,
        JobTypeRequiredItems,
        WorkorderDocuments,
        WorkorderItems,
        WorkorderImages,

        ValidationObserver,
        ValidationProvider
    },

    props: ['workorder', 'customers', 'items', 'job_types'],

    data () {
        return {
            loading: true,

            workorder_status_def: {
                0: 'Draft',
                1: 'Finalized',
                2: 'Completed',
            },

            workorder_next_status_def: {
                0: 'Finalize',
                1: 'Complete',
            },

            confirming_delete: false,

            confirming_next_status: false,

            form: this.$inertia.form({
                customer_id: this.workorder.customer_id,
                job_type_id: this.workorder.job_type_id,
                status: this.workorder.status,
                job_name: this.workorder.job_name,
                job_address: this.workorder.job_address,
                quoted_by: this.workorder.quoted_by,
                po: this.workorder.po,
                order_date: this.workorder.order_date,
                job_notes: this.workorder.job_notes,
                admin_notes: this.workorder.admin_notes,
                documents: this.workorder.documents !== undefined ? this.workorder.documents : [],
                items: this.workorder.items !== undefined ? this.workorder.items : [],
                images: this.workorder.images !== undefined ? this.workorder.images : []
            })
        }
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
            },100);
        },

        submit_form () {
            this.$refs.submit_button.click();
        },

        handle_submit_form () {
            this.$refs.observer.validate().then((result) => {
                if (result)
                {
                    if (this.workorder.id === null) this.form.post(route('workorders.workorder_add_store'));
                    else this.form.post(route('workorders.workorder_edit_store', {'workorder': this.workorder.id}));
                }
                else this.scroll_to_first_error();
            });
        },

        delete_workorder () {
            this.$inertia.visit(route('workorders.workorder_delete', {'workorder': this.workorder.id, }));
        },

        confirm_next_status () {
            this.confirming_next_status = true;
        },

        next_status () {
            this.form.status++;
            this.submit_form();
        },

        google_maps () {
            window.open('https://www.google.com/maps/dir/?api=1&destination=' + encodeURIComponent(this.form.job_address));
        }
    },

    computed: {
        job_type () {
            if (this.form.job_type_id === null) return null;
            return this.job_types.find(o => o.id === this.form.job_type_id);
        },
    }
}
</script>
