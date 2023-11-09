<template>
    <inertia-link :href="href" :class="classes">
        <slot></slot>
    </inertia-link>
</template>

<script>
    export default {
        props: ['href', 'active'],

        computed: {
            classes() {
                return this.active
                            ? 'inline-flex items-center w-full h-12 px-4 md:px-6 lg:px-8 text-base md:text-lg font-medium leading-5 text-white bg-gray-200 bg-opacity-25 focus:outline-none focus:border-white transition duration-150 ease-in-out'
                            : 'inline-flex items-center w-full h-12 px-4 md:px-6 lg:px-8 text-base md:text-lg font-medium leading-5 text-gray-200 bg-transparent hover:text-white hover:bg-gray-100 hover:bg-opacity-25 focus:outline-none focus:text-white focus:bg-gray-100 focus:bg-opacity-25 transition duration-150 ease-in-out'
            }
        }
    }
</script>
