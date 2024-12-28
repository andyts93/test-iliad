<script setup>
import dayjs from 'dayjs';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { BorderFullIcon, Timer02Icon, Edit02Icon, Delete01Icon } from 'hugeicons-vue';
import ProductsTable from '../../Components/Order/ProductsTable.vue';

const route = useRoute();
const loading = ref(true);
const order = ref();

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
        </div>
        <div class="bg-gray-50 rounded-2xl p-4 mb-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-xl">Details</h3>
                <BorderFullIcon />
            </div>
            <table class="w-full">
                <tbody>
                    <tr>
                        <th class="text-left align-top pr-6 font-semibold">Name</th>
                        <td class="text-right font-light"><input type="text" v-model="order.name" /></td>
                    </tr>
                    <tr>
                        <th class="text-left align-top pr-6 font-semibold">Description</th>
                        <td class="text-right font-light italic">
                            <textarea v-model="order.description"></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <ProductsTable :products="order.products" :has-action-buttons="true"/>
    </div>
</template>