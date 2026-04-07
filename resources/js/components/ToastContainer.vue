<template>
    <div class="toast-container">
        <div
            v-for="toast in toasts"
            :key="toast.id"
            class="toast-item"
            :class="`toast-${toast.type}`"
        >
            <span>{{ toast.message }}</span>
            <button class="toast-close" @click="removeToast(toast.id)">×</button>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { useUiStore } from '../stores/uiStore';

const uiStore = useUiStore();
const toasts = computed(() => uiStore.toasts);

function removeToast(id) {
    uiStore.removeToast(id);
}
</script>
