<script setup lang="ts">
    import dayjs from 'dayjs';
    import { onMounted, ref } from 'vue';
    import { useRoute, useRouter } from 'vue-router';
    import { BorderFullIcon, Edit02Icon, Delete01Icon } from 'hugeicons-vue';
    import Swal from 'sweetalert2';
    import ProductsTable from '../../Components/Order/ProductsTable.vue';
    import History from '../../Components/Order/History.vue';
    import Card from '../../Components/Card.vue';
    import { getOrder, deleteOrder } from '../../api/orders';
import SkeletonLoader from '../../Components/SkeletonLoader.vue';

    const route = useRoute();
    const router = useRouter();
    const order = ref();
    const loading = ref(true);

    const delOrder = async () => {
        const result = await Swal.fire({
            title: 'Warning',
            text: 'Do you want to delete this order?',
            icon: 'warning',
            confirmButtonText: 'Sure',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showCancelButton: true,
            reverseButtons: true,
            confirmButtonColor: '#FF0000'
        });
        if (result.isConfirmed) {
            deleteOrder(Number(route.params.id))
                .then(() => router.replace('/'))
        }
    }

    onMounted(() => {
        getOrder(Number(route.params.id))
            .then(response => order.value = response.data)
            .finally(() => loading.value = false);
    });
</script>
<template>
    <div v-if="loading">
        <div class="flex justify-between mb-2">
            <SkeletonLoader class="w-32 h-8" />
            <SkeletonLoader class="w-32 h-8" />
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-8 mb-4">
            <SkeletonLoader class="h-96 rounded-xl" />
            <SkeletonLoader class="h-96 rounded-xl" />
        </div>
    </div>
    <div v-else>
        <div class="flex items-center justify-between mb-2 gap-4">
            <h1 class="font-bold text-xl lg:text-3xl">Order #{{ order.id }}</h1>
            <p class="text-right text-xs lg:text-base">Created on <span class="font-semibold">{{ dayjs(order.date).format('DD MMM YYYY HH:mm:ss') }}</span></p>
        </div>
        <div class="flex justify-end gap-2 mb-4">
            <router-link :to="`/orders/${order.id}/edit`" class="rounded-md bg-gray-200 p-2 hover:bg-gray-500 hover:text-white transition-colors duration-300">
                <Edit02Icon />
            </router-link>
            <button class="rounded-md bg-red-300 p-2 hover:bg-red-500 hover:text-white transition-colors duration-300" @click="delOrder">
                <Delete01Icon />
            </button>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-8 mb-4">
            <Card title="Details">
                <template v-slot:icon>
                    <BorderFullIcon />
                </template>
                <template v-slot:body>
                    <table class="w-full">
                        <tbody>
                            <tr>
                                <th class="text-left align-top pr-6 font-semibold">Name</th>
                                <td class="text-right font-light">{{ order.name }}</td>
                            </tr>
                            <tr>
                                <th class="text-left align-top pr-6 font-semibold">Description</th>
                                <td class="text-right font-light italic">{{ order.description }}</td>
                            </tr>
                        </tbody>
                    </table>
                </template>
            </Card>
            <History :order="order" />
        </div>
        <ProductsTable :products="order.products" />
    </div>
</template>