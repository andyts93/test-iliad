<template>
    <div class="flex h-screen overflow-hidden">
        <div class="bg-slate-800 mb-2 text-white p-4">
            <a href="/">LOGO</a>
            <ul>
                <li><router-link to="/">Orders</router-link></li>
            </ul>
        </div>
        <div class="container mx-auto bg-white rounded-3xl p-4 my-2">
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