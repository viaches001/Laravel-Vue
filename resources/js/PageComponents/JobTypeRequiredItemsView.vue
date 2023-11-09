<template>

<div class="px-6" v-if="!view_only">
    <div v-for="(item, index) in required_items" :key="index" class="mb-4">
        <span class="inline-flex gap-x-4">
            <span class="flex-shrink">
                <input type="checkbox" ref="required_item_checkbox" @change="all_checked">
            </span>
            <span class="flex-shrink">
                {{ item.quantity == null ? item.quantity : Number(item.quantity) }} {{ item.item.units }}
            </span>
            <span class="flex-grow">
                <div>{{ item.item.part_num }}</div>
                <div class="italic text-xs">{{ item.item.description }}</div>
            </span>
        </span>
    </div>
</div>
<ul class="list-inside px-6" v-else>
    <li v-for="(item, index) in required_items" :key="index" class="mb-4">
        <span class="inline-flex gap-x-4">
            <span class="flex-shrink">
                {{ item.quantity == null ? item.quantity : Number(item.quantity) }} {{ item.item.units }}
            </span>
            <span class="flex-grow">
                <div>{{ item.item.part_num }}</div>
                <div class="italic text-xs">{{ item.item.description }}</div>
            </span>
        </span>
    </li>
</ul>

</template>

<script>

    export default {
        props: {
            required_items: {
                type: Array,
            },

            view_only: {
                type: Boolean,
                default: false,
            },
        },

        methods: {
            all_checked () {
                let all_checked = true;
                this.$refs.required_item_checkbox.forEach((element) => {
                    if (!element.checked)
                    {
                        all_checked = false;
                        return false;
                    }
                });
                
                this.$emit('checkbox_changed', all_checked);
            }
        }
    }
</script>

