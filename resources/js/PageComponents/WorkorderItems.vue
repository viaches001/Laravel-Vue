<template>

<div>
    <workorder-item v-for="(item, index) in object.items" :key="index" :object="object" :items="items" :item="item" :index="index" :view_only="view_only" :hide_labels="hide_labels"></workorder-item>

    <div class="flex flex-row gap-4" v-if="!view_only">
        <div class="flex-grow"></div>
        <div class="flex-shrink">
            <jet-button type="button" shape="circle" class_type="secondary" @click.native="add_item()"><plus-icon></plus-icon></jet-button>
        </div>
    </div>
</div>

</template>

<script>
    import JetButton from '@/Jetstream/Button';
    import JetLabel from '@/Jetstream/Label';
    import JetInput from '@/Jetstream/Input';
    import JetSelect from '@/Jetstream/Select';
    import JetInputError from '@/Jetstream/InputError';

    import WorkorderItem from '@/PageComponents/WorkorderItem';

    import { ValidationObserver, ValidationProvider } from 'vee-validate';
    
    const empty_item = {
        'id': null,
        'workorder_id': null,
        'item_id': null,
        'quantity': null,
        'created_at': null,
        'updated_at': null,
    };

    export default {
        props: {
            object: {
                type: Object,
                default: {},
            },

            items: {
                type: Array,
                default: [],
            },
            
            view_only: {
                type: Boolean,
                default: false,
            },

            hide_labels: {
                type: Boolean,
                default: false,
            }
        },

        components: {
            JetButton,
            JetLabel,
            JetInput,
            JetSelect,
            JetInputError,

            WorkorderItem,

            ValidationObserver,
            ValidationProvider
        },

        created() {
            // If no items, add an empty one
            if (!this.view_only && this.object.items.length == 0) this.add_item();
        },

        methods: {
            add_item () {
                let new_item = JSON.parse(JSON.stringify(empty_item));
                this.object.items.push(new_item);
            },
        }
    }
</script>

