<script setup lang="ts">
import { DeliveryBox01Icon } from 'hugeicons-vue';
</script>
<template>
    <div class="flex h-screen overflow-hidden">
        <div class="bg-slate-800 mb-2 text-white w-16 md:w-32 lg:w-64 py-4">
            <svg class="fill-white w-12 md:w-16 mx-auto" viewBox="0 0 24 24" id="magicoon-Filled" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <defs></defs> <title>store</title> <g id="store-Filled"> <path id="store-Filled-2" data-name="store-Filled" class="cls-1" d="M20.5,18A3.5,3.5,0,0,1,17,21.5H14.53V19a2.5,2.5,0,0,0-5,0v2.5H7A3.5,3.5,0,0,1,3.5,18V13.35A5.634,5.634,0,0,0,5.99,14,4.409,4.409,0,0,0,9,12.78a4.3,4.3,0,0,0,6,0A4.409,4.409,0,0,0,18.01,14a5.634,5.634,0,0,0,2.49-.65Zm.974-9.158L20.386,5.577A4.494,4.494,0,0,0,16.117,2.5H7.883A4.494,4.494,0,0,0,3.614,5.577L2.526,8.842A.5.5,0,0,0,2.5,9a3.5,3.5,0,0,0,3.49,3.5A3.853,3.853,0,0,0,9,11.034a3.809,3.809,0,0,0,6.006,0A3.854,3.854,0,0,0,18.01,12.5,3.5,3.5,0,0,0,21.5,9,.5.5,0,0,0,21.474,8.842Z"></path> </g> </g></svg>
            <ul class="mt-4">
                <li>
                    <router-link to="/" class="flex items-center justify-center md:justify-start gap-2 px-4 py-2 hover:bg-slate-700" title="Orders">
                        <DeliveryBox01Icon />
                        <span class="hidden md:block">Orders</span>
                    </router-link>
                </li>
            </ul>
        </div>
        <div class="bg-white rounded-3xl p-4 my-2 mr-2 flex-1">
            <div class="overflow-y-auto h-full p-2">
                <router-view v-slot="{ Component, route }">
                    <div :key="route.name">
                        <Component :is="Component" />
                    </div>
                </router-view>
            </div>
        </div>
    </div>
</template>