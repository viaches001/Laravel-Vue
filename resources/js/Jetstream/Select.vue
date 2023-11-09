<template>
    <!-- Possibly add the focus outline and shadow? -->
    <!-- Maybe switch to Popper Js positioning to dynamically show above or below select -->

    <v-select ref="select" :filter="fuse_search" :options="options" :reduce="option => option[value_field]" :label="label_field" :value="value" @input="update_value" @search:blur="searching = false" @search:focus="searching = true" @option:selecting="add_item" :class="{'errors': errors}" :disabled="disabled" :clearable="clearable && !disabled" :selectOnTab="true">
        <!-- Selected Option -->
        <template v-if="label_field2 != null" v-slot:selected-option="option">
            <div class="md:flex md:items-center md:justify-center md:gap-x-4">
                <div class="md:flex-shrink">{{ option[label_field] }}</div>
                <div class="md:flex-grow italic text-xs">{{ searching ? '' : option[label_field2] }}</div>
            </div>
        </template>

        <!-- Option -->
        <template v-if="label_field2 != null" v-slot:option="option">
            <div class="flex gap-x-4">
                <div class="flex-grow font-semibold">{{ option[label_field] }}<sup v-if="option['add']">&nbsp;&nbsp;&nbsp;<span class="px-2 text-white rounded-full" :class="option['add'] && (option[label_field2] === null || option[label_field2] === '') ? 'bg-red-400': 'bg-blue-400'">Add New</span></sup></div>
                <div v-if="option['add']" class="flex-shrink font-bold" :class="option['add'] && (option[label_field2] === null || option[label_field2] === '') ? 'text-red-400': 'text-blue-400'">
                    <alert-decagram-icon v-if="option['add'] && (option[label_field2] === null || option[label_field2] === '')"></alert-decagram-icon>
                    <decagram-icon v-else></decagram-icon>
                </div>
            </div>
            <span class="italic text-xs" :class="{'text-red-400': option['add'] && (option[label_field2] === null || option[label_field2] === '')}">{{ option['add'] && (option[label_field2] === null || option[label_field2] === '') ? 'Description required (add a double space to start the description)' : option[label_field2] }}</span>
        </template>
    </v-select>
</template>

<script>
    import vSelect from 'vue-select';
    import Fuse from 'fuse.js';

    export default {
        props: ['options', 'value_field', 'label_field', 'label_field2', 'add_items', 'empty_option', 'clearable', 'disabled', 'errors', 'value'],

        components: { vSelect },

        data () {
            return {
                searching: false,

                add_option: true,
            }
        },

        methods: {
            focus() {
                this.$refs.select.focus()
            },

            update_value: function (value, id) {
                this.$emit('input', value)
            },
            
            fuse_search(options, search) {
                let strict_fuse = null;
                let strict_resulting_list = [];
                if (this.add_items)
                {
                    strict_fuse = new Fuse(options, {
                        keys: this.fuse_search_keys,
                        shouldSort: true,
                        threshold: 0.0
                    });

                    strict_resulting_list = search.length ? strict_fuse.search(search).map(({ item }) => item) : strict_fuse.list;
                }
                
                let fuse = new Fuse(options, {
                    keys: this.fuse_search_keys,
                    shouldSort: true,
                    threshold: 0.4
                });

                let resulting_list = search.length ? fuse.search(search).map(({ item }) => item) : fuse.list;
                
                if (this.add_items && strict_resulting_list.length <= 0)
                {
                    let split_search = search.split('  ');
                    let label = (split_search[0]).trim();
                    let label2 = null;

                    if (this.label_field2 != null)
                    {
                        split_search.shift();
                        label2 = split_search.join(' ');
                    }

                    let add_option = {};
                    add_option['add'] = true;
                    add_option[this.label_field] = label;
                    if (this.label_field2 != null) add_option[this.label_field2] = label2;
                    add_option[this.value_field] = search;
                    resulting_list.push(add_option)
                }

                return resulting_list;
            },

            add_item (selected_option) {
                if (this.add_items && selected_option['add'])
                {
                    if (selected_option[this.label_field2] === null || selected_option[this.label_field2] === '')
                    {
                        this.$nextTick(() => {
                            this.update_value(null);
                        });
                    }
                    else
                    {
                        let data = {
                            part_num: selected_option[this.label_field],
                            description: selected_option[this.label_field2]
                        };

                        axios.post('/settings/item_async', data)
                        .then(response => {
                            // Update the options
                            let new_item = {
                                'id': response.data.item.id,
                                'part_num': response.data.item.part_num,
                                'description': response.data.item.description,
                            };
                            this.options.push(new_item);

                            this.options.sort((a, b) => {
                                if (a.part_num < b.part_num) return -1;
                                else if (a.part_num > b.part_num) return 1;
                                else if (a.description < b.description) return -1;
                                else if (a.description > b.description) return -1;
                                return 0;
                            });

                            // Update the selected value
                            this.$nextTick(() => {
                                this.update_value(response.data.item.id);
                            });
                        });
                    }
                }
            }
        },

        computed: {
            fuse_search_keys () {
                let keys = [this.label_field];
                if (this.label_field2 != null) keys.push(this.label_field2);

                return keys;
            }
        },

        watch: {
            searching () {
                if (this.searching) {
                    setTimeout(() => {
                        window.scroll(0, (this.$refs.select.$el.offsetTop - 100));
                        // this.$refs.select.$el.scrollIntoView({block: "center"});
                    }, 300);
                }
            }
        }
    }
</script>