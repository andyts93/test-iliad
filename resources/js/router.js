import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: '/',
        component: () => import('./Pages/Orders/Index.vue'),
    },
    {
        path: '/orders/:id',
        component: () => import('./Pages/Orders/Show.vue'),
    },
    {
        path: '/orders/:id/edit',
        component: () => import('./Pages/Orders/Edit.vue'),
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;