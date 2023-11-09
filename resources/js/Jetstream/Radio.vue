<template>
    <button type="button" :class="classes">
        <div class="absolute top-0 left-0 mx-2 my-2">
            <checkbox-blank-circle-outline-icon :class="errors ? 'text-red-400' : 'text-gray-400'" v-if="this.value !== this.checked_value"></checkbox-blank-circle-outline-icon>
            <checkbox-marked-circle-outline-icon :class="errors ? 'text-red-400' : 'text-blue-400'" v-else></checkbox-marked-circle-outline-icon>
        </div>
        <slot></slot>
    </button>
</template>

<script>
    export default {
        props: ['errors', 'value', 'checked_value'],

        computed: {
            classes () {
                return ' relative flex-grow w-full px-6 py-2 leading-6 inline-flex gap-2 justify-center items-center bg-white border border-gray-400 rounded-full text-sm lg:text-base hover:bg-gray-100 active:bg-gray-200 focus:outline-none active:border-gray-500 focus:shadow-outline-blue disabled:opacity-25 transition ease-in-out duration-150' + (this.errors ? ' border border-red-400 shadow-outline-red' : '');
            }
        }
    }
</script>

<style scoped>
    button:disabled {
        cursor: not-allowed !important;
        background-color: #f8f8f8 !important;
        opacity: 100% !important;
    }
</style>