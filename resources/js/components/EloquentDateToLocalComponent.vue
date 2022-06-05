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
                return window._globalEloquentDateToLocal(date);
            }
        },
        mounted() {}
    }
</script>
