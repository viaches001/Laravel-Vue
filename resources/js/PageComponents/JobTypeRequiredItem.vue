<template>

<ValidationObserver>
    <div class="flex flex-col">
        <div class="flex flex-row gap-4">
            <div class="flex-grow mb-2">
                <jet-label :key="'item_id_' + index" :index="index" value="Material" />
                <ValidationProvider :name="'item_id_' + index" :rules="{'required': item.quantity != null && item.quantity != ''}" v-slot="{ errors }">
                    <jet-select :id="'item_id_' + index" v-model="item.item_id" :options="items" value_field="id" label_field="part_num" label_field2="description" :add_items="true" :errors="(object_is_form && object.error('item_id_' + index)) || errors.length > 0" autocomplete="item_id" :disabled="view_only"/>
                    <jet-input-error :message="object.error('item_id_' + index)" v-if="object_is_form && object.error('item_id_' + index)"/>
                    <jet-input-error :message="errors[0]" v-else/>
                </ValidationProvider>
            </div>
            
            <div class="flex-shrink mb-2">
                <jet-label :key="'item_quantity_' + index" :index="index" value="Quantity" />
                <ValidationProvider :name="'item_quantity_' + index" :rules="{'required': item.item_id != null && item.item_id != ''}" v-slot="{ errors }">
                    <jet-input-group v-if="selected_item != null && selected_item.units != null">
                        <jet-input :id="'item_quantity_' + index" type="number" v-model.number="item.quantity" :errors="(object_is_form && object.error('item_quantity_' + index)) || errors.length > 0" autocomplete="quantity" :disabled="view_only"/>
                        <jet-input-group-text>{{ selected_item.units }}</jet-input-group-text>
                    </jet-input-group>
                    <jet-input v-else :id="'item_quantity_' + index" type="number" v-model.number="item.quantity" :errors="(object_is_form && object.error('item_quantity_' + index)) || errors.length > 0" autocomplete="quantity" :disabled="view_only"/>
                    <jet-input-error :message="object.error('item_quantity_' + index)" v-if="object_is_form && object.error('item_quantity_' + index)"/>
                    <jet-input-error :message="errors[0]" v-else/>
                </ValidationProvider>
            </div>

            <div class="flex-shrink mb-2">
                <jet-button type="button" shape="circle" class_type="danger" :class="{'mt-6': index === 0, 'invisible': view_only}" :disabled="object.items.length == 1" @click.native="delete_item(index)"><trash-can-outline-icon></trash-can-outline-icon></jet-button>
            </div>
        </div>
    </div>
</ValidationObserver>

</template>

<script>
    import JetButton from '@/Jetstream/Button';
    import JetLabel from '@/Jetstream/Label';
    import JetInputGroup from '@/Jetstream/InputGroup';
    import JetInputGroupText from '@/Jetstream/InputGroupText';
    import JetInput from '@/Jetstream/Input';
    import JetSelect from '@/Jetstream/Select';
    import JetInputError from '@/Jetstream/InputError';

    import { ValidationObserver, ValidationProvider } from 'vee-validate';
    
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
            
            item: {
                type: Object,
            },
            
            index: {
                type: Number,
            },
        },

        components: {
            JetButton,
            JetLabel,
            JetInputGroup,
            JetInputGroupText,
            JetInput,
            JetSelect,
            JetInputError,
            ValidationObserver,
            ValidationProvider
        },

        methods: {
            delete_item (index) {
                this.object.items.splice(index, 1);
            },
        },

        computed: {
            selected_item () {
                if (this.item.item_id === null) return null;
                return this.items.find(o => o.id === this.item.item_id);
            },

            object_is_form () {
                return Object.keys(this.object).includes('__options');
            }
        }
    }
</script>

