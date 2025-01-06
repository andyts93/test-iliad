<script setup lang="ts">
    import { onMounted, Ref, ref } from 'vue';
    import { useRoute, useRouter } from 'vue-router';
    import { BorderFullIcon, Edit02Icon, Delete01Icon } from 'hugeicons-vue';
    import Swal from 'sweetalert2';
    import ProductsTable from '../../Components/Order/ProductsTable.vue';
    import History from '../../Components/Order/OrderHistory.vue';
    import Card from '../../Components/CardDefault.vue';
    import { getOrder, deleteOrder } from '../../api/orders';
    import SkeletonLoader from '../../Components/SkeletonLoader.vue';
    import OrderHeader from '../../Components/Order/OrderHeader.vue';
    import Order from '../../interfaces/Order';

    const route = useRoute();
    const router = useRouter();
    const order: Ref<Order | undefined> = ref();
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
            .catch(err => {
                if(err.response.status === 404) {
                    router.replace('/not-found');
                }
            })
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
    <div v-else-if="order">
        <OrderHeader :order="order" />
        <div class="flex justify-end gap-2 mb-4">
            <router-link :to="`/orders/${order.id}/edit`" class="rounded-md bg-gray-200 p-2 hover:bg-gray-500 hover:text-white transition-colors duration-300" title="Edit order">
                <Edit02Icon />
            </router-link>
            <button class="rounded-md bg-red-300 p-2 hover:bg-red-500 hover:text-white transition-colors duration-300" @click="delOrder" title="Delete order">
                <Delete01Icon />
            </button>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-8 mb-4">
            <Card title="Details">
                <template v-slot:icon>
                    <BorderFullIcon />
                </template>
                <template v-slot:body>
                    <table class="w-full text-sm md:text-base">
                        <tbody>
                            <tr class="flex flex-col mb-4 sm:table-row">
                                <th class="text-left align-top pr-6 font-semibold">Name</th>
                                <td class="text-right font-light">{{ order.name }}</td>
                            </tr>
                            <tr class="flex flex-col mb-4 sm:table-row">
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