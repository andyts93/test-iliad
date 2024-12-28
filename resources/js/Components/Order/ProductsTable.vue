<script setup lang="ts">
import CurrencyDisplay from '../../Components/CurrencyDisplay.vue';
import {  Delete01Icon } from 'hugeicons-vue';
import Product from '../../interfaces/Product';

defineProps({
    products: Array<Product>,
    hasActionButtons: { type: Boolean, default: false },
});

</script>
<template>
    <table class="w-full">
            <thead>
                <tr>
                    <th class="text-left">Product</th>
                    <th class="text-right">Price</th>
                    <th v-if="hasActionButtons" class="w-12"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in products" :key="product.id">
                    <td class="py-2">
                        <div class="flex items-center gap-8">
                            <img :src="`https://picsum.photos/seed/${product.id}/80`" class="rounded-lg" />
                            <p class="uppercase">{{ product.name }}</p>
                        </div>
                    </td>
                    <td class="text-right"><currency-display :value="product.price" /></td>
                    <td v-if="hasActionButtons" class="text-right">
                        <button class="rounded-md bg-red-300 p-2 hover:bg-red-500 hover:text-white transition-colors duration-300">
                            <Delete01Icon :size="16"/>
                        </button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <td colspan="2" class="border-t pt-2 text-right font-bold text-xl"><currency-display :value="products?.reduce((prev, curr) => prev + curr.price, 0)" /></td>
                </tr>
            </tfoot>
        </table>
</template>