<template>
    <div class="app-layout">
        <ToastContainer />

        <aside class="sidebar" :class="{ 'sidebar-open': mobileMenuOpen }">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                <h2 style="margin-bottom: 0;">Client Manager</h2>

                <button class="btn btn-secondary mobile-only" @click="mobileMenuOpen = false">
                    Close
                </button>
            </div>

            <nav>
                <router-link to="/" @click="mobileMenuOpen = false">Dashboard</router-link>
                <router-link to="/clients" @click="mobileMenuOpen = false">Clients</router-link>
                <router-link to="/clients/create" @click="mobileMenuOpen = false">Add Client</router-link>
            </nav>
        </aside>

        <main class="main-content">
            <div class="mobile-topbar">
                <button class="btn btn-primary mobile-only" @click="mobileMenuOpen = true">
                    Menu
                </button>
            </div>

            <router-view />
        </main>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import ToastContainer from './components/ToastContainer.vue';

const mobileMenuOpen = ref(false);
</script>
