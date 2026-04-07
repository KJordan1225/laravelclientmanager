import { defineStore } from 'pinia';
import axios from 'axios';

export const useClientStore = defineStore('clientStore', {
    state: () => ({
        clients: [],
        client: null,
        stats: {
            total_clients: 0,
            active_clients: 0,
            lead_clients: 0,
            open_tasks: 0,
            recent_clients: [],
        },
        meta: null,
        links: null,
        loading: false,
        errors: {},
    }),

    actions: {
        async fetchStats() {
            const { data } = await axios.get('/dashboard/stats');
            this.stats = data;
        },

        async fetchClients(params = {}) {
            this.loading = true;

            try {
                const { data } = await axios.get('/clients', { params });
                this.clients = data.data ?? [];
                this.meta = data.meta ?? null;
                this.links = data.links ?? null;
            } finally {
                this.loading = false;
            }
        },

        async fetchClient(id) {
            this.loading = true;

            try {
                const { data } = await axios.get(`/clients/${id}`);
                this.client = data.data ?? data;
                return this.client;
            } finally {
                this.loading = false;
            }
        },

        async createClient(payload) {
            this.errors = {};

            try {
                const { data } = await axios.post('/clients', payload);
                return data;
            } catch (error) {
                if (error.response?.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
                throw error;
            }
        },

        async updateClient(id, payload) {
            this.errors = {};

            try {
                const { data } = await axios.put(`/clients/${id}`, payload);
                return data;
            } catch (error) {
                if (error.response?.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
                throw error;
            }
        },

        async deleteClient(id) {
            await axios.delete(`/clients/${id}`);
            this.clients = this.clients.filter(client => client.id !== id);
        },
    },
});
