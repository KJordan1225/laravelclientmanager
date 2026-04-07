import { defineStore } from 'pinia';

let toastId = 1;

export const useUiStore = defineStore('uiStore', {
    state: () => ({
        toasts: [],
    }),

    actions: {
        toast(message, type = 'success', timeout = 3000) {
            const id = toastId++;

            this.toasts.push({
                id,
                message,
                type,
            });

            setTimeout(() => {
                this.removeToast(id);
            }, timeout);
        },

        removeToast(id) {
            this.toasts = this.toasts.filter(toast => toast.id !== id);
        },
    },
});
