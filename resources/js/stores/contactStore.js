import { defineStore } from 'pinia';
import axios from 'axios';

export const useContactStore = defineStore('contactStore', {
    state: () => ({
        errors: {},
    }),

    actions: {
        async createContact(payload) {
            this.errors = {};

            try {
                const { data } = await axios.post('/contacts', payload);
                return data;
            } catch (error) {
                if (error.response?.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
                throw error;
            }
        },

        async updateContact(id, payload) {
            this.errors = {};

            try {
                const { data } = await axios.put(`/contacts/${id}`, payload);
                return data;
            } catch (error) {
                if (error.response?.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
                throw error;
            }
        },

        async deleteContact(id) {
            await axios.delete(`/contacts/${id}`);
        },
    },
});
