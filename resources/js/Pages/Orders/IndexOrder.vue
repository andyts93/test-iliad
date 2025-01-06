<script setup lang="ts">
    import { ref, onMounted, Ref } from 'vue';
    import dayjs from 'dayjs';
    import CurrencyDisplay from '../../Components/CurrencyDisplay.vue';
    import { ShoppingCartCheck02Icon, DeliveryBox01Icon, MoneyReceive02Icon, UserMultipleIcon, Add02Icon } from 'hugeicons-vue';
    import StatBox from '../../Components/StatBox.vue';
    import { getOrders } from '../../api/orders';
    import SkeletonLoader from '../../Components/SkeletonLoader.vue';
    import Order from '../../interfaces/Order';
    import PaginatedResults from '../../interfaces/PaginatedResults';
import PaginatorBar from '../../Components/PaginatorBar.vue';

    const orders: Ref<PaginatedResults<Order> | undefined> = ref();
    const queryDate = ref();
    const queryName = ref();
    const queryDescription = ref();
    const loading = ref(true);

    const loadOrders = async (page: number) => {
        loading.value = true;
        getOrders({
            name: queryName.value,
            description: queryDescription.value,
            date: queryDate.value,
            page,
        })
            .then(response => orders.value = response.data)
            .finally(() => loading.value = false);
    }

    const search = async (e) => {
        e.preventDefault();
        loadOrders(1);
    }

    const reset = () => {
        queryDate.value = null;
        queryName.value = null;
        queryDescription.value = null;
        loadOrders(1);
    }

    const onPageChange = (page: number) => {
        loadOrders(page);
    }

    onMounted(() => {
        loadOrders(1);
    });
</script>

<template>
    <h1 class="font-bold text-xl md:text-3xl mb-2 md:mb-4">Overview</h1>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 md:gap-8 mb-4 md:mb-8">
        <StatBox title="Orders" value="50" bg-class="bg-lime-100" shadow-class="shadow-lime-300" icon-class="text-lime-500">
            <ShoppingCartCheck02Icon :size="100" />
        </StatBox>
        <StatBox title="Products" value="50" bg-class="bg-amber-100" shadow-class="shadow-amber-300" icon-class="text-amber-500">
            <DeliveryBox01Icon :size="100" />
        </StatBox>
        <StatBox title="Income" value="2.35M" bg-class="bg-cyan-100" shadow-class="shadow-cyan-300" icon-class="text-cyan-500">
            <MoneyReceive02Icon :size="100" />
        </StatBox>
        <StatBox title="Clients" value="1.2K" bg-class="bg-purple-100" shadow-class="shadow-purple-300" icon-class="text-purple-500">
            <UserMultipleIcon :size="100" />
        </StatBox>
    </div>
    <h1 class="font-bold text-xl md:text-3xl mb-2 md:mb-4">Orders</h1>
    <div class="flex flex-col lg:flex-row justify-between lg:items-center mb-4 gap-1 lg:gap-4">
        <form @submit="search" class="flex flex-col lg:flex-row gap-1 lg:gap-4">
            <input type="date" aria-label="Date" placeholder="Date" v-model="queryDate" class="cst-input" />
            <input type="text" aria-label="Name" placeholder="Name" v-model="queryName" class="cst-input" />
            <input type="text" aria-label="Description" placeholder="Description" v-model="queryDescription" class="cst-input" />
            <button type="submit" class="btn btn-success" :disabled="loading">Search</button>
            <button type="button" class="btn btn-danger" @click="reset" :disabled="loading">Reset</button>
        </form>
        <router-link to="/orders/create" class="btn btn-success flex items-center justify-center gap-2" title="Create new order"><Add02Icon /> <span class="lg:hidden">Create new</span></router-link>
    </div>
    <table class="w-full text-sm md:text-base">
        <thead>
            <tr class="sr-only md:not-sr-only">
                <th class="text-left p-2">ID</th>
                <th class="text-left p-2">Name</th>
                <th class="text-left p-2">Date</th>
                <th class="text-right p-2">Price</th>
            </tr>
        </thead>
        <tbody>
            <template v-if="loading">
                <tr v-for="n in 15" :key="n">
                    <td class="p-2">
                        <SkeletonLoader class="h-5" />
                    </td>
                    <td class="p-2">
                        <SkeletonLoader class="h-5" />
                    </td>
                    <td class="p-2">
                        <SkeletonLoader class="h-5" />
                    </td>
                    <td class="p-2">
                        <SkeletonLoader class="h-5" />
                    </td>
                </tr>
            </template>
            <tr v-else-if="orders" v-for="order in orders.data" :key="order.id" class="flex flex-col mb-4 sm:table-row">
                <td class="p-0 md:p-2">
                    <span class="bg-black p-2 text-white block text-center">#{{ order.id }}</span>
                </td>
                <td class="p-0 md:p-2">
                    <router-link :to="`/orders/${order.id}`"><p class="font-bold">{{ order.name }}</p></router-link>
                    <p class="text-gray-500">{{ order.description }}</p>
                </td>
                <td class="p-0 md:p-2">{{ dayjs(order.date).format('DD/MM/YYYY') }}</td>
                <td class="p-0 md:p-2 font-bold text-right">
                    <CurrencyDisplay :value="order.products_sum_price / 100" :is-plain="true" />
                </td>
            </tr> 
        </tbody>
    </table>
    <PaginatorBar v-if="orders" :data="orders" @pageChange="onPageChange"/>
</template>