<script setup lang="ts">
import CurrencyDisplay from '../../Components/CurrencyDisplay.vue';
import {  Delete01Icon } from 'hugeicons-vue';
import Product from '../../interfaces/Product';
import Order from '../../interfaces/Order';
import axios from 'axios';
import { toast } from 'vue3-toastify';
import Swal from 'sweetalert2';
import { removeProductOrder } from '../../api/orders';

interface Props {
    products: Product[],
    order?: Order,
    hasActionButtons?: boolean
}
const props = withDefaults(defineProps<Props>(), {
    hasActionButtons: false,
});
const emit = defineEmits<{
    (e: 'removed', id: number): void
}>();

const deleteProduct = async (product: Product) => {
    const result = await Swal.fire({
        text: `Are you sure to delete the product "${product.name}"?`,
        icon: 'warning',
        confirmButtonText: 'Sure',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showCancelButton: true,
        reverseButtons: true,
        confirmButtonColor: '#FF0000'
    });
    if (result.isConfirmed) {
        removeProductOrder(Number(props.order?.id), product.id).then(response => {
            toast.success(response.data.message);
            emit('removed', product.id);
        });
    }
}

</script>

<template>
    <div class="flex items-center">
        <table class="w-full">
            <thead class="sr-only">
                <tr>
                    <th class="text-left">Product</th>
                    <th class="text-right">Price</th>
                    <th v-if="hasActionButtons" class="w-12"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in products" :key="product.id" class="flex flex-col mb-4 sm:table-row">
                    <td class="py-2">
                        <div class="flex items-center gap-4 lg:gap-8">
                            <img :src="`https://picsum.photos/seed/${product.id}/80`" class="rounded-lg" :alt="product.name" />
                            <p class="uppercase">{{ product.name }}</p>
                        </div>
                    </td>
                    <td class="text-right"><currency-display :value="product.price" /></td>
                    <td v-if="hasActionButtons" class="text-right w-12">
                        <button class="rounded-md bg-red-300 p-2 hover:bg-red-500 hover:text-white transition-colors duration-300" @click="deleteProduct(product)">
                            <Delete01Icon :size="16"/>
                        </button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="flex flex-col mb-4 sm:table-row">
                    <th></th>
                    <td colspan="2" class="border-t pt-2 text-right font-bold text-xl"><currency-display :value="products?.reduce((prev, curr) => prev + curr.price, 0)" /></td>
                </tr>
            </tfoot>
        </table>
    </div>
</template>