<template>

<ValidationObserver>
    <div class="flex flex-col">
        <div class="flex flex-wrap flex-row gap-x-4" :class="{ 'mb-8 md:mb-0': index !== (object.items.length - 1) }">
            <div class="flex-grow w-full md:w-auto mb-2">
                <jet-label :key="'item_id_' + index" :index="index" value="Material" />
                <ValidationProvider :ref="'item_id_' + index" :name="'item_id_' + index" :rules="{'required': item.quantity != null && item.quantity != ''}" v-slot="{ errors }">
                    <jet-select :id="'item_id_' + index" v-model="item.item_id" :options="items" value_field="id" label_field="part_num" label_field2="description" :add_items="true" :errors="object.error('item_id') || errors.length > 0" :disabled="view_only"/>
                    <jet-input-error :message="object.error('item_id')" v-if="object.error('item_id')"/>
                    <jet-input-error :message="errors[0]" v-else/>
                </ValidationProvider>
            </div>
            
            <div class="flex-grow md:flex-grow-0 md:flex-shrink mb-2" :style="view_only ? 'max-width: calc(100% - 91px - 1rem)' : 'max-width: calc(100% - 134px - 2rem)'">
                <jet-label :key="'item_quantity_' + index" :index="index" value="Quantity" />
                <ValidationProvider :ref="'item_quantity_' + index" :name="'item_quantity_' + index" :rules="{'required': item.item_id != null && item.item_id != ''}" v-slot="{ errors }">
                    <jet-input-group v-if="selected_item != null && selected_item.units != null">
                        <jet-input :id="'item_quantity_' + index" type="number" pattern="[0-9]*" inputmode="numeric" v-model.number="item.quantity" :errors="object.error('item_quantity_' + index) || errors.length > 0" :disabled="view_only"/>
                        <jet-input-group-text>{{ selected_item.units }}</jet-input-group-text>
                    </jet-input-group>
                    <jet-input v-else :id="'item_quantity_' + index" type="number" pattern="[0-9]*" inputmode="numeric" v-model.number="item.quantity" :errors="object.error('item_quantity_' + index) || errors.length > 0" :disabled="view_only"/>
                    <jet-input-error :message="object.error('item.quantity')" v-if="object.error('item.quantity')"/>
                    <jet-input-error :message="errors[0]" v-else/>
                </ValidationProvider>
            </div>
            
            <div class="flex-shrink mb-2">
                <jet-checkbox @click.native="item.extra = !item.extra" :value="item.extra" :class="index === 0 ? 'mt-6' : 'mt-6 md:mt-0'" :disabled="view_only">Extra</jet-checkbox>
            </div>

            <div class="flex-shrink mb-2" v-if="!view_only">
                <jet-button type="button" shape="circle" class_type="danger" :class="index === 0 ? 'mt-6' : 'mt-6 md:mt-0'" :disabled="object.items.length == 1" @click.native="delete_item(index)"><trash-can-outline-icon></trash-can-outline-icon></jet-button>
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
    import JetCheckbox from '@/Jetstream/Checkbox';
    import JetInputError from '@/Jetstream/InputError';

    import { ValidationObserver, ValidationProvider } from 'vee-validate';
    
    const empty_item = {
        'id': null,
        'workorder_id': null,
        'item_id': null,
        'quantity': null,
        'extra': false,
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
            JetCheckbox,
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
            }
        }
    }
</script>

