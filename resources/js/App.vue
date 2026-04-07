<template>
    <div class="app-layout">
        <aside class="sidebar">
            <h2>Client Manager</h2>

            <nav>
                <router-link to="/">Dashboard</router-link>
                <router-link to="/clients">Clients</router-link>
                <router-link to="/clients/create">Add Client</router-link>
            </nav>
        </aside>

        <main class="main-content">
            <router-view />
        </main>
    </div>
</template>

<script setup>
</script>
