<script setup>
const props = defineProps(['value'])
</script>

<template>
    <span>${{ (value / 100).toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
</template>