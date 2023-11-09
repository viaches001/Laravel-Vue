<template>
    <div :class="classes">
        <div v-if="has_title" class="text-lg font-bold"><slot name="title"></slot></div>
        <slot></slot>
    </div>
</template>

<script>
    export default {
        props: {
            class_type: {
                type: String,
                default: 'secondary',
            },
        },

        computed: {
            classes () {
                if (this.class_type == 'primary') return 'bg-blue-100 text-blue-600 font-bold px-6 py-3 my-4 rounded-3xl shadow-md';
                else if (this.class_type == 'danger') return 'bg-red-100 text-red-600 font-bold px-6 py-3 my-4 rounded-3xl shadow-md';
                else if (this.class_type == 'success') return 'bg-green-100 text-green-600 font-bold px-6 py-3 my-4 rounded-3xl shadow-md';
                else return 'bg-gray-100 text-gray-600 font-bold px-6 py-3 my-4 rounded-3xl shadow-md';
            },

            has_title () {
                return !!this.$slots.title;
            }
        }
    }
</script>
