<template>
    <span :class="badgeClass" class="badge">
        {{ label }}
    </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    status: {
        type: String,
        required: true,
    },
});

const badgeClass = computed(() => {
    if (props.status === 'active') return 'badge badge-active';
    if (props.status === 'inactive') return 'badge badge-inactive';
    if (props.status === 'lead') return 'badge badge-lead';
    if (props.status === 'completed') return 'badge badge-active';
    if (props.status === 'in_progress') return 'badge badge-lead';
    return 'badge badge-inactive';
});

const label = computed(() => {
    return props.status.replaceAll('_', ' ');
});
</script>
