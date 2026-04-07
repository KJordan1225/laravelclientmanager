<template>
    <div class="container" v-if="client">
        <PageHeader
            :title="client.company_name"
            subtitle="Client profile, contacts, notes, and tasks."
        >
            <router-link :to="`/clients/${client.id}/edit`" class="btn btn-primary">
                Edit Client
            </router-link>

            <router-link to="/clients" class="btn btn-secondary">
                Back
            </router-link>
        </PageHeader>

        <div class="grid grid-3" style="margin-bottom: 1.5rem;">
            <div class="card">
                <h3 style="margin-top: 0;">Client Details</h3>
                <p><strong>Code:</strong> {{ client.client_code }}</p>
                <p><strong>Status:</strong> <StatusBadge :status="client.status" /></p>
                <p><strong>Industry:</strong> {{ client.industry || '—' }}</p>
                <p><strong>Email:</strong> {{ client.email || '—' }}</p>
                <p><strong>Phone:</strong> {{ client.phone || '—' }}</p>
                <p><strong>Website:</strong> {{ client.website || '—' }}</p>
                <p style="margin-bottom: 0;"><strong>Location:</strong> {{ fullLocation }}</p>
            </div>

            <div class="card">
                <h3 style="margin-top: 0;">Commercial Terms</h3>
                <p><strong>Hourly Rate:</strong> {{ money(client.hourly_rate) }}</p>
                <p><strong>Credit Limit:</strong> {{ money(client.credit_limit) }}</p>
                <p><strong>Onboarded:</strong> {{ readableDate(client.onboarded_at) }}</p>
                <p style="margin-bottom: 0;"><strong>Address:</strong> {{ client.address || '—' }}</p>
            </div>

            <div class="card">
                <h3 style="margin-top: 0;">Description</h3>
                <p style="margin-bottom: 0; white-space: pre-line;">
                    {{ client.description || 'No description yet.' }}
                </p>
            </div>
        </div>

        <div class="grid grid-2" style="align-items: start;">
            <div class="grid">
                <div class="card">
                    <div style="display: flex; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                        <h3 style="margin-top: 0;">Contacts</h3>
                        <button class="btn btn-primary" @click="startNewContact">
                            {{ showContactForm ? 'Close Form' : 'Add Contact' }}
                        </button>
                    </div>

                    <form v-if="showContactForm" @submit.prevent="submitContact" style="margin-bottom: 1rem;">
                        <div class="grid grid-2">
                            <div class="form-group">
                                <label>First Name</label>
                                <input v-model="contactForm.first_name" class="form-control" type="text">
                            </div>

                            <div class="form-group">
                                <label>Last Name</label>
                                <input v-model="contactForm.last_name" class="form-control" type="text">
                            </div>

                            <div class="form-group">
                                <label>Job Title</label>
                                <input v-model="contactForm.job_title" class="form-control" type="text">
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input v-model="contactForm.email" class="form-control" type="email">
                            </div>

                            <div class="form-group">
                                <label>Phone</label>
                                <input v-model="contactForm.phone" class="form-control" type="text">
                            </div>

                            <div class="form-group">
                                <label>Mobile</label>
                                <input v-model="contactForm.mobile" class="form-control" type="text">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                <input v-model="contactForm.is_primary" type="checkbox">
                                Primary contact
                            </label>
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea v-model="contactForm.notes" class="form-control"></textarea>
                        </div>

                        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                            <button class="btn btn-primary" type="submit">
                                {{ editingContactId ? 'Update Contact' : 'Save Contact' }}
                            </button>

                            <button class="btn btn-secondary" type="button" @click="resetContactForm">
                                Cancel
                            </button>
                        </div>
                    </form>

                    <div v-if="client.contacts.length === 0" style="color: var(--muted);">
                        No contacts yet.
                    </div>

                    <div
                        v-for="contact in client.contacts"
                        :key="contact.id"
                        class="card"
                        style="margin-top: 0.75rem; box-shadow: none;"
                    >
                        <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: start;">
                            <div>
                                <strong>{{ contact.full_name }}</strong>
                                <span v-if="contact.is_primary" class="badge badge-active" style="margin-left: 0.5rem;">
                                    Primary
                                </span>
                                <div style="color: var(--muted); margin-top: 0.35rem;">{{ contact.job_title || '—' }}</div>
                                <div style="margin-top: 0.35rem;">{{ contact.email || '—' }}</div>
                                <div>{{ contact.phone || contact.mobile || '—' }}</div>
                                <div v-if="contact.notes" style="margin-top: 0.35rem;">{{ contact.notes }}</div>
                            </div>

                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <button class="btn btn-secondary" @click="editContact(contact)">Edit</button>
                                <ConfirmButton @confirm="removeContact(contact.id)">Delete</ConfirmButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid">
                <div class="card">
                    <div style="display: flex; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                        <h3 style="margin-top: 0;">Notes</h3>
                        <button class="btn btn-primary" @click="startNewNote">
                            {{ showNoteForm ? 'Close Form' : 'Add Note' }}
                        </button>
                    </div>

                    <form v-if="showNoteForm" @submit.prevent="submitNote" style="margin-bottom: 1rem;">
                        <div class="form-group">
                            <label>Title</label>
                            <input v-model="noteForm.title" class="form-control" type="text">
                        </div>

                        <div class="form-group">
                            <label>Body</label>
                            <textarea v-model="noteForm.body" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Created By</label>
                            <input v-model="noteForm.created_by" class="form-control" type="text">
                        </div>

                        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                            <button class="btn btn-primary" type="submit">
                                {{ editingNoteId ? 'Update Note' : 'Save Note' }}
                            </button>

                            <button class="btn btn-secondary" type="button" @click="resetNoteForm">
                                Cancel
                            </button>
                        </div>
                    </form>

                    <div v-if="client.notes.length === 0" style="color: var(--muted);">
                        No notes yet.
                    </div>

                    <div
                        v-for="note in client.notes"
                        :key="note.id"
                        class="card"
                        style="margin-top: 0.75rem; box-shadow: none;"
                    >
                        <div style="display: flex; justify-content: space-between; gap: 1rem;">
                            <div>
                                <strong>{{ note.title }}</strong>
                                <div style="color: var(--muted); margin-top: 0.35rem;">
                                    {{ note.created_by || 'System' }} · {{ readableDateTime(note.created_at) }}
                                </div>
                                <div style="margin-top: 0.5rem; white-space: pre-line;">
                                    {{ note.body }}
                                </div>
                            </div>

                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <button class="btn btn-secondary" @click="editNote(note)">Edit</button>
                                <ConfirmButton @confirm="removeNote(note.id)">Delete</ConfirmButton>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div style="display: flex; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
                        <h3 style="margin-top: 0;">Tasks</h3>
                        <button class="btn btn-primary" @click="startNewTask">
                            {{ showTaskForm ? 'Close Form' : 'Add Task' }}
                        </button>
                    </div>

                    <form v-if="showTaskForm" @submit.prevent="submitTask" style="margin-bottom: 1rem;">
                        <div class="form-group">
                            <label>Title</label>
                            <input v-model="taskForm.title" class="form-control" type="text">
                        </div>

                        <div class="grid grid-2">
                            <div class="form-group">
                                <label>Priority</label>
                                <select v-model="taskForm.priority" class="form-control">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select v-model="taskForm.status" class="form-control">
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Due Date</label>
                                <input v-model="taskForm.due_date" class="form-control" type="date">
                            </div>

                            <div class="form-group">
                                <label>Assigned To</label>
                                <input v-model="taskForm.assigned_to" class="form-control" type="text">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea v-model="taskForm.description" class="form-control"></textarea>
                        </div>

                        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                            <button class="btn btn-primary" type="submit">
                                {{ editingTaskId ? 'Update Task' : 'Save Task' }}
                            </button>

                            <button class="btn btn-secondary" type="button" @click="resetTaskForm">
                                Cancel
                            </button>
                        </div>
                    </form>

                    <div v-if="client.tasks.length === 0" style="color: var(--muted);">
                        No tasks yet.
                    </div>

                    <div
                        v-for="task in client.tasks"
                        :key="task.id"
                        class="card"
                        style="margin-top: 0.75rem; box-shadow: none;"
                    >
                        <div style="display: flex; justify-content: space-between; gap: 1rem;">
                            <div>
                                <strong>{{ task.title }}</strong>
                                <div style="margin-top: 0.35rem;">
                                    <StatusBadge :status="task.status" />
                                </div>
                                <div style="color: var(--muted); margin-top: 0.35rem;">
                                    Priority: {{ task.priority }} |
                                    Due: {{ readableDate(task.due_date) }} |
                                    Assigned: {{ task.assigned_to || '—' }}
                                </div>
                                <div v-if="task.description" style="margin-top: 0.5rem;">
                                    {{ task.description }}
                                </div>
                            </div>

                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <button class="btn btn-secondary" @click="editTask(task)">Edit</button>
                                <ConfirmButton @confirm="removeTask(task.id)">Delete</ConfirmButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-else class="container">
        <div class="card">Loading client details...</div>
    </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { useClientStore } from '../stores/clientStore';
