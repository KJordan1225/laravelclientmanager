<template>
    <div class="container">
        <PageHeader
            title="Dashboard"
            subtitle="Quick overview of clients and open work."
        >
            <router-link to="/clients/create" class="btn btn-primary">
                Add Client
            </router-link>
        </PageHeader>

        <div class="grid grid-4">
            <div class="card stat-card">
                <h3>Total Clients</h3>
                <p>{{ stats.total_clients }}</p>
            </div>

            <div class="card stat-card">
                <h3>Active Clients</h3>
                <p>{{ stats.active_clients }}</p>
            </div>

            <div class="card stat-card">
                <h3>Leads</h3>
                <p>{{ stats.lead_clients }}</p>
            </div>

            <div class="card stat-card">
                <h3>Open Tasks</h3>
                <p>{{ stats.open_tasks }}</p>
            </div>
        </div>

        <div class="card" style="margin-top: 1.5rem;">
            <h3 style="margin-top: 0;">Welcome</h3>
            <p style="color: var(--muted); margin-bottom: 0;">
                This client management system lets you manage client records, contacts,
                notes, and tasks from one clean interface built with Vue 3 and Pinia.
            </p>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useClientStore } from '../stores/clientStore';
import PageHeader from '../components/PageHeader.vue';

const clientStore = useClientStore();
const stats = computed(() => clientStore.stats);

onMounted(() => {
    clientStore.fetchStats();
});
</script>
