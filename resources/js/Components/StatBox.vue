<script setup lang="ts">
defineProps<{
    bgClass: string,
    shadowClass: string,
    iconClass: string,
    title: string,
    value: number | string,
}>();
</script>

<template>
    <div :class="['rounded-xl p-4 shadow-brutal relative overflow-hidden', bgClass, shadowClass]">
        <h3 class="text-xl">{{ title }}</h3>
        <span :class="['absolute -left-2 -bottom-4 opacity-30', iconClass]">
            <slot />
        </span>
        <p class="text-right font-bold text-6xl z-10">{{ value }}</p>
    </div>
</template>