import { useContactStore } from '../stores/contactStore';
import { useNoteStore } from '../stores/noteStore';
import { useTaskStore } from '../stores/taskStore';
import { useUiStore } from '../stores/uiStore';
import PageHeader from '../components/PageHeader.vue';
import StatusBadge from '../components/StatusBadge.vue';
import ConfirmButton from '../components/ConfirmButton.vue';
import { money, readableDate, readableDateTime } from '../utils/formatters';

const props = defineProps({
    id: {
        type: [String, Number],
        required: true,
    },
});

const clientStore = useClientStore();
const contactStore = useContactStore();
const noteStore = useNoteStore();
const taskStore = useTaskStore();
const uiStore = useUiStore();

const client = computed(() => clientStore.client);

const showContactForm = ref(false);
const showNoteForm = ref(false);
const showTaskForm = ref(false);

const editingContactId = ref(null);
const editingNoteId = ref(null);
const editingTaskId = ref(null);

const contactForm = reactive({
    client_id: props.id,
    first_name: '',
    last_name: '',
    job_title: '',
    email: '',
    phone: '',
    mobile: '',
    is_primary: false,
    notes: '',
});

const noteForm = reactive({
    client_id: props.id,
    title: '',
    body: '',
    created_by: '',
});

const taskForm = reactive({
    client_id: props.id,
    title: '',
    description: '',
    priority: 'medium',
    status: 'pending',
    due_date: '',
    assigned_to: '',
});

