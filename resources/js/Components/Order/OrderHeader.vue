<script setup lang="ts">
import dayjs from 'dayjs';
import Order from '../../interfaces/Order';
import { ArrowLeft01Icon } from 'hugeicons-vue';
import { useRouter } from 'vue-router';

defineProps<{
    order?: Order
}>();

const router = useRouter();

const back = () => router.back();

</script>
<template>
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-2 gap-4">
        <div class="flex items-center gap-2">
            <button class="rounded-md bg-gray-200 p-2 hover:bg-gray-500 hover:text-white transition-colors duration-300" @click="back">
                <ArrowLeft01Icon />
            </button>
            <h1 class="font-bold text-xl lg:text-3xl">Order #{{ order?.id }}</h1>
        </div>
        <p class="text-right text-xs lg:text-base">Created on <span class="font-semibold">{{ dayjs(order?.date).format('DD MMM YYYY HH:mm:ss') }}</span></p>
    </div>
</template>