<script setup>
    import dayjs from 'dayjs';
    import { onMounted, ref } from 'vue';
    import { useRoute, useRouter } from 'vue-router';
    import CurrencyDisplay from '../Components/CurrencyDisplay.vue';
    import { BorderFullIcon, Timer02Icon, Edit02Icon, Delete01Icon } from 'hugeicons-vue';
    import Swal from 'sweetalert2';

    const route = useRoute();
    const router = useRouter();
    const order = ref();
    const loading = ref(true);

    const deleteOrder = async () => {
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
            try {
                await axios.delete(`/api/v1/orders/${route.params.id}`);
                router.replace('/');
            }
            catch (e) {
                console.error(e);
            }
        }
    }

    onMounted(async () => {
        try {
            const response = await axios.get('/api/v1/orders/' + route.params.id);
            
            order.value = response.data;
        }
        catch (e) {
            console.error(e);
        }
        loading.value = false;
    });
</script>
<template>
    <div v-if="loading"></div>
    <div v-else>
        <div class="flex items-center justify-between mb-4">
            <h1 class="font-bold text-3xl">Order #{{ order.id }}</h1>
            <p>Created on <span class="font-semibold">{{ dayjs(order.date).format('DD MMM YYYY HH:mm:ss') }}</span></p>
            <div class="flex gap-2">
                <button class="rounded-md bg-gray-200 p-2 hover:bg-gray-500 hover:text-white transition-colors duration-300">
                    <Edit02Icon />
                </button>
                <button class="rounded-md bg-red-300 p-2 hover:bg-red-500 hover:text-white transition-colors duration-300" @click="deleteOrder">
                    <Delete01Icon />
                </button>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-8 mb-4">
            <div class="bg-gray-50 rounded-2xl p-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-xl">Details</h3>
                    <BorderFullIcon />
                </div>
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
            </div>
            <div class="bg-gray-50 rounded-2xl p-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-xl">History</h3>
                    <Timer02Icon />
                </div>
                <ol class="relative border-s border-gray-300 ml-6">
                    <li class="mb-10 ms-4">
                        <div class="absolute w-3 h-3 bg-teal-600 rounded-full mt-1.5 -start-1.5 border border-gray-50"></div>
                        <h3 class="text-lg font-semibold text-teal-600">Shipped</h3>
                        <time class="mb-1 text-sm font-normal leading-none text-gray-400">{{ dayjs(order.date).add(3, 'day').format('DD MMM YYYY') }}</time>
                    </li>
                    <li class="mb-10 ms-4">
                        <div class="absolute w-3 h-3 bg-slate-800 rounded-full mt-1.5 -start-1.5 border border-gray-50"></div>
                        <h3 class="text-lg font-semibold text-gray-900">Prepared</h3>
                        <time class="mb-1 text-sm font-normal leading-none text-gray-400">{{ dayjs(order.date).add(2, 'day').format('DD MMM YYYY') }}</time>
                    </li>
                    <li class="mb-10 ms-4">
                        <div class="absolute w-3 h-3 bg-slate-800 rounded-full mt-1.5 -start-1.5 border border-gray-50"></div>
                        <h3 class="text-lg font-semibold text-gray-900">Created</h3>
                        <time class="mb-1 text-sm font-normal leading-none text-gray-400">{{ dayjs(order.date).format('DD MMM YYYY') }}</time>
                    </li>
                </ol>
            </div>
        </div>
        <table class="w-full">
            <thead>
                <tr>
                    <th class="text-left">Product</th>
                    <th class="text-right">Price</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in order.products" :key="product.id">
                    <td class="py-2">
                        <div class="flex items-center gap-8">
                            <img :src="`https://picsum.photos/seed/${product.id}/80`" class="rounded-lg" />
                            <p class="uppercase">{{ product.name }}</p>
                        </div>
                    </td>
                    <td class="text-right"><currency-display :value="product.price" /></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <td colspan="2" class="border-t pt-2 text-right font-bold text-xl"><currency-display :value="order.products.reduce((prev, curr) => prev + curr.price, 0)" /></td>
                </tr>
            </tfoot>
        </table>
    </div>
</template>