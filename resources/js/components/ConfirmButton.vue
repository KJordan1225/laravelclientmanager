<template>
    <button class="btn btn-danger" @click="confirmAction">
        <slot>Delete</slot>
    </button>
</template>

<script setup>
const emit = defineEmits(['confirm']);

function confirmAction() {
    if (window.confirm('Are you sure?')) {
        emit('confirm');
    }
}
</script>
