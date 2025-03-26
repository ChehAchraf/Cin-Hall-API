<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <h1 class="text-xl font-bold">CineHall</h1>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <button @click="handleLogout" 
                            class="ml-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                            تسجيل الخروج
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <h2 class="text-2xl font-bold mb-4">مرحباً بك في CineHall</h2>
                <!-- هنا يمكنك إضافة محتوى الصفحة الرئيسية -->
            </div>
        </main>
    </div>
</template>

<script>
import { useRouter } from 'vue-router';
import axios from 'axios';

export default {
    name: 'Home',
    setup() {
        const router = useRouter();

        const handleLogout = async () => {
            try {
                await axios.post('/api/logout');
                localStorage.removeItem('token');
                delete axios.defaults.headers.common['Authorization'];
                router.push('/login');
            } catch (error) {
                console.error('خطأ في تسجيل الخروج:', error);
            }
        };

        return {
            handleLogout
        };
    }
}
</script> 