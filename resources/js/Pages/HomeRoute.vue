<script setup>
    import axios from 'axios';
    import { ref, onMounted } from 'vue';
    import dayjs from 'dayjs';
    import CurrencyDisplay from '../Components/CurrencyDisplay.vue';
    import { ShoppingCartCheck02Icon, DeliveryBox01Icon, MoneyReceive02Icon, UserMultipleIcon } from 'hugeicons-vue';

    const orders = ref([]);
    const queryDate = ref();
    const queryName = ref();
    const queryDescription = ref();

    const loadOrders = async () => {
        try {
            const response = await axios.get('/api/v1/orders', {
                params: {
                    name: queryName.value,
                    description: queryDescription.value,
                    date: queryDate.value,
                }
            });
            
            orders.value = response.data;
        }
        catch (e) {
            console.error(e);
        }
    }

    const search = async (e) => {
        e.preventDefault();
        await loadOrders();
    }

    onMounted(async () => {
        await loadOrders();
    });
</script>

<template>
    <h1 class="font-bold text-3xl mb-4">Overview</h1>
    <div class="grid grid-cols-4 gap-8 mb-8">
        <div class="bg-lime-100 rounded-xl p-4 shadow-brutal shadow-lime-300 relative overflow-hidden">
            <h3 class="text-xl">Orders</h3>
            <ShoppingCartCheck02Icon class="absolute -left-2 -bottom-4 text-lime-500 opacity-30" size="100" />
            <p class="text-right font-bold text-6xl z-10">50</p>
        </div>
        <div class="bg-amber-100 rounded-xl p-4 shadow-brutal shadow-amber-300 relative overflow-hidden">
            <h3 class="text-xl">Products</h3>
            <DeliveryBox01Icon class="absolute -left-2 -bottom-4 text-amber-500 opacity-30" size="100" />
            <p class="text-right font-bold text-6xl">20</p>
        </div>
        <div class="bg-cyan-100 rounded-xl p-4 shadow-brutal shadow-cyan-300 relative overflow-hidden">
            <h3 class="text-xl">Income</h3>
            <MoneyReceive02Icon class="absolute -left-2 -bottom-4 text-cyan-500 opacity-30" size="100" />
            <p class="text-right font-bold text-6xl">2.35M</p>
        </div>
        <div class="bg-purple-100 rounded-xl p-4 shadow-brutal shadow-purple-300 relative overflow-hidden">
            <h3 class="text-xl">Customers</h3>
            <UserMultipleIcon class="absolute -left-2 -bottom-4 text-purple-500 opacity-30" size="100" />
            <p class="text-right font-bold text-6xl">1.2k</p>
        </div>
    </div>
    <h1 class="font-bold text-3xl mb-4">Orders</h1>
    <form @submit="search" class="flex gap-4 mb-4">
        <input type="date" placeholder="Date" v-model="queryDate" class="rounded-full bg-white border border-gray-300 p-2 text-black focus:outline-none focus:border-gray-800" />
        <input type="text" placeholder="Name" v-model="queryName" class="rounded-full bg-white border border-gray-300 p-2 text-black focus:outline-none focus:border-gray-800" />
        <input type="text" placeholder="Description" v-model="queryDescription" class="rounded-full bg-white border border-gray-300 p-2 text-black focus:outline-none focus:border-gray-800" />
        <button type="submit" class="bg-green-500 px-4 rounded-full text-white">Search</button>
    </form>
    <table class="table">
        <thead>
            <th class="text-left p-2">ID</th>
            <th class="text-left p-2">Name</th>
            <th class="text-left p-2">Date</th>
            <th class="text-right p-2">Price</th>
        </thead>
        <tbody>
            <tr v-for="order in orders.data" :key="order.id">
                <td class="p-2">
                    <span class="bg-black p-2 text-white block text-center">#{{ order.id }}</span>
                </td>
                <td class="p-2">
                    <router-link :to="`/orders/${order.id}`"><p class="font-bold">{{ order.name }}</p></router-link>
                    <p class="text-gray-500">{{ order.description }}</p>
                </td>
                <td class="p-2">{{ dayjs(order.date).format('DD/MM/YYYY') }}</td>
                <td class="p-2 font-bold text-right">
                    <CurrencyDisplay :value="order.products_sum_price" :is-plain="true" />
                </td>
            </tr> 
        </tbody>
    </table>
</template>