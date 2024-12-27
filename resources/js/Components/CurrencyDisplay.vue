<script setup>
const props = defineProps(['value', 'isPlain'])

if (props.isPlain) {
    props.value /= 100;
}
</script>

<template>
    <span>${{ value.toLocaleString(undefined, { minimumFractionDigits: 2 }) }}</span>
</template>