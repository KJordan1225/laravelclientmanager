<template>
    <div class="container">
        <PageHeader
            title="Clients"
            subtitle="Manage all client records."
        >
            <router-link to="/clients/create" class="btn btn-primary">
                New Client
            </router-link>
        </PageHeader>

        <div class="card" style="margin-bottom: 1rem;">
            <div class="grid grid-3">
                <div class="form-group" style="margin-bottom: 0;">
                    <label>Search</label>
                    <input
                        v-model="filters.search"
                        class="form-control"
                        type="text"
                        placeholder="Search company, code, email..."
                    >
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label>Status</label>
                    <select v-model="filters.status" class="form-control">
                        <option value="">All statuses</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="lead">Lead</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0; justify-content: end;">
                    <label>&nbsp;</label>
                    <button class="btn btn-secondary" @click="resetFilters">
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <div class="card">
            <div v-if="loading" style="padding: 1rem;">Loading clients...</div>

            <template v-else>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Code</th>
                                <th>Industry</th>
                                <th>Status</th>
                                <th>Counts</th>
                                <th>Email</th>
                                <th style="width: 220px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="clients.length === 0">
                                <td colspan="7" style="text-align: center; color: var(--muted);">
                                    No clients found.
                                </td>
                            </tr>

                            <tr v-for="client in clients" :key="client.id">
                                <td>{{ client.company_name }}</td>
                                <td>{{ client.client_code }}</td>
                                <td>{{ client.industry || '—' }}</td>
                                <td>
                                    <StatusBadge :status="client.status" />
                                </td>
                                <td>
                                    <div class="small-text">
                                        Contacts: {{ client.contacts_count ?? 0 }}
                                    </div>
                                    <div class="small-text">
                                        Notes: {{ client.notes_count ?? 0 }}
                                    </div>
                                    <div class="small-text">
                                        Tasks: {{ client.tasks_count ?? 0 }}
                                    </div>
                                </td>
                                <td>{{ client.email || '—' }}</td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                        <router-link
                                            :to="`/clients/${client.id}`"
                                            class="btn btn-secondary"
                                        >
                                            View
                                        </router-link>

                                        <router-link
                                            :to="`/clients/${client.id}/edit`"
                                            class="btn btn-primary"
                                        >
                                            Edit
                                        </router-link>

                                        <ConfirmButton @confirm="removeClient(client.id)">
                                            Delete
                                        </ConfirmButton>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <PaginationControls
                    :meta="meta"
                    @change="changePage"
                />
            </template>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, reactive, watch } from 'vue';
import { useClientStore } from '../stores/clientStore';
import PageHeader from '../components/PageHeader.vue';
import StatusBadge from '../components/StatusBadge.vue';
import ConfirmButton from '../components/ConfirmButton.vue';
import PaginationControls from '../components/PaginationControls.vue';

const clientStore = useClientStore();

const filters = reactive({
    search: '',
    status: '',
    page: 1,
});

const clients = computed(() => clientStore.clients);
const loading = computed(() => clientStore.loading);
const meta = computed(() => clientStore.meta);

async function loadClients() {
    await clientStore.fetchClients({
        search: filters.search,
        status: filters.status,
        page: filters.page,
    });
}

async function removeClient(id) {
    await clientStore.deleteClient(id);
    await loadClients();
}

function resetFilters() {
    filters.search = '';
    filters.status = '';
    filters.page = 1;
    loadClients();
}

function changePage(page) {
    filters.page = page;
    loadClients();
}

let timeoutId = null;

watch(
    () => [filters.search, filters.status],
    () => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            filters.page = 1;
            loadClients();
        }, 300);
    }
);

onMounted(() => {
    loadClients();
});
</script>
