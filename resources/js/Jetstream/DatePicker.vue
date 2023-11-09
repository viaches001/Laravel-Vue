<template>
    <date-picker value-type="format" :value="value" @input="update_value" :class="{'errors': errors}" :disabled="disabled" :clearable="clearable && !disabled"></date-picker>
</template>

<script>
    import DatePicker from 'vue2-datepicker';

    export default {
        props: ['clearable', 'disabled', 'errors', 'value'],

        components: { DatePicker },

        methods: {
            focus() {
                this.$refs.input.focus()
            },

            update_value: function (value, id) {
                this.$emit('input', value)
            },
        },
    }
</script>