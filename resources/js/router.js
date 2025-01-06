import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: '/',
        component: () => import('./Pages/Orders/IndexOrder.vue'),
        name: 'Orders',
    },
    {
        path: '/orders/:id',
        component: () => import('./Pages/Orders/ShowOrder.vue'),
        name: 'Order :id details',
    },
    {
        path: '/orders/create',
        component: () => import('./Pages/Orders/CreateOrder.vue'),
        name: 'Create order',
    },
    {
        path: '/orders/:id/edit',
        component: () => import('./Pages/Orders/EditOrder.vue'),
        name: 'Edit order :id',
    },
    {
        path: '/not-found',
        component: () => import('./Pages/NotFound.vue'),
        name: 'Not found'
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    let title = "Iliad Test | " + to.name;
    if (typeof title === 'string' && /:[a-zA-Z]+/.test(title)) {
        title = title.replace(/:([a-zA-Z]+)/g, (_, paramName) => {
            return to.params[paramName] || `:${paramName}`;
        });
    }
    document.title = title;
    next();
})

export default router;