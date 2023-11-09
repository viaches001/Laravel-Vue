<template>

<ul class="list-inside px-6 mb-4">
    <li v-for="(action, index) in object.actions" :key="index">
        {{ action.description }}
    </li>
</ul>

</template>

<script>

    export default {
        props: {
            object: {
                type: Object,
                default: {},
            },
        },
    }
</script>

