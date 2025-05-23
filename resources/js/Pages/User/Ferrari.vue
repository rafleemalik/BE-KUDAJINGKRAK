<template>
  <div class="min-h-screen bg-black text-white w-full overflow-x-hidden">
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
        
        <!-- Kategori Pencarian -->
        <div class="flex items-center gap-4">
          <button v-for="category in categories" 
                  :key="category.value"
                  @click="selectedCategory = category.value"
                  :class="[
                    'px-4 py-2 rounded-full transition-all',
                    selectedCategory === category.value 
                      ? 'bg-red-600 text-white' 
                      : 'bg-gray-800 text-gray-300 hover:bg-gray-700'
                  ]">
            {{ category.label }}
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

        <!-- Profile Photo Upload -->
        <div class="mb-6">
          <label class="block text-sm font-medium mb-2">Profile Photo</label>
          <div class="flex items-center gap-4">
            <img :src="user.profile_photo || '/images/leclerc.jpg'" 
                 alt="Profile" 
                 class="w-20 h-20 rounded-full object-cover border-2 border-red-500">
            <input type="file" 
                   accept="image/*" 
                   @change="handlePhotoUpload" 
                   class="hidden" 
                   ref="photoInput">
            <button @click="$refs.photoInput.click()" 
                    class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
              Change Photo
            </button>
          </div>
        </div>

        <!-- Edit Name -->
        <div class="mb-6">
          <label class="block text-sm font-medium mb-2">Name</label>
          <input v-model="user.name" 
                 type="text" 
                 class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 text-white">
        </div>

        <!-- Save Button -->
        <button @click="updateProfile" 
                class="w-full bg-red-600 hover:bg-red-700 text-white px-8 py-4 rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-red-500/50"
                :disabled="form.processing">
          {{ form.processing ? 'Saving...' : 'Save Changes' }}
        </button>

        <!-- Orders Section -->
        <div class="mb-6">
          <h3 class="text-xl font-semibold mb-4">My Orders</h3>
          <div v-if="orders.length > 0" class="space-y-4">
            <div v-for="order in orders" :key="order.id" class="bg-gray-800 p-4 rounded-lg">
              <div class="flex justify-between items-center">
                <div>
                  <h4 class="font-semibold">{{ order.car_name }}</h4>
                  <p class="text-gray-400">Order Date: {{ order.date }}</p>
                </div>
                <span :class="[
                  'px-3 py-1 rounded-full text-sm',
                  order.status === 'completed' ? 'bg-green-500' : 'bg-yellow-500'
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
            <div v-for="car in likedCars" :key="car.id" class="bg-gray-800 p-4 rounded-lg">
              <img :src="car.image" :alt="car.name" class="w-full h-32 object-cover rounded-lg mb-2">
              <h4 class="font-semibold">{{ car.name }}</h4>
              <p class="text-gray-400">{{ car.price }}</p>
            </div>
          </div>
          <p v-else class="text-gray-400">No liked cars yet</p>
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
          <h1 class="text-8xl font-bold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-white animate-fade-in">
            FERRARI SHOWROOM
          </h1>
          <p class="text-2xl mb-8 text-gray-300 animate-fade-in-delay">Where Dreams Meet Reality</p>
          <button class="bg-red-600 hover:bg-red-700 text-white px-12 py-4 rounded-full transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-red-500/50 animate-fade-in-delay-2">
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
          <div v-for="(feature, index) in features" :key="index" 
               class="bg-gray-900/50 p-8 rounded-xl transform transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-red-500/20 border border-gray-800"
               :class="`animate-fade-in-delay-${index + 3}`">
            <div class="text-red-600 text-5xl mb-6 transform transition-transform duration-300 hover:scale-110">{{ feature.icon }}</div>
            <h3 class="text-2xl font-semibold mb-4 text-white">{{ feature.title }}</h3>
            <p class="text-gray-400 leading-relaxed">{{ feature.description }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Gallery Section dengan Hover Effects -->
    <div class="py-20 w-full bg-gradient-to-b from-gray-900 to-black">
      <div class="max-w-7xl mx-auto px-4 md:px-8 w-full">
        <h2 class="text-5xl font-bold mb-16 text-center text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-white animate-fade-in">
          Our Showroom
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div v-for="car in filteredCars" :key="car.id" 
               class="relative group overflow-hidden rounded-xl animate-fade-in-delay-6">
            <img :src="getImageUrl(car.image)" 
                 :alt="car.name" 
                 class="w-full h-80 object-cover transform transition-transform duration-500 group-hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
              <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                <h3 class="text-xl font-bold mb-2">{{ car.name }}</h3>
                <p class="text-gray-300 mb-2">${{ car.price.toLocaleString() }}</p>
                <p class="text-gray-400 mb-4 text-sm">{{ car.specifications }}</p>
                <div class="flex gap-4">
                  <button class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-full transition-all duration-300 transform hover:scale-105">
                    Schedule Test Drive
                  </button>
                  <button @click="toggleLike(car)" 
                          class="bg-gray-800 hover:bg-gray-700 text-white p-2 rounded-full transition-all duration-300">
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
      </div>
    </div>

    <!-- Contact Section dengan Glass Effect -->
    <div class="py-20 px-4 md:px-8 w-full bg-gradient-to-b from-black to-gray-900">
      <div class="max-w-7xl mx-auto w-full">
        <h2 class="text-5xl font-bold mb-16 text-center text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-white animate-fade-in">
          Book Your Visit
        </h2>
        <div class="max-w-md mx-auto w-full animate-fade-in-delay-7">
          <form class="space-y-6 bg-gray-900/50 p-8 rounded-xl border border-gray-800 backdrop-blur-sm">
            <div>
              <label class="block text-sm font-medium mb-2 text-gray-300">Full Name</label>
              <input type="text" class="w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 text-white placeholder-gray-400" placeholder="Enter your full name">
            </div>
            <div>
              <label class="block text-sm font-medium mb-2 text-gray-300">Email Address</label>
              <input type="email" class="w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 text-white placeholder-gray-400" placeholder="Enter your email">
            </div>
            <div>
              <label class="block text-sm font-medium mb-2 text-gray-300">Preferred Model</label>
              <select class="w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 text-white">
                <option value="">Select a Ferrari model</option>
                <option value="sf90">SF90 Stradale</option>
                <option value="roma">Roma</option>
                <option value="f8">F8 Tributo</option>
                <option value="portofino">Portofino M</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium mb-2 text-gray-300">Message</label>
              <textarea class="w-full px-4 py-3 bg-gray-800/50 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 text-white placeholder-gray-400" rows="4" placeholder="Tell us about your interest in Ferrari"></textarea>
            </div>
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-8 py-4 rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-red-500/50">
              Request Appointment
            </button>
          </form>
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
const orders = ref([
  {
    id: 1,
    car_name: 'Ferrari SF90 Stradale',
    date: '2024-03-15',
    status: 'completed'
  },
  {
    id: 2,
    car_name: 'Ferrari Roma',
    date: '2024-03-20',
    status: 'pending'
  }
])

// Liked cars
const likedCars = ref([])

// Cars data with categories
const cars = ref([])

// Fungsi untuk mengambil data mobil
const fetchCars = async () => {
  try {
    const response = await axios.get('/api/cars')
    cars.value = response.data
  } catch (error) {
    console.error('Error fetching cars:', error)
  }
}

// Filter cars based on selected category
const filteredCars = computed(() => {
  if (selectedCategory.value === 'all') {
    return cars.value
  }
  return cars.value.filter(car => car.type === selectedCategory.value)
})

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
    description: 'Experience the pinnacle of automotive engineering with our high-performance vehicles.'
  },
  {
    icon: '✨',
    title: 'Luxury Redefined',
    description: 'Indulge in the finest craftsmanship and premium materials that define Ferrari luxury.'
  },
  {
    icon: '🌟',
    title: 'Heritage & Innovation',
    description: 'A perfect blend of rich racing heritage and cutting-edge technology.'
  }
]

// Mengambil data mobil saat komponen dimount
onMounted(() => {
  fetchCars()
})
</script>

<style scoped>
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
</style> 