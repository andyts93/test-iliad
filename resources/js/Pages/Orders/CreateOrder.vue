<script setup lang="ts">
import { BorderFullIcon, ArrowLeft01Icon } from 'hugeicons-vue';
import ProductsTable from '../../Components/Order/ProductsTable.vue';
import ProductAutocomplete from '../../Components/ProductAutocomplete.vue';
import Card from '../../Components/CardDefault.vue';
import { computed, Ref, ref, watch } from 'vue';
import { toast } from 'vue3-toastify';
import { createOrder } from '../../api/orders';
import { UnsavedOrder } from '../../interfaces/UnsavedOrder';
import { useRouter } from 'vue-router';
import SkeletonLoader from '../../Components/SkeletonLoader.vue';

const order: Ref<UnsavedOrder> = ref({ name: '', description: '', products: []});
const searchedProduct = ref();
const loading = ref(false);
const router = useRouter();

const save = e => {
    e.preventDefault();
    createOrder(order.value)
        .then(response => {
            toast.success(response.data.message);
            router.push(`/orders/${response.data.order.id}`)
        })
};

const removeProduct = id => {
    order.value.products.splice(order.value.products.findIndex(el => el.id === id));
}

watch(searchedProduct, newVal => {
    if (order.value.products.find(el => el.id === newVal.id)) {
        return toast.error('You can\'t add the same product twice');
    }
    order.value.products.push(newVal);
})

const isOrderValid = computed(() => Boolean(order.value.name && order.value.description && order.value.products.length > 0));
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
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-2 gap-4">
            <div class="flex items-center gap-2">
                <button class="rounded-md bg-gray-200 p-2 hover:bg-gray-500 hover:text-white transition-colors duration-300" @click="$router.back()" title="Go back">
                    <ArrowLeft01Icon />
                </button>
                <h1 class="font-bold text-xl lg:text-3xl">New order</h1>
            </div>
            <button type="button" class="btn btn-success" @click="save" :disabled="!isOrderValid">Save</button>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-8 mb-4">
            <Card title="Details">
                <template v-slot:icon>
                    <BorderFullIcon />
                </template>
                <template v-slot:body>
                    <div class="flex flex-col lg:flex-row justify-between mb-4 gap-2 lg:gap-4">
                        <span>Name</span>
                        <input aria-label="Name" type="text" v-model="order.name" class="cst-input" />
                    </div>
                    <div class="flex flex-col lg:flex-row justify-between mb-4 gap-2 lg:gap-4">
                        <span>Description</span>
                        <textarea aria-label="Description" v-model="order.description" class="rounded-2xl bg-white border border-gray-300 px-4 py-2 text-black focus:outline-none focus:border-gray-800 w-full xl:min-w-96" rows="5"></textarea>
                    </div>
                </template>
            </Card>
            <div>
                <div class="flex flex-col md:flex-row md:items-center gap-4 mb-4 bg-gray-50 rounded-2xl p-4">
                    <p class="font-semibold">Add a product</p>
                    <ProductAutocomplete v-model="searchedProduct" />
                </div>
                <ProductsTable :products="order.products" :has-action-buttons="true" @removed="removeProduct"/>
            </div>
        </div>        
    </div>
</template>