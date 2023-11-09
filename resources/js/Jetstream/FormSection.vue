<template>
    <div class="mb-12">
        <div class="font-bold text-xl md:text-2xl lg:text-3xl text-gray-800 leading-none mx-6 mb-4" :class="{'cursor-pointer select-none': collapsible}" @click="collapsed = collapsible ? !collapsed : false">
            <slot name="title"></slot>
            
            <span v-if="collapsible">
                <chevron-up-icon v-if="!collapsed"></chevron-up-icon>
                <chevron-down-icon v-if="collapsed"></chevron-down-icon>
            </span>
        </div>

        <slot name="content" v-if="!collapsible || !collapsed"></slot>
    </div>
</template>

<script>

    export default {
        props: {
            collapsible: {
                type: Boolean,
                default: false,
            },
        },

        data () {
            return {
                collapsed: true,
            };
        },
    }
</script>
