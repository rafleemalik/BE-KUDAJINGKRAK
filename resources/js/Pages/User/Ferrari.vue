<template>
  <div class="min-h-screen bg-black text-white w-full overflow-x-hidden">
    <!-- Notification Toast -->
    <div v-if="showNotification" 
         class="fixed top-4 right-4 z-50 transform transition-all duration-500"
         :class="[
           showNotification ? 'translate-x-0 opacity-100' : 'translate-x-full opacity-0',
           notificationType === 'success' ? 'bg-green-500' : 'bg-red-500'
         ]">
      <div class="flex items-center p-4 rounded-lg shadow-lg">
        <div class="flex-shrink-0">
          <svg v-if="notificationType === 'success'" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <svg v-else class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm font-medium text-white">
            {{ notificationMessage }}
          </p>
        </div>
        <div class="ml-auto pl-3">
          <div class="-mx-1.5 -my-1.5">
            <button @click="showNotification = false" class="inline-flex rounded-md p-1.5 text-white hover:bg-white/10 focus:outline-none">
              <span class="sr-only">Dismiss</span>
              <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- Header dengan Profile -->
    <div class="sticky top-0 left-0 right-0 z-50 bg-black/80 backdrop-blur-sm border-b border-gray-800">
      <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
        <div class="flex items-center gap-4">
          <button @click="showProfile = !showProfile" class="flex items-center gap-2 hover:text-red-500 transition-colors">
            <img :src="user.profile_photo || '/images/leclerc.jpg'" 
                 alt="Profile" 
                 class="w-10 h-10 rounded-full object-cover border-2 border-red-500">
            <span>{{ user.name }}</span>
          </button>
        </div>
        
        <!-- Logout Button -->
        <form @submit.prevent="logout">
          <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-full transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-red-500/50 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
            </svg>
            Logout
          </button>
        </form>
      </div>
    </div>

    <!-- Profile Modal -->
    <div v-if="showProfile" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm">
      <div class="bg-gray-900 rounded-xl p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold">Profile</h2>
          <button @click="showProfile = false" class="text-gray-400 hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Profile Info -->
        <div class="space-y-6">
          <!-- Profile Photo -->
          <div class="flex items-center gap-4">
            <img :src="user.profile_photo || '/images/leclerc.jpg'" 
                 alt="Profile" 
                 class="w-24 h-24 rounded-full object-cover border-2 border-red-500">
            <div>
              <h3 class="text-xl font-semibold text-white">{{ user.name }}</h3>
              <p class="text-gray-400">Ferrari Enthusiast</p>
            </div>
          </div>

          <!-- Orders Section -->
          <div>
            <h3 class="text-xl font-semibold mb-4">My Orders</h3>
            <div v-if="orders.length > 0" class="space-y-4">
              <div v-for="order in orders" :key="order.id" class="bg-gray-800 p-4 rounded-lg">
                <div class="flex justify-between items-center">
                  <div>
                    <h4 class="font-semibold">{{ order.car.name }}</h4>
                    <p class="text-gray-400">Order Date: {{ new Date(order.purchase_date).toLocaleDateString() }}</p>
                    <p class="text-red-500 font-medium">${{ order.price.toLocaleString() }}</p>
                  </div>
                  <span :class="[
                    'px-3 py-1 rounded-full text-sm',
                    order.status === 'completed' ? 'bg-green-500' : 
                    order.status === 'cancelled' ? 'bg-red-500' : 'bg-yellow-500'
                  ]">
                    {{ order.status }}
                  </span>
                </div>
              </div>
            </div>
            <p v-else class="text-gray-400">No orders yet</p>
          </div>

          <!-- Liked Cars Section -->
          <div>
            <h3 class="text-xl font-semibold mb-4">Liked Cars</h3>
            <div v-if="likedCars.length > 0" class="grid grid-cols-2 gap-4">
              <div v-for="car in likedCars" :key="car.id" class="bg-gray-800 p-4 rounded-lg group hover:bg-gray-700 transition-all duration-300">
                <div class="relative h-40 mb-3 overflow-hidden rounded-lg">
                  <img :src="getImageUrl(car.image)" 
                       :alt="car.name" 
                       class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110">
                </div>
                <h4 class="font-semibold text-white mb-1">{{ car.name }}</h4>
                <p class="text-red-500 font-medium">${{ car.price.toLocaleString() }}</p>
                <p class="text-gray-400 text-sm mt-1 line-clamp-2">{{ car.specifications }}</p>
                <button @click="toggleLike(car)" 
                        class="mt-3 w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                  </svg>
                  Remove from Favorites
                </button>
              </div>
            </div>
            <p v-else class="text-gray-400 text-center py-4">No liked cars yet</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Hero Section dengan Parallax -->
    <div class="relative h-screen w-full overflow-hidden">
      <div class="absolute inset-0">
        <img src="/public/images/lewis-hamilton-ferrari.jpg" 
             alt="Ferrari Background" 
             class="w-full h-full object-cover transform scale-110 animate-zoom">
      </div>
      <div class="absolute inset-0 bg-gradient-to-r from-black/80 to-red-900/50"></div>
      <div class="absolute inset-0 flex items-center justify-center">
        <div class="text-center transform transition-all duration-500 hover:scale-105">
          <h1 class="text-8xl font-bold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-white animate-fade-in ferrari-title">
            FERRARI SHOWROOM
          </h1>
          <p class="text-2xl mb-8 text-gray-300 animate-fade-in-delay">Where Dreams Meet Reality</p>
          <button @click="scrollToShowroom" class="bg-red-600 hover:bg-red-700 text-white px-12 py-4 rounded-full transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-red-500/50 animate-fade-in-delay-2">
            Explore Our Collection
          </button>
        </div>
      </div>
    </div>

    <!-- Features Section dengan Animasi -->
    <div class="py-20 px-4 md:px-8 w-full bg-gradient-to-b from-black to-gray-900">
      <div class="max-w-7xl mx-auto w-full">
        <h2 class="text-5xl font-bold mb-16 text-center text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-white animate-fade-in">
          Why Choose Ferrari?
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
          <div v-for="(feature, index) in features" 
               :key="index" 
               class="group relative bg-gray-900/50 p-8 rounded-xl transform transition-all duration-500 hover:scale-105 hover:shadow-lg hover:shadow-red-500/20 border border-gray-800 hover:border-red-500/50 overflow-hidden"
               :class="`animate-fade-in-delay-${index + 3}`">
            <!-- Background Gradient Effect -->
            <div class="absolute inset-0 bg-gradient-to-br from-red-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            
            <!-- Icon Container -->
            <div class="relative mb-6">
              <div class="absolute inset-0 bg-red-500/20 rounded-full blur-xl transform scale-0 group-hover:scale-100 transition-transform duration-500"></div>
              <div class="relative text-red-500 text-5xl transform transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">
                {{ feature.icon }}
              </div>
            </div>

            <!-- Content -->
            <div class="relative space-y-4">
              <h3 class="text-2xl font-semibold text-white group-hover:text-red-500 transition-colors duration-300">
                {{ feature.title }}
              </h3>
              <p class="text-gray-400 leading-relaxed group-hover:text-gray-300 transition-colors duration-300">
                {{ feature.description }}
              </p>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-red-500/10 rounded-full blur-2xl transform scale-0 group-hover:scale-100 transition-transform duration-500"></div>
            <div class="absolute -left-4 -top-4 w-24 h-24 bg-red-500/10 rounded-full blur-2xl transform scale-0 group-hover:scale-100 transition-transform duration-500"></div>
          </div>
        </div>

        <!-- Stats Section -->
        <div class="mt-20 grid grid-cols-2 md:grid-cols-4 gap-8">
          <div v-for="(stat, index) in stats" 
               :key="index"
               class="text-center p-6 rounded-xl bg-gray-900/50 border border-gray-800 transform transition-all duration-500 hover:scale-105 hover:shadow-lg hover:shadow-red-500/20"
               :class="`animate-fade-in-delay-${index + 6}`">
            <div class="text-4xl font-bold text-red-500 mb-2">{{ stat.value }}</div>
            <div class="text-gray-400">{{ stat.label }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Gallery Section dengan Hover Effects -->
    <div v-show="showShowroom" ref="showroomSection" class="py-20 w-full bg-gradient-to-b from-gray-900 to-black">
      <div class="max-w-7xl mx-auto px-4 md:px-8 w-full">
        <h2 class="text-5xl font-bold mb-16 text-center text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-white animate-fade-in">
          Our Showroom
        </h2>
        
        <!-- Filter dan Search Bar -->
        <div class="mb-12 flex flex-col md:flex-row gap-4 justify-between items-center">
          <div class="flex gap-4">
            <button v-for="category in categories" 
                    :key="category.value"
                    @click="selectedCategory = category.value"
                    :class="[
                      'px-6 py-3 rounded-full transition-all duration-300 transform hover:scale-105',
                      selectedCategory === category.value 
                        ? 'bg-red-600 text-white shadow-lg shadow-red-500/50' 
                        : 'bg-gray-800 text-gray-300 hover:bg-gray-700'
                    ]">
              {{ category.label }}
            </button>
          </div>
          <div class="relative w-full md:w-64">
            <input type="text" 
                   v-model="searchQuery" 
                   placeholder="Search car name..." 
                   class="w-full px-6 py-3 bg-gray-800/50 border border-gray-700 rounded-full focus:outline-none focus:ring-2 focus:ring-red-600 text-white placeholder-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-5 w-5 absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400" 
                 fill="none" 
                 viewBox="0 0 24 24" 
                 stroke="currentColor">
              <path stroke-linecap="round" 
                    stroke-linejoin="round" 
                    stroke-width="2" 
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
        </div>

        <!-- Grid Cars dengan Animasi -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="(car, index) in filteredAndSearchedCars" 
               :key="car.id" 
               class="group relative overflow-hidden rounded-xl bg-gray-900/50 border border-gray-800 hover:border-red-500/50 transition-all duration-500"
               :class="`animate-fade-in-delay-${index % 6 + 1}`">
            <!-- Car Image dengan Zoom Effect -->
            <div class="relative h-80 overflow-hidden">
              <img :src="getImageUrl(car.image)" 
                   :alt="car.name" 
                   class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110">
              <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            </div>

            <!-- Car Info dengan Slide Up Animation -->
            <div class="absolute inset-0 flex flex-col justify-end p-6 transform translate-y-8 group-hover:translate-y-0 transition-transform duration-500">
              <div class="space-y-4">
                <div class="flex justify-between items-start">
                  <div>
                    <h3 class="text-2xl font-bold text-white mb-2">{{ car.name }}</h3>
                    <p class="text-red-500 text-xl font-semibold">${{ car.price.toLocaleString() }}</p>
                  </div>
                  <span class="px-4 py-2 bg-red-600/20 text-red-500 rounded-full text-sm font-medium">
                    {{ car.type }}
                  </span>
                </div>
                
                <p class="text-gray-300 text-sm line-clamp-2">{{ car.specifications }}</p>
                
                <div class="flex gap-4 pt-4">
                  <button @click="openCarDetail(car)" 
                          class="flex-1 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-full transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-red-500/50 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                      <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                    </svg>
                    View Details
                  </button>
                  <button @click="toggleLike(car)" 
                          class="bg-gray-800 hover:bg-gray-700 text-white p-3 rounded-full transition-all duration-300 transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-6 w-6" 
                         :class="{'text-red-500': isLiked(car)}"
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke="currentColor">
                      <path stroke-linecap="round" 
                            stroke-linejoin="round" 
                            stroke-width="2" 
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="filteredAndSearchedCars.length === 0" 
             class="text-center py-12">
          <svg xmlns="http://www.w3.org/2000/svg" 
               class="h-16 w-16 mx-auto text-gray-600 mb-4" 
               fill="none" 
               viewBox="0 0 24 24" 
               stroke="currentColor">
            <path stroke-linecap="round" 
                  stroke-linejoin="round" 
                  stroke-width="2" 
                  d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <h3 class="text-xl font-semibold text-gray-400">No cars found</h3>
          <p class="text-gray-500 mt-2">Try adjusting your search or filter criteria</p>
        </div>
      </div>
    </div>

    <!-- Modal Detail Mobil -->
    <div v-if="showCarDetail && selectedCar" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm">
      <div class="bg-gray-900 rounded-xl p-8 max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <!-- Header Modal -->
        <div class="flex justify-between items-start mb-6">
          <div>
            <h2 class="text-3xl font-bold text-white mb-2">{{ selectedCar.name }}</h2>
            <p class="text-red-500 text-2xl font-semibold">${{ selectedCar.price.toLocaleString() }}</p>
          </div>
          <button @click="showCarDetail = false" class="text-gray-400 hover:text-white transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Image Gallery -->
        <div class="relative h-96 mb-8 rounded-xl overflow-hidden">
          <img :src="getImageUrl(selectedCar.image)" 
               :alt="selectedCar.name" 
               class="w-full h-full object-cover">
          <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
        </div>

        <!-- Specifications -->
        <div class="grid grid-cols-1 gap-8 mb-8">
          <div>
            <h3 class="text-xl font-semibold text-white mb-4">Specifications</h3>
            <div class="space-y-4">
              <div class="flex justify-between items-center py-2 border-b border-gray-800">
                <span class="text-gray-400">Type</span>
                <span class="text-white font-medium">{{ selectedCar.type }}</span>
              </div>
              <div class="flex justify-between items-center py-2 border-b border-gray-800">
                <span class="text-gray-400">Specifications</span>
                <span class="text-white font-medium">{{ selectedCar.specifications }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Purchase Options -->
        <div class="border-t border-gray-800 pt-8">
          <h3 class="text-xl font-semibold text-white mb-6">Purchase</h3>
          <div class="bg-gray-800/50 rounded-xl p-6 border border-gray-700 hover:border-red-500/50 transition-all duration-300">
            <div class="flex justify-between items-center mb-6">
              <div>
                <h4 class="text-lg font-semibold text-white">Total Price</h4>
                <p class="text-gray-400">One-time payment</p>
              </div>
              <div class="text-3xl font-bold text-red-500">${{ selectedCar.price.toLocaleString() }}</div>
            </div>
            <button @click="handlePurchase"
                    :disabled="isPurchasing"
                    class="w-full bg-red-600 hover:bg-red-700 text-white px-6 py-4 rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-red-500/50 text-lg font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
              {{ isPurchasing ? 'Processing...' : 'Purchase Now' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { ref, computed, onMounted, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
import axios from 'axios'

// Konfigurasi axios
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
axios.defaults.withCredentials = true

// Get user data from page props
const page = usePage()
const authUser = computed(() => page.props.auth?.user)

// User data
const user = ref({
  name: authUser.value?.name || authUser.value?.username || '',
  profile_photo: authUser.value?.profile_photo || null
})

// Watch for changes in auth user
watch(authUser, (newUser) => {
  if (newUser) {
    user.value = {
      name: newUser.name || newUser.username || '',
      profile_photo: newUser.profile_photo
    }
  }
}, { immediate: true })

// Form for profile update
const form = useForm({
  name: authUser.value?.name || authUser.value?.username || '',
  profile_photo: null
})

// Profile modal state
const showProfile = ref(false)
const photoInput = ref(null)

// Categories
const categories = [
  { label: 'All', value: 'all' },
  { label: 'Sport', value: 'sport' },
  { label: 'Hybrid', value: 'hybrid' },
  { label: 'GT', value: 'gt' },
  { label: 'SUV', value: 'suv' }
]

const selectedCategory = ref('all')

// Orders data
const orders = ref([])

// Liked cars
const likedCars = ref([])

// Cars data with categories
const cars = ref([])

// Search query
const searchQuery = ref('')

// Computed property untuk filter dan search
const filteredAndSearchedCars = computed(() => {
  let filtered = cars.value
  
  // Filter berdasarkan kategori
  if (selectedCategory.value !== 'all') {
    filtered = filtered.filter(car => car.type.toLowerCase() === selectedCategory.value.toLowerCase())
  }
  
  // Filter berdasarkan search query (hanya berdasarkan nama mobil)
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(car => 
      car.name.toLowerCase().includes(query)
    )
  }
  
  return filtered
})

// Fungsi untuk mengambil data mobil
const fetchCars = async () => {
  try {
    const response = await axios.get('/api/cars')
    cars.value = response.data
    console.log('Cars loaded:', cars.value) // Debug log
  } catch (error) {
    console.error('Error fetching cars:', error)
  }
}

// Handle photo upload
const handlePhotoUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.profile_photo = file
    const reader = new FileReader()
    reader.onload = (e) => {
      user.value.profile_photo = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

// Toggle like for a car
const toggleLike = (car) => {
  const index = likedCars.value.findIndex(c => c.id === car.id)
  if (index === -1) {
    likedCars.value.push(car)
  } else {
    likedCars.value.splice(index, 1)
  }
}

// Check if a car is liked
const isLiked = (car) => {
  return likedCars.value.some(c => c.id === car.id)
}

// Fungsi untuk mendapatkan URL gambar
const getImageUrl = (imagePath) => {
  if (!imagePath) return '/images/default-car.jpg'
  return `http://127.0.0.1:8000/storage/${imagePath}`
}

// Update profile
const updateProfile = () => {
  form.post('/profile', {
    preserveScroll: true,
    onSuccess: () => {
      showProfile.value = false
    },
    onError: (errors) => {
      console.log('Errors:', errors)
    }
  })
}

const logout = () => {
  router.post('/logout')
}

const features = [
  {
    icon: '🏎️',
    title: 'Unmatched Performance',
    description: 'Experience the pinnacle of automotive engineering with our high-performance vehicles. Every Ferrari is crafted to deliver an exhilarating driving experience.'
  },
  {
    icon: '✨',
    title: 'Luxury Redefined',
    description: 'Indulge in the finest craftsmanship and premium materials that define Ferrari luxury. From hand-stitched leather to carbon fiber accents, every detail matters.'
  },
  {
    icon: '🌟',
    title: 'Heritage & Innovation',
    description: 'A perfect blend of rich racing heritage and cutting-edge technology. Ferrari continues to push boundaries while honoring its legendary legacy.'
  }
]

const stats = [
  { value: '75+', label: 'Years of Excellence' },
  { value: '500+', label: 'Race Victories' },
  { value: '1M+', label: 'Happy Customers' },
  { value: '24/7', label: 'Customer Support' }
]

// Ref untuk showroom section
const showroomSection = ref(null)

// State untuk mengontrol visibilitas showroom
const showShowroom = ref(false)

// Fungsi untuk scroll ke showroom
const scrollToShowroom = () => {
  showShowroom.value = true
  // Tunggu sebentar agar DOM terupdate
  setTimeout(() => {
    showroomSection.value?.scrollIntoView({ 
      behavior: 'smooth',
      block: 'start'
    })
  }, 100)
}

// State untuk modal detail mobil
const showCarDetail = ref(false)
const selectedCar = ref(null)
const isPurchasing = ref(false)

// Fungsi untuk membuka modal detail
const openCarDetail = (car) => {
  selectedCar.value = car
  showCarDetail.value = true
}

// Fungsi untuk mengambil data orders
const fetchOrders = async () => {
  try {
    const response = await axios.get('/purchases')
    orders.value = response.data
  } catch (error) {
    console.error('Error fetching orders:', error)
  }
}

// Notification state
const showNotification = ref(false)
const notificationMessage = ref('')
const notificationType = ref('success')

// Function to show notification
const showToast = (message, type = 'success') => {
  notificationMessage.value = message
  notificationType.value = type
  showNotification.value = true
  
  // Auto hide after 3 seconds
  setTimeout(() => {
    showNotification.value = false
  }, 3000)
}

// Update handlePurchase function
const handlePurchase = async () => {
  if (!selectedCar.value) return
  
  try {
    isPurchasing.value = true
    console.log('Sending purchase request:', {
      car_id: selectedCar.value.id,
      price: selectedCar.value.price
    })

    const response = await axios.post('/purchases', {
      car_id: selectedCar.value.id,
      price: selectedCar.value.price
    })
    
    console.log('Purchase response:', response.data)
    
    // Show success notification
    showToast('Purchase successful! Thank you for your purchase. 🎉')
    
    // Tutup modal
    showCarDetail.value = false
    
    // Refresh data orders
    await fetchOrders()
    
  } catch (error) {
    console.error('Error making purchase:', error.response?.data || error)
    showToast(error.response?.data?.message || 'Failed to make purchase. Please try again.', 'error')
  } finally {
    isPurchasing.value = false
  }
}

// Mengambil data mobil saat komponen dimount
onMounted(() => {
  fetchCars()
  fetchOrders()
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&display=swap');

@keyframes zoom {
  0% { transform: scale(1); }
  50% { transform: scale(1.1); }
  100% { transform: scale(1); }
}

.animate-zoom {
  animation: zoom 20s infinite ease-in-out;
}

/* Fade In Animations */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fadeIn 0.8s ease-out forwards;
}

.animate-fade-in-delay {
  animation: fadeIn 0.8s ease-out 0.3s forwards;
  opacity: 0;
}

.animate-fade-in-delay-2 {
  animation: fadeIn 0.8s ease-out 0.6s forwards;
  opacity: 0;
}

.animate-fade-in-delay-3 {
  animation: fadeIn 0.8s ease-out 0.9s forwards;
  opacity: 0;
}

.animate-fade-in-delay-4 {
  animation: fadeIn 0.8s ease-out 1.2s forwards;
  opacity: 0;
}

.animate-fade-in-delay-5 {
  animation: fadeIn 0.8s ease-out 1.5s forwards;
  opacity: 0;
}

.animate-fade-in-delay-6 {
  animation: fadeIn 0.8s ease-out 1.8s forwards;
  opacity: 0;
}

.animate-fade-in-delay-7 {
  animation: fadeIn 0.8s ease-out 2.1s forwards;
  opacity: 0;
}

.ferrari-title {
  font-family: 'Playfair Display', serif;
  font-weight: 900;
  letter-spacing: 0.1em;
  text-shadow: 0 0 30px rgba(220, 38, 38, 0.3);
  background: linear-gradient(to right, #dc2626, #ffffff);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
  position: relative;
}

.ferrari-title::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 200px;
  height: 2px;
  background: linear-gradient(to right, transparent, #dc2626, transparent);
}

/* Notification Animation */
@keyframes slideIn {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes slideOut {
  from {
    transform: translateX(0);
    opacity: 1;
  }
  to {
    transform: translateX(100%);
    opacity: 0;
  }
}

.notification-enter-active {
  animation: slideIn 0.5s ease-out;
}

.notification-leave-active {
  animation: slideOut 0.5s ease-in;
}
</style> 