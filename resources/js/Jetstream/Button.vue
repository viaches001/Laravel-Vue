<template>
    <button :type="type" :class="classes" :disabled="!online">
        <slot></slot>
    </button>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        props: {
            type: {
                type: String,
                default: 'submit',
            },
            class_type: {
                type: String,
                default: 'secondary',
            },
            shape: {
                type: String,
                default: 'default',
            },
        },

        computed: {
            ... mapState({
                online: state => state.online,
            }),

            classes () {
                if (this.class_type == 'primary') return (this.shape == 'default' ? 'px-4 py-2 ' : 'p-2 ') + 'inline-flex gap-2 justify-center items-center bg-blue-600 border border-transparent rounded-full text-sm lg:text-base text-white font-bold hover:bg-blue-500 active:bg-blue-700 focus:outline-none active:border-blue-700 focus:shadow-outline-blue disabled:opacity-25 transition ease-in-out duration-150';
                else if (this.class_type == 'danger') return (this.shape == 'default' ? 'px-4 py-2 ' : 'p-2 ') + 'inline-flex gap-2 justify-center items-center bg-gray-800 border border-transparent rounded-full text-sm lg:text-base text-white font-bold hover:bg-red-500 active:bg-red-700 focus:outline-none active:border-red-700 focus:shadow-outline-red disabled:opacity-25 transition ease-in-out duration-150';
                else if (this.class_type == 'success') return(this.shape == 'default' ? 'px-4 py-2 ' : 'p-2 ') + 'inline-flex gap-2 justify-center items-center bg-green-600 border border-transparent rounded-full text-sm lg:text-base text-white font-bold hover:bg-green-500 active:bg-green-700 focus:outline-none active:border-green-700 focus:shadow-outline-green disabled:opacity-25 transition ease-in-out duration-150';
                else return(this.shape == 'default' ? 'px-4 py-2 ' : 'p-2 ') + 'inline-flex gap-2 justify-center items-center bg-gray-800 border border-transparent rounded-full text-sm lg:text-base text-white font-bold hover:bg-gray-600 active:bg-gray-900 focus:outline-none active:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150';
            },
        }
    }
</script>
