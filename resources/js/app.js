import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import router from './router';
import axios from 'axios';

axios.defaults.baseURL = '/api';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const app = createApp(App);

app.config.globalProperties.$http = axios;

app.use(createPinia());
app.use(router);
app.mount('#app');