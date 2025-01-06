<script setup lang="ts">
import { onMounted, Ref, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { BorderFullIcon } from 'hugeicons-vue';
import ProductsTable from '../../Components/Order/ProductsTable.vue';
import { toast } from 'vue3-toastify';
import ProductAutocomplete from '../../Components/ProductAutocomplete.vue';
import History from '../../Components/Order/OrderHistory.vue';
import Card from '../../Components/CardDefault.vue';
import { addProductOrder, getOrder, updateOrder } from '../../api/orders';
import Order from '../../interfaces/Order';
import SkeletonLoader from '../../Components/SkeletonLoader.vue';
import OrderHeader from '../../Components/Order/OrderHeader.vue';

const route = useRoute();
const loading = ref(true);
const order: Ref<Order | undefined> = ref();
const searchedProduct = ref();

const loadOrder = () => {
    loading.value = true;
    getOrder(Number(route.params.id))
        .then(response => order.value = response.data)
        .finally(() => loading.value = false);
}

const save = async (e) => {
    e.preventDefault();
    loading.value = true;
    updateOrder(Number(route.params.id), {
        name: order.value?.name,
        description: order.value?.description,
    })
        .then(response => toast.success(response.data.message))
        .finally(() => loading.value = false);
}

onMounted(() => {
    loadOrder();
});

watch(searchedProduct, newVal => {
    addProductOrder(Number(route.params.id), newVal.id).then(async response => {
        toast.success(response.data.message);
        loadOrder();
    });
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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-8 mb-4">
            <Card title="Details">
                <template v-slot:icon>
                    <BorderFullIcon />
                </template>
                <template v-slot:body>
                    <form @submit="save">
                        <div class="flex flex-col lg:flex-row justify-between mb-4 gap-2">
                            <span>Name</span>
                            <input aria-label="Name" type="text" v-model="order.name" class="rounded-full bg-white border border-gray-300 px-4 py-2 text-black focus:outline-none focus:border-gray-800" />
                        </div>
                        <div class="flex flex-col lg:flex-row justify-between mb-4 gap-2">
                            <span>Description</span>
                            <textarea aria-label="Description" v-model="order.description" class="rounded-2xl bg-white border border-gray-300 px-4 py-2 text-black focus:outline-none focus:border-gray-800 w-full xl:min-w-96" rows="5"></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-green-500 px-4 rounded-full text-white py-2 hover:bg-green-400 transition-colors duration-300">Save</button>
                        </div>
                    </form>
                </template>
            </Card>
            <History :order="order" />
        </div>
        <div class="flex flex-col md:flex-row md:items-center gap-4 mb-4 bg-gray-50 rounded-2xl p-4">
            <p class="font-semibold">Add a product</p>
            <ProductAutocomplete v-model="searchedProduct" />
        </div>
        <ProductsTable :products="order.products" :has-action-buttons="true" :order="order" @removed="loadOrder"/>
    </div>
</template>