<template>
    <label class="block font-semibold ml-6" :class="classes">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot></slot></span>
    </label>
</template>

<script>
    export default {
        props: ['index', 'value', 'hidden_classes'],

        computed: {
            classes () {
                return this.index > 0 ? (this.hidden_classes || 'md:hidden') : '';
            }
        }
    }
</script>
