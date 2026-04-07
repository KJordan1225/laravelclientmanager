import { defineStore } from 'pinia';
import axios from 'axios';

export const useTaskStore = defineStore('taskStore', {
    state: () => ({
        errors: {},
    }),

    actions: {
        async createTask(payload) {
            this.errors = {};
            try {
                const { data } = await axios.post('/tasks', payload);
                return data;
            } catch (error) {
                if (error.response?.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
                throw error;
            }
        },

        async updateTask(id, payload) {
            this.errors = {};
            try {
                const { data } = await axios.put(`/tasks/${id}`, payload);
                return data;
            } catch (error) {
                if (error.response?.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
                throw error;
            }
        },
