<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          إنشاء حساب جديد
        </h2>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="handleRegister">
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="name" class="sr-only">الاسم</label>
            <input id="name" v-model="form.name" name="name" type="text" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="الاسم">
          </div>
          <div>
            <label for="email" class="sr-only">البريد الإلكتروني</label>
            <input id="email" v-model="form.email" name="email" type="email" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="البريد الإلكتروني">
          </div>
          <div>
            <label for="password" class="sr-only">كلمة المرور</label>
            <input id="password" v-model="form.password" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="كلمة المرور">
          </div>
          <div>
            <label for="password_confirmation" class="sr-only">تأكيد كلمة المرور</label>
            <input id="password_confirmation" v-model="form.password_confirmation" name="password_confirmation" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="تأكيد كلمة المرور">
          </div>
        </div>

        <div>
          <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            تسجيل
          </button>
        </div>
      </form>
      <div class="text-center">
        <router-link to="/login" class="text-indigo-600 hover:text-indigo-500">
          لديك حساب بالفعل؟ سجل دخول
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

export default {
  name: 'Register',
  setup() {
    const router = useRouter()
    const form = ref({
      name: '',
      email: '',
      password: '',
      password_confirmation: ''
    })

    const handleRegister = async () => {
      try {
        const response = await axios.post('/api/register', form.value)
        localStorage.setItem('token', response.data.access_token)
        localStorage.setItem('user_role', response.data.user.role)
        router.push('/dashboard')
      } catch (error) {
        console.error('Registration error:', error)
      }
    }

    return {
      form,
      handleRegister
    }
  }
}
</script> 