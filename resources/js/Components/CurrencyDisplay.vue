<script setup>
defineProps(['value']);
</script>

<template>
    <span>${{ value.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
</template>