<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    تسجيل الدخول
                </h2>
            </div>
            <form class="mt-8 space-y-6" @submit.prevent="handleLogin">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email" class="sr-only">البريد الإلكتروني</label>
                        <input id="email" v-model="form.email" name="email" type="email" required 
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                            placeholder="البريد الإلكتروني">
                    </div>
                    <div>
                        <label for="password" class="sr-only">كلمة المرور</label>
                        <input id="password" v-model="form.password" name="password" type="password" required 
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" 
                            placeholder="كلمة المرور">
                    </div>
                </div>

                <div>
                    <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        :disabled="loading">
                        <span v-if="loading">جاري التحميل...</span>
                        <span v-else>تسجيل الدخول</span>
                    </button>
                </div>

                <div v-if="error" class="text-red-500 text-center">
                    {{ error }}
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

export default {
    name: 'Login',
    setup() {
        const router = useRouter();
        const loading = ref(false);
        const error = ref('');
        
        const form = reactive({
            email: '',
            password: ''
        });
        
        const handleLogin = async () => {
            try {
                loading.value = true;
                error.value = '';
                
                const response = await axios.post('/api/login', form);
                
                if (response.data.token) {
                    // حفظ التوكن في localStorage
                    localStorage.setItem('token', response.data.token);
                    // تعيين التوكن في header للطلبات المستقبلية
                    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
                    
                    // التوجيه إلى الصفحة الرئيسية
                    router.push('/');
                }
            } catch (err) {
                error.value = err.response?.data?.message || 'حدث خطأ في تسجيل الدخول';
                console.error('خطأ في تسجيل الدخول:', err);
            } finally {
                loading.value = false;
            }
        };

        return {
            form,
            loading,
            error,
            handleLogin
        };
    }
}
</script> 