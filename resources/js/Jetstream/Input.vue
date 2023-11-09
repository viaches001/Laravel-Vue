<template>
    <input class="form-input rounded-3xl w-full px-6 shadow-sm" :class="{'border border-red-400 shadow-outline-red': errors}" :value="value" @input="$emit('input', $event.target.value)" ref="input">
</template>

<script>
    export default {
        props: ['errors', 'value'],

        methods: {
            focus() {
                this.$refs.input.focus()
            }
        }
    }
</script>

<style scoped>
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    margin: 0; 
    }

    input:disabled {
        cursor: not-allowed;
        background-color: #f8f8f8;
    }
</style>

