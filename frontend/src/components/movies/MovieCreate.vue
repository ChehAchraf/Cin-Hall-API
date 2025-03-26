<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">إضافة فيلم جديد</h1>
        <router-link to="/movies" class="text-indigo-600 hover:text-indigo-900">
          العودة للقائمة
        </router-link>
      </div>

      <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <form @submit.prevent="handleSubmit" class="space-y-6">
            <div>
              <label for="title" class="block text-sm font-medium text-gray-700">عنوان الفيلم</label>
              <input type="text" id="title" v-model="form.title" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div>
              <label for="description" class="block text-sm font-medium text-gray-700">وصف الفيلم</label>
              <textarea id="description" v-model="form.description" rows="3" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            </div>

            <div>
              <label for="duration" class="block text-sm font-medium text-gray-700">المدة (بالدقائق)</label>
              <input type="number" id="duration" v-model="form.duration" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div>
              <label for="min_age" class="block text-sm font-medium text-gray-700">العمر الصغير</label>
              <input type="number" id="min_age" v-model="form.min_age" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div>
              <label for="trailer_url" class="block text-sm font-medium text-gray-700">رابط الموسيقى</label>
              <input type="text" id="trailer_url" v-model="form.trailer_url" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div>
              <label for="genre" class="block text-sm font-medium text-gray-700">الصنف</label>
              <input type="text" id="genre" v-model="form.genre" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div>
              <label for="hall_id" class="block text-sm font-medium text-gray-700">القاعة</label>
              <select id="hall_id" v-model="form.hall_id" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option v-for="hall in halls" :key="hall.id" :value="hall.id">{{ hall.name }}</option>
              </select>
            </div>

            <div>
              <label for="price" class="block text-sm font-medium text-gray-700">السعر (درهم)</label>
              <input type="number" id="price" v-model="form.price" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div>
              <label for="image" class="block text-sm font-medium text-gray-700">صورة الفيلم</label>
              <input type="file" id="image" @change="handleImageChange" accept="image/*" required class="mt-1 block w-full">
            </div>

            <div class="flex justify-end">
              <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                حفظ
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

export default {
  name: 'MovieCreate',
  setup() {
    const router = useRouter()
    const halls = ref([])
    const form = ref({
      title: '',
      description: '',
      duration: '',
      min_age: 0,
      trailer_url: '',
      genre: '',
      image: null
    })

    const fetchHalls = async () => {
      try {
        const response = await axios.get('/api/halls')
        halls.value = response.data.data
      } catch (error) {
        console.error('Error fetching halls:', error)
      }
    }

    const handleImageChange = (event) => {
      form.value.image = event.target.files[0]
    }

    const handleSubmit = async () => {
      try {
        const formData = new FormData()
        formData.append('title', form.value.title)
        formData.append('description', form.value.description)
        formData.append('duration', form.value.duration)
        formData.append('min_age', form.value.min_age)
        formData.append('trailer_url', form.value.trailer_url)
        formData.append('genre', form.value.genre)
        if (form.value.image) {
          formData.append('image', form.value.image)
        }

        const response = await axios.post('/api/movies', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })

        if (response.data.status) {
          router.push('/movies')
        } else {
          console.error('Error creating movie:', response.data.message)
        }
      } catch (error) {
        console.error('Error creating movie:', error.response?.data?.message || error.message)
      }
    }

    onMounted(fetchHalls)

    return {
      form,
      halls,
      handleImageChange,
      handleSubmit
    }
  }
}
</script> 