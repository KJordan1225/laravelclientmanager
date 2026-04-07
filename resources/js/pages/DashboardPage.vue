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

        <div class="grid grid-2" style="margin-top: 1.5rem;">
            <div class="card">
                <h3 style="margin-top: 0;">Recent Clients</h3>

                <div v-if="!stats.recent_clients || stats.recent_clients.length === 0" class="small-text">
                    No recent clients yet.
                </div>

                <div v-else class="summary-list">
                    <div
                        v-for="client in stats.recent_clients"
                        :key="client.id"
                        class="summary-item"
                    >
                        <strong>{{ client.company_name }}</strong>
                        <div class="small-text">Code: {{ client.client_code }}</div>
                        <div style="margin-top: 0.35rem;">
                            <StatusBadge :status="client.status" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3 style="margin-top: 0;">System Overview</h3>
                <p style="color: var(--muted); line-height: 1.6; margin-bottom: 0;">
                    This client management system gives you a clean workflow for account tracking,
                    contact management, note capture, and client-related task management. The
                    frontend is powered by Vue 3 and Pinia, while Laravel provides the API and
                    persistence layer.
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useClientStore } from '../stores/clientStore';
import PageHeader from '../components/PageHeader.vue';
import StatusBadge from '../components/StatusBadge.vue';

const clientStore = useClientStore();
const stats = computed(() => clientStore.stats);

onMounted(() => {
    clientStore.fetchStats();
});
</script>
