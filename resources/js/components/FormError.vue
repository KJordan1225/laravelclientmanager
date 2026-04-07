<template>
    <small v-if="message" style="color: var(--danger); display: block; margin-top: 0.25rem;">
        {{ message }}
    </small>
</template>

<script setup>
defineProps({
    message: {
        type: String,
        default: '',
    },
});
</script>
