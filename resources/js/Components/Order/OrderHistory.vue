<script setup lang="ts">
import dayjs from 'dayjs';
import Order from '../../interfaces/Order';
import Card from '../CardDefault.vue';
import { Timer02Icon } from 'hugeicons-vue';

defineProps<{
    order: Order
}>();

</script>

<template>
    <Card title="History">
        <template v-slot:icon>
            <Timer02Icon />
        </template>
        <template v-slot:body>
            <ol class="relative border-s border-gray-300 ml-6">
                <li class="mb-10 ms-4">
                    <div class="absolute w-3 h-3 bg-teal-700 rounded-full mt-1.5 -start-1.5 border border-gray-50"></div>
                    <h3 class="text-base md:text-lg font-semibold text-teal-700">Shipped</h3>
                    <time class="mb-1 text-sm font-normal leading-none text-gray-600">{{ dayjs(order.date).add(3, 'day').format('DD MMM YYYY') }}</time>
                </li>
                <li class="mb-10 ms-4">
                    <div class="absolute w-3 h-3 bg-slate-800 rounded-full mt-1.5 -start-1.5 border border-gray-50"></div>
                    <h3 class="text-base md:text-lg font-semibold text-gray-900">Prepared</h3>
                    <time class="mb-1 text-sm font-normal leading-none text-gray-600">{{ dayjs(order.date).add(2, 'day').format('DD MMM YYYY') }}</time>
                </li>
                <li class="mb-10 ms-4">
                    <div class="absolute w-3 h-3 bg-slate-800 rounded-full mt-1.5 -start-1.5 border border-gray-50"></div>
                    <h3 class="text-base md:text-lg font-semibold text-gray-900">Created</h3>
                    <time class="mb-1 text-sm font-normal leading-none text-gray-600">{{ dayjs(order.date).format('DD MMM YYYY') }}</time>
                </li>
            </ol>
        </template>
    </Card>
</template>