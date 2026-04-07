<template>
    <div class="container">
        <PageHeader
            :title="isEdit ? 'Edit Client' : 'Create Client'"
            :subtitle="isEdit ? 'Update client account details.' : 'Add a new client to your system.'"
        >
            <router-link to="/clients" class="btn btn-secondary">
                Back to Clients
            </router-link>
        </PageHeader>

        <div class="card">
            <form @submit.prevent="submitForm">
                <div class="grid grid-2">
                    <div class="form-group">
                        <label>Company Name</label>
                        <input v-model="form.company_name" class="form-control" type="text">
                        <small v-if="errors.company_name" style="color: var(--danger);">
                            {{ errors.company_name[0] }}
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Client Code</label>
                        <input v-model="form.client_code" class="form-control" type="text">
                        <small v-if="errors.client_code" style="color: var(--danger);">
                            {{ errors.client_code[0] }}
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Industry</label>
                        <input v-model="form.industry" class="form-control" type="text">
                    </div>

                    <div class="form-group">
                        <label>Website</label>
                        <input v-model="form.website" class="form-control" type="url">
                        <small v-if="errors.website" style="color: var(--danger);">
                            {{ errors.website[0] }}
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input v-model="form.email" class="form-control" type="email">
                        <small v-if="errors.email" style="color: var(--danger);">
                            {{ errors.email[0] }}
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Phone</label>
                        <input v-model="form.phone" class="form-control" type="text">
                    </div>

                    <div class="form-group">
                        <label>City</label>
                        <input v-model="form.city" class="form-control" type="text">
                    </div>

                    <div class="form-group">
                        <label>State</label>
                        <input v-model="form.state" class="form-control" type="text">
                    </div>

                    <div class="form-group">
                        <label>Postal Code</label>
                        <input v-model="form.postal_code" class="form-control" type="text">
                    </div>

                    <div class="form-group">
                        <label>Country</label>
                        <input v-model="form.country" class="form-control" type="text">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select v-model="form.status" class="form-control">
                            <option value="lead">Lead</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <small v-if="errors.status" style="color: var(--danger);">
                            {{ errors.status[0] }}
                        </small>
                    </div>

                    <div class="form-group">
                        <label>Onboarded Date</label>
                        <input v-model="form.onboarded_at" class="form-control" type="date">
                    </div>

                    <div class="form-group">
                        <label>Hourly Rate</label>
                        <input v-model="form.hourly_rate" class="form-control" type="number" step="0.01">
                    </div>

                    <div class="form-group">
                        <label>Credit Limit</label>
                        <input v-model="form.credit_limit" class="form-control" type="number" step="0.01">
                    </div>
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <textarea v-model="form.address" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea v-model="form.description" class="form-control"></textarea>
                </div>

                <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                    <button class="btn btn-primary" type="submit" :disabled="submitting">
                        {{ submitting ? 'Saving...' : (isEdit ? 'Update Client' : 'Create Client') }}
                    </button>

                    <router-link to="/clients" class="btn btn-secondary">
                        Cancel
                    </router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useClientStore } from '../stores/clientStore';
import PageHeader from '../components/PageHeader.vue';

const props = defineProps({
    id: {
        type: [String, Number],
        default: null,
    },
});

const router = useRouter();
const clientStore = useClientStore();
const submitting = ref(false);

const form = reactive({
    company_name: '',
    client_code: '',
    industry: '',
    website: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    state: '',
    postal_code: '',
    country: '',
    status: 'lead',
    hourly_rate: '',
    credit_limit: '',
    onboarded_at: '',
    description: '',
});

const isEdit = computed(() => !!props.id);
const errors = computed(() => clientStore.errors);

function fillForm(client) {
    form.company_name = client.company_name || '';
    form.client_code = client.client_code || '';
    form.industry = client.industry || '';
    form.website = client.website || '';
    form.email = client.email || '';
    form.phone = client.phone || '';
    form.address = client.address || '';
    form.city = client.city || '';
    form.state = client.state || '';
    form.postal_code = client.postal_code || '';
    form.country = client.country || '';
    form.status = client.status || 'lead';
    form.hourly_rate = client.hourly_rate || '';
    form.credit_limit = client.credit_limit || '';
    form.onboarded_at = client.onboarded_at || '';
    form.description = client.description || '';
}

async function submitForm() {
    submitting.value = true;

    try {
        if (isEdit.value) {
            await clientStore.updateClient(props.id, { ...form });
            window.alert('Client updated successfully.');
        } else {
            await clientStore.createClient({ ...form });
            window.alert('Client created successfully.');
        }

        router.push('/clients');
    } catch (error) {
        console.error(error);
    } finally {
        submitting.value = false;
    }
}

onMounted(async () => {
    if (isEdit.value) {
        const client = await clientStore.fetchClient(props.id);
        fillForm(client);
    }
});
</script>
