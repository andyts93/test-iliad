<script setup lang="ts">
defineProps<{
    title: string;
}>();
</script>

<template>
    <div class="bg-gray-50 rounded-2xl p-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-xl">{{ title }}</h3>
            <slot name="icon"></slot>
        </div>
        <slot name="body"></slot>
    </div>
</template>