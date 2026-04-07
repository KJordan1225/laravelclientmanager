import { defineStore } from 'pinia';
import axios from 'axios';

export const useNoteStore = defineStore('noteStore', {
    state: () => ({
        errors: {},
    }),

    actions: {
        async createNote(payload) {
            this.errors = {};

            try {
                const { data } = await axios.post('/notes', payload);
                return data;
            } catch (error) {
                if (error.response?.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
                throw error;
            }
        },

        async updateNote(id, payload) {
            this.errors = {};

            try {
                const { data } = await axios.put(`/notes/${id}`, payload);
                return data;
            } catch (error) {
                if (error.response?.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
                throw error;
            }
        },

        async deleteNote(id) {
            await axios.delete(`/notes/${id}`);
        },
    },
});
