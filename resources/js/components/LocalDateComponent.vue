<template>
    <span>{{ toLocal(date) }}</span>
</template>

<script>
    export default {
        props: {
            date: String
        },
        methods: {
            toLocal: (date) => {
                return new Date(date).toLocaleString()
            }
        },
        mounted() {}
    }
</script>
