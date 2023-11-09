<template>
    <button type="button" :class="classes">
        <div>
            <checkbox-blank-circle-outline-icon class="text-gray-400" v-if="!this.value"></checkbox-blank-circle-outline-icon>
            <checkbox-marked-circle-outline-icon class="text-blue-400" v-else></checkbox-marked-circle-outline-icon>
        </div>
        <slot></slot>
    </button>
</template>

<script>
    export default {
        props: {
            value: {
                type: Boolean,
                default: false,
            },
        },

        computed: {
            classes () {
                return ' inline-flex pl-2 pr-4 py-2 leading-6 inline-flex gap-2 justify-center items-center bg-white border border-gray-400 rounded-full text-sm lg:text-base hover:bg-gray-100 active:bg-gray-200 focus:outline-none active:border-gray-500 focus:shadow-outline-blue disabled:opacity-25 transition ease-in-out duration-150';
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

