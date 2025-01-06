<script setup lang="ts">
import axios from 'axios';
import { computed, ModelRef, Ref, ref, watch } from 'vue';
import Product from '../interfaces/Product';
import { Loading02Icon } from 'hugeicons-vue';
import CurrencyDisplay from './CurrencyDisplay.vue';

const queryTerm = ref('');
const products: Ref<Product[]> = ref([]);
const loading = ref(false);
const error = ref();
const resultsOpen = ref(false);
const selectedProduct: ModelRef<Product | undefined> = defineModel();

const searchProduct = async () => {
    if (queryTerm.value === '') {
        products.value = [];
        return;
    }

    try {
        loading.value = true;
        error.value = null;

        const response = await axios.get('/api/v1/products', {
            params: {
                q: queryTerm.value,
            }
        });

        products.value = response.data;
        resultsOpen.value = true;
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    } catch (err) {
        error.value = 'Something went wrong';
    } finally {
        loading.value = false;
    }
};

const selectProduct = (product: Product) => {
    selectedProduct.value = product;
    queryTerm.value = '';
    resultsOpen.value = false;
}

// Debounce della ricerca
let timeout: NodeJS.Timeout|undefined = undefined;
watch(queryTerm, () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        searchProduct();
    }, 300);
});

const isSearching = computed(() => loading.value);
const hasError = computed(() => error.value);

</script>
<template>
    <div class="relative inline-block">
        <div class="relative">
            <input type="text" aria-label="Search product" v-model="queryTerm" class="h-10 rounded-full bg-white border border-gray-300 px-4 py-2 text-black focus:outline-none focus:border-gray-800" placeholder="Search product" @focus="resultsOpen = true"/>
            <Loading02Icon v-if="isSearching" class="absolute top-2 right-2 animate-spin-slow text-gray-400" />
        </div>
        <p v-if="hasError" class="text-red-500">{{ error }}</p>
        <ul v-if="resultsOpen && products.length > 0" class="absolute right-0 top-10 bg-gray-50 shadow-md min-w-full mt-1 rounded-xl overflow-hidden">
            <li v-for="product in products" :key="product.id" class="px-4 py-0.5 flex justify-between gap-8 items-center cursor-pointer hover:bg-gray-300" @click="selectProduct(product)">
                <span class="">{{ product.name }}</span>
                <span class="text-xs text-gray-500"><CurrencyDisplay :value="product.price" /></span>
            </li>
        </ul>
    </div>
</template>