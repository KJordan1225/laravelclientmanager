<template>
    <div class="page-header">
        <div>
            <h1>{{ title }}</h1>
            <p v-if="subtitle" style="margin: 0.35rem 0 0; color: var(--muted);">
                {{ subtitle }}
            </p>
        </div>

        <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
            <slot />
        </div>
    </div>
</template>

<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        default: '',
    },
});
</script>
