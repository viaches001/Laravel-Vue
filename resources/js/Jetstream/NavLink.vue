<template>
    <inertia-link :href="href" :class="classes" v-if="!subnav_toggle">
        <slot></slot>
    </inertia-link>

    <div :class="classes" v-else>
        <slot></slot>
    </div>
</template>

<script>
    export default {
        props: ['href', 'active', 'subnav_toggle'],

        computed: {
            classes() {
                return this.active
                            ? 'inline-flex items-center cursor-pointer w-full h-12 px-4 md:px-6 lg:px-8 text-base md:text-lg font-bold leading-5 text-gray-900 bg-gray-200 focus:outline-none focus:bg-grey-100 transition duration-150 ease-in-out'
                            : 'inline-flex items-center cursor-pointer w-full h-12 px-4 md:px-6 lg:px-8 text-base md:text-lg font-bold leading-5 text-gray-500 bg-transparent hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:text-gray-700 focus:bg-gray-100 transition duration-150 ease-in-out'
            }
        }
    }
</script>
