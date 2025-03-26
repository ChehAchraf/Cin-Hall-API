import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import axios from 'axios';
import App from './App.vue';
import Login from './components/Login.vue';
import Home from './components/Home.vue';

// إعداد axios
axios.defaults.baseURL = '/api';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// التحقق من وجود توكن محفوظ
const token = localStorage.getItem('token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

// تعريف المسارات
const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
        meta: { requiresAuth: true }
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: { requiresAuth: false }
    }
];

// إنشاء مثيل Router
const router = createRouter({
    history: createWebHistory(),
    routes
});

// حماية المسارات
router.beforeEach((to, from, next) => {
    const isAuthenticated = !!localStorage.getItem('token');
    
    if (to.meta.requiresAuth && !isAuthenticated) {
        next('/login');
    } else if (to.path === '/login' && isAuthenticated) {
        next('/');
    } else {
        next();
    }
});

// إنشاء تطبيق Vue
const app = createApp(App);

// استخدام Router
app.use(router);

// تحميل التطبيق
app.mount('#app');
