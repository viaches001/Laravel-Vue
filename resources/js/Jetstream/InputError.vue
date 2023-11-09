<template>
    <div v-show="message">
        <p class="text-red-400 text-sm ml-6 mt-1">
            {{ message }}
        </p>
    </div>
</template>

<script>
    export default {
        props: ['message']
    }
</script>
