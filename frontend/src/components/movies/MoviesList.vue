<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">الأفلام</h1>
        <router-link v-if="isAdmin" to="/movies/create" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
          إضافة فيلم جديد
        </router-link>
      </div>

      <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
          <li v-for="movie in movies" :key="movie.id" class="px-6 py-4 hover:bg-gray-50">
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <img :src="movie.image" :alt="movie.title" class="h-16 w-16 object-cover rounded">
                <div class="mr-4">
                  <h3 class="text-lg font-medium text-gray-900">{{ movie.title }}</h3>
                  <p class="text-sm text-gray-500">{{ movie.description }}</p>
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <span class="text-gray-900">{{ movie.price }} درهم</span>
                <div v-if="isAdmin" class="flex space-x-2">
                  <router-link :to="`/movies/${movie.id}/edit`" class="text-indigo-600 hover:text-indigo-900">
                    تعديل
                  </router-link>
                  <button @click="deleteMovie(movie.id)" class="text-red-600 hover:text-red-900">
                    حذف
                  </button>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

export default {
  name: 'MoviesList',
  setup() {
    const movies = ref([])
    const isAdmin = computed(() => localStorage.getItem('user_role') === 'admin')

    const fetchMovies = async () => {
      try {
        const response = await axios.get('/api/movies')
        movies.value = response.data.data
      } catch (error) {
        console.error('Error fetching movies:', error)
      }
    }

    const deleteMovie = async (id) => {
      if (confirm('هل أنت متأكد من حذف هذا الفيلم؟')) {
        try {
          await axios.delete(`/api/movies/${id}`)
          movies.value = movies.value.filter(movie => movie.id !== id)
        } catch (error) {
          console.error('Error deleting movie:', error)
        }
      }
    }

    onMounted(fetchMovies)

    return {
      movies,
      isAdmin,
      deleteMovie
    }
  }
}
</script> 