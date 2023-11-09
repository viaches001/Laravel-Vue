<template>
    <modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 relative">
            <div class="absolute top-0 right-0 m-4 flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:h-10 sm:w-10">
                <help-circle-outline-icon class="text-red-400"></help-circle-outline-icon>
            </div>

            <div class="mt-3 sm:mt-0">
                <h3 class="text-lg font-bold">
                    <slot name="title"></slot>
                </h3>

                <div class="mt-4">
                    <slot name="content"></slot>
                </div>
            </div>
        </div>

        <div class="p-4 bg-gray-100 text-right">
            <slot name="footer">
            </slot>
        </div>
    </modal>
</template>

<script>
    import Modal from './Modal'

    export default {
        components: {
            Modal,
        },

        props: {
            show: {
                default: false
            },
            maxWidth: {
                default: '2xl'
            },
            closeable: {
                default: true
            },
        },

        methods: {
            close() {
                this.$emit('close')
            },
        }
    }
</script>
