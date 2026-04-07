import { createRouter, createWebHistory } from 'vue-router';
import DashboardPage from '../pages/DashboardPage.vue';
import ClientsPage from '../pages/ClientsPage.vue';
import ClientFormPage from '../pages/ClientFormPage.vue';
import ClientDetailsPage from '../pages/ClientDetailsPage.vue';

const routes = [
    {
        path: '/',
        name: 'dashboard',
        component: DashboardPage,
    },
    {
        path: '/clients',
        name: 'clients.index',
        component: ClientsPage,
    },
    {
        path: '/clients/create',
        name: 'clients.create',
        component: ClientFormPage,
    },
    {
        path: '/clients/:id',
        name: 'clients.show',
        component: ClientDetailsPage,
        props: true,
    },
    {
        path: '/clients/:id/edit',
        name: 'clients.edit',
        component: ClientFormPage,
        props: true,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