const fullLocation = computed(() => {
    const parts = [
        client.value?.city,
        client.value?.state,
        client.value?.postal_code,
        client.value?.country,
    ].filter(Boolean);

    return parts.length ? parts.join(', ') : '—';
});

async function loadClient() {
    await clientStore.fetchClient(props.id);
}

function startNewContact() {
    if (showContactForm.value && !editingContactId.value) {
        resetContactForm();
        return;
    }

    editingContactId.value = null;
    showContactForm.value = true;
}

function startNewNote() {
    if (showNoteForm.value && !editingNoteId.value) {
        resetNoteForm();
        return;
    }

    editingNoteId.value = null;
    showNoteForm.value = true;
}

function startNewTask() {
    if (showTaskForm.value && !editingTaskId.value) {
        resetTaskForm();
        return;
    }

    editingTaskId.value = null;
    showTaskForm.value = true;
}

function resetContactForm() {
    editingContactId.value = null;
    showContactForm.value = false;

    Object.assign(contactForm, {
        client_id: props.id,
        first_name: '',
        last_name: '',
        job_title: '',
        email: '',
        phone: '',
        mobile: '',
        is_primary: false,
        notes: '',
    });
}

function resetNoteForm() {
    editingNoteId.value = null;
    showNoteForm.value = false;

    Object.assign(noteForm, {
        client_id: props.id,
        title: '',
        body: '',
        created_by: '',
    });
}

function resetTaskForm() {
    editingTaskId.value = null;
    showTaskForm.value = false;

    Object.assign(taskForm, {
        client_id: props.id,
        title: '',
        description: '',
        priority: 'medium',
        status: 'pending',
        due_date: '',
        assigned_to: '',
    });
}

function editContact(contact) {
    editingContactId.value = contact.id;
    showContactForm.value = true;

    Object.assign(contactForm, {
        client_id: props.id,
        first_name: contact.first_name || '',
        last_name: contact.last_name || '',
        job_title: contact.job_title || '',
        email: contact.email || '',
        phone: contact.phone || '',
        mobile: contact.mobile || '',
        is_primary: !!contact.is_primary,
        notes: contact.notes || '',
    });
}

function editNote(note) {
    editingNoteId.value = note.id;
    showNoteForm.value = true;

    Object.assign(noteForm, {
        client_id: props.id,
        title: note.title || '',
        body: note.body || '',
        created_by: note.created_by || '',
    });
}

function editTask(task) {
    editingTaskId.value = task.id;
    showTaskForm.value = true;

    Object.assign(taskForm, {
        client_id: props.id,
        title: task.title || '',
        description: task.description || '',
        priority: task.priority || 'medium',
        status: task.status || 'pending',
        due_date: task.due_date || '',
        assigned_to: task.assigned_to || '',
    });
}

async function submitContact() {
    try {
        if (editingContactId.value) {
            await contactStore.updateContact(editingContactId.value, { ...contactForm });
            uiStore.toast('Contact updated successfully.');
        } else {
            await contactStore.createContact({ ...contactForm });
            uiStore.toast('Contact created successfully.');
        }

        resetContactForm();
        await loadClient();
    } catch (error) {
        uiStore.toast('Unable to save contact.', 'error');
        console.error(error);
    }
}

async function submitNote() {
    try {
        if (editingNoteId.value) {
            await noteStore.updateNote(editingNoteId.value, { ...noteForm });
            uiStore.toast('Note updated successfully.');
        } else {
            await noteStore.createNote({ ...noteForm });
            uiStore.toast('Note created successfully.');
        }

        resetNoteForm();
        await loadClient();
    } catch (error) {
        uiStore.toast('Unable to save note.', 'error');
        console.error(error);
    }
}

async function submitTask() {
    try {
        if (editingTaskId.value) {
            await taskStore.updateTask(editingTaskId.value, { ...taskForm });
            uiStore.toast('Task updated successfully.');
        } else {
            await taskStore.createTask({ ...taskForm });
            uiStore.toast('Task created successfully.');
        }

        resetTaskForm();
        await loadClient();
    } catch (error) {
        uiStore.toast('Unable to save task.', 'error');
        console.error(error);
    }
}

async function removeContact(id) {
    await contactStore.deleteContact(id);
    uiStore.toast('Contact deleted successfully.');
    await loadClient();
}

async function removeNote(id) {
    await noteStore.deleteNote(id);
    uiStore.toast('Note deleted successfully.');
    await loadClient();
}

async function removeTask(id) {
    await taskStore.deleteTask(id);
    uiStore.toast('Task deleted successfully.');
    await loadClient();
}

onMounted(() => {
    loadClient();
});
</script>
