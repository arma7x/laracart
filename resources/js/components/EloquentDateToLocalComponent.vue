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
                const offset = -(new Date().getTimezoneOffset() * 60 * 1000);
                const d = new Date(date);
                d.setTime(d.getTime() + offset);
                return d.toLocaleString();
            }
        },
        mounted() {}
    }
</script>
