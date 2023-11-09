<template>

<ValidationObserver>
    <div class="flex flex-col" v-for="(action, index) in object.actions" :key="index">
        <div class="flex flex-row gap-4">
            <div class="flex-grow mb-2">
                <jet-label :for="'action_description_' + index" :index="index" value="Description" />
                <jet-textarea rows="1" :id="'action_description_' + index" v-model="action.description" :errors="object.error('action.description')" autocomplete="description"/>
                <jet-input-error :message="object.error('action.description')" v-if="object.error('action.description')"/>
            </div>

            <div class="flex-shrink mb-2">
                <jet-button type="button" shape="circle" class_type="danger" :class="{'mt-6': index === 0}" :disabled="object.actions.length == 1" @click.native="delete_action(index)"><trash-can-outline-icon></trash-can-outline-icon></jet-button>
            </div>
        </div>
    </div>

    <div class="flex flex-row gap-4" v-if="!view_only">
        <div class="flex-grow"></div>
        <div class="flex-shrink">
            <jet-button type="button" shape="circle" class_type="secondary" @click.native="add_action()"><plus-icon></plus-icon></jet-button>
        </div>
    </div>
</ValidationObserver>

</template>

<script>
    import JetButton from '@/Jetstream/Button';
    import JetLabel from '@/Jetstream/Label';
    import JetInput from '@/Jetstream/Input';
    import JetTextarea from '@/Jetstream/Textarea';
    import JetSelect from '@/Jetstream/Select';
    import JetInputError from '@/Jetstream/InputError';

    import { ValidationObserver, ValidationProvider } from 'vee-validate';
    
    const empty_action = {
        'id': null,
        'job_type_id': null,
        'description': null,
        'created_at': null,
        'updated_at': null,
    };

    export default {
        props: {
            object: {
                type: Object,
                default: {},
            },
            
            view_only: {
                type: Boolean,
                default: false,
            },
        },

        components: {
            JetButton,
            JetLabel,
            JetInput,
            JetTextarea,
            JetSelect,
            JetInputError,

            ValidationObserver,
            ValidationProvider
        },

        created() {
            // If no actions, add an empty one
            if (!this.view_only && this.object.actions.length == 0) this.add_action();
        },

        methods: {
            add_action () {
                let new_action = JSON.parse(JSON.stringify(empty_action));
                this.object.actions.push(new_action);
            },

            delete_action (index) {
                this.object.actions.splice(index, 1);
            }
        }
    }
</script>

