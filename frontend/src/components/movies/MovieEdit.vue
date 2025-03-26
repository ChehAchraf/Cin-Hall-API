<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">تعديل الفيلم</h1>
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
              <label for="release_date" class="block text-sm font-medium text-gray-700">تاريخ الإصدار</label>
              <input type="date" id="release_date" v-model="form.release_date" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
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
              <input type="file" id="image" @change="handleImageChange" accept="image/*" class="mt-1 block w-full">
              <img v-if="form.image_url" :src="form.image_url" :alt="form.title" class="mt-2 h-32 w-32 object-cover rounded">
            </div>

            <div class="flex justify-end">
              <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                حفظ التغييرات
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
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'

export default {
  name: 'MovieEdit',
  setup() {
    const router = useRouter()
    const route = useRoute()
    const halls = ref([])
    const form = ref({
      title: '',
      description: '',
      duration: '',
      release_date: '',
      hall_id: '',
      price: '',
      image: null,
      image_url: ''
    })

    const fetchMovie = async () => {
      try {
        const response = await axios.get(`/api/movies/${route.params.id}`)
        const movie = response.data.data
        form.value = {
          ...movie,
          image: null,
          image_url: movie.image
        }
      } catch (error) {
        console.error('Error fetching movie:', error)
      }
    }

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
        Object.keys(form.value).forEach(key => {
          if (key !== 'image_url' && form.value[key] !== null) {
            formData.append(key, form.value[key])
          }
        })

        await axios.post(`/api/movies/${route.params.id}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })

        router.push('/movies')
      } catch (error) {
        console.error('Error updating movie:', error)
      }
    }

    onMounted(() => {
      fetchMovie()
      fetchHalls()
    })

    return {
      form,
      halls,
      handleImageChange,
      handleSubmit
    }
  }
}
</script> 