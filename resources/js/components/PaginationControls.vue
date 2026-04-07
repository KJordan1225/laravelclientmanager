<template>
    <div
        v-if="meta && meta.last_page > 1"
        style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; margin-top: 1rem;"
    >
        <div style="color: var(--muted);">
            Showing {{ meta.from || 0 }} to {{ meta.to || 0 }} of {{ meta.total || 0 }}
        </div>

        <div style="display: flex; gap: 0.5rem;">
            <button
                class="btn btn-secondary"
                :disabled="meta.current_page <= 1"
                @click="$emit('change', meta.current_page - 1)"
            >
                Previous
            </button>

            <button
                class="btn btn-secondary"
                :disabled="meta.current_page >= meta.last_page"
                @click="$emit('change', meta.current_page + 1)"
            >
                Next
            </button>
        </div>
    </div>
</template>

<script setup>
defineProps({
    meta: {
        type: Object,
        default: null,
    },
});

defineEmits(['change']);
</script>
