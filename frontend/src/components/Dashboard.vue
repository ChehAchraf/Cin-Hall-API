<script>
import { computed } from 'vue'
import { useRouter } from 'vue-router'

export default {
  name: 'Dashboard',
  setup() {
    const router = useRouter()
    const isAdmin = computed(() => localStorage.getItem('user_role') === 'admin')

    const handleLogout = () => {
      localStorage.removeItem('token')
      localStorage.removeItem('user_role')
      router.push('/login')
    }

    return {
      isAdmin,
      handleLogout
    }
  }
}
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <h1 class="text-xl font-bold text-gray-900">CineHall</h1>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
              <router-link to="/dashboard" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                الرئيسية
              </router-link>
              <router-link to="/movies" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                الأفلام
              </router-link>
              <router-link to="/halls" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                القاعات
              </router-link>
              <router-link to="/reservations" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                الحجوزات
              </router-link>
              <router-link v-if="isAdmin" to="/sessions" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                الجلسات
              </router-link>
            </div>
          </div>
          <div class="flex items-center">
            <button @click="handleLogout" class="text-gray-500 hover:text-gray-700">
              تسجيل الخروج
            </button>
          </div>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <router-view></router-view>
    </main>
  </div>
</template>

<style scoped>
    .dashboard-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .dashboard-container h1 {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
    }
</style>