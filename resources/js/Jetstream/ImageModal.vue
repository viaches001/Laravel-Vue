<template>
    <modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close" class="relative">
        <div class="absolute top-0 right-0 m-2">
            <jet-button type="button" shape="circle" class_type="secondary" @click.native.stop="close"><close-icon></close-icon></jet-button>
        </div>
        
        <img class="w-full" :src="img_src" alt="Uploaded Image" draggable="false">
        
        <div class="px-6 py-4" v-if="img_description !== null">
            {{ img_description }}
        </div>
    </modal>
</template>

<script>
    import Modal from './Modal';
    import JetButton from '@/Jetstream/Button';

    export default {
        components: {
            Modal,
            JetButton
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
            img_src: {
                default: null
            },
            img_description: {
                default: null
            },
        },

        methods: {
            close() {
                this.$emit('close')
            },
        }
    }
</script>
