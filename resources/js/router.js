import nprogress from 'nprogress';
import nProgress from 'nprogress';
import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: '/',
        component: () => import('./Pages/HomeRoute.vue'),
    },
    {
        path: '/orders/:id',
        component: () => import('./Pages/OrderDetail.vue'),
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});
nProgress.configure({ speed: 5000 })

router.beforeResolve((to, from, next) => {
    if (to.name) {
        nProgress.start();
    }
    next();
});

router.afterEach((to, from) => {
    nProgress.done();
});

export default router;