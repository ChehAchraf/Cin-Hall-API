import { createRouter, createWebHistory } from 'vue-router'
import Login from './components/auth/Login.vue'
import Register from './components/auth/Register.vue'
import Dashboard from './components/Dashboard.vue'
import MoviesList from './components/movies/MoviesList.vue'
import MovieCreate from './components/movies/MovieCreate.vue'
import MovieEdit from './components/movies/MovieEdit.vue'
// import HallsList from './components/halls/HallsList.vue'
// import HallCreate from './components/halls/HallCreate.vue'
// import HallEdit from './components/halls/HallEdit.vue'
// import ReservationsList from './components/reservations/ReservationsList.vue'
// import ReservationCreate from './components/reservations/ReservationCreate.vue'
// import ReservationEdit from './components/reservations/ReservationEdit.vue'
// import SessionsList from './components/sessions/SessionsList.vue'
// import SessionCreate from './components/sessions/SessionCreate.vue'
// import SessionEdit from './components/sessions/SessionEdit.vue'

const routes = [
    {
        path: '/',
        redirect: '/login'
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/register',
        name: 'register',
        component: Register
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    },
    // Movies Routes
    {
        path: '/movies',
        name: 'movies',
        component: MoviesList,
        meta: { requiresAuth: true }
    },
    {
        path: '/movies/create',
        name: 'movie-create',
        component: MovieCreate,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
        path: '/movies/:id/edit',
        name: 'movie-edit',
        component: MovieEdit,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    // Halls Routes
    {
        path: '/halls',
        name: 'halls',
        component: HallsList,
        meta: { requiresAuth: true }
    },
    {
        path: '/halls/create',
        name: 'hall-create',
        component: HallCreate,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
        path: '/halls/:id/edit',
        name: 'hall-edit',
        component: HallEdit,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    // Reservations Routes
    {
        path: '/reservations',
        name: 'reservations',
        component: ReservationsList,
        meta: { requiresAuth: true }
    },
    {
        path: '/reservations/create',
        name: 'reservation-create',
        component: ReservationCreate,
        meta: { requiresAuth: true }
    },
    {
        path: '/reservations/:id/edit',
        name: 'reservation-edit',
        component: ReservationEdit,
        meta: { requiresAuth: true }
    },
    // Sessions Routes
    {
        path: '/sessions',
        name: 'sessions',
        component: SessionsList,
        meta: { requiresAuth: true }
    },
    {
        path: '/sessions/create',
        name: 'session-create',
        component: SessionCreate,
        meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
        path: '/sessions/:id/edit',
        name: 'session-edit',
        component: SessionEdit,
        meta: { requiresAuth: true, requiresAdmin: true }
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

// Navigation Guards
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token')
    const userRole = localStorage.getItem('user_role')

    if (to.meta.requiresAuth && !token) {
        next('/login')
    } else if (to.meta.requiresAdmin && userRole !== 'admin') {
        next('/dashboard')
    } else {
        next()
    }
})

export default router