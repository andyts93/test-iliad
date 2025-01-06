import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: '/',
        component: () => import('./Pages/Orders/IndexOrder.vue'),
    },
    {
        path: '/orders/:id',
        component: () => import('./Pages/Orders/ShowOrder.vue'),
    },
    {
        path: '/orders/create',
        component: () => import('./Pages/Orders/CreateOrder.vue'),
    },
    {
        path: '/orders/:id/edit',
        component: () => import('./Pages/Orders/EditOrder.vue'),
    },
    {
        path: '/not-found',
        component: () => import('./Pages/NotFound.vue'),
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;