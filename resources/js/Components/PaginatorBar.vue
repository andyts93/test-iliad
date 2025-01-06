<script setup lang="ts">
import PaginatedResults from '../interfaces/PaginatedResults';
import { ArrowLeft01Icon, ArrowRight01Icon } from 'hugeicons-vue';

defineProps<{ data: PaginatedResults<unknown> }>();

const emit = defineEmits<{
    (e: 'pageChange', page: number): void
}>();
</script>
<template>
    <div class="flex justify-between py-2 px-4 bg-gray-50 rounded-2xl items-center">
        <p class="text-sm hidden lg:block">Showing {{data.from}} to {{data.to}} of {{data.total}} records</p>
        <p class="text-sm lg:hidden">{{data.from}}-{{data.to}} of {{data.total}}</p>
        <div class="flex gap-4">
            <button type="button" title="Previous" class="rounded-md bg-gray-200 hover:bg-gray-500 hover:text-white transition-colors duration-300 disabled:opacity-50 disabled:pointer-events-none" @click="emit('pageChange', data.current_page - 1)" :disabled="data.current_page === 1">
                <ArrowLeft01Icon />
            </button>
            <span>{{ data.current_page }}</span>
            <button type="button" title="Previous" class="rounded-md bg-gray-200 hover:bg-gray-500 hover:text-white transition-colors duration-300 disabled:opacity-50 disabled:pointer-events-none" @click="emit('pageChange', data.current_page + 1)" :disabled="data.current_page === data.last_page">
                <ArrowRight01Icon />
            </button>
        </div>
    </div>
</template>