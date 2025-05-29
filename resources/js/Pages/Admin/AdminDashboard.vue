<template>
  <transition name="page-transition" appear>
    <div class="fixed inset-0 flex flex-col bg-gradient-to-br from-black via-gray-900 to-black text-white overflow-hidden">
      <!-- Header -->
      <header class="flex items-center justify-between bg-gradient-to-r from-red-900 to-red-700 px-6 py-4 shadow-lg backdrop-blur-sm">
        <div class="flex items-center space-x-4">
          <img src="/public/images/logoFerrari.svg" alt="Ferrari Logo" class="w-12 h-12 transform hover:scale-110 transition-transform duration-300" />
          <h1 class="text-3xl font-bold tracking-wider bg-clip-text text-transparent bg-gradient-to-r from-white to-red-200">Ferrari Admin</h1>
        </div>
        <button
          @click="logout"
          class="bg-black/50 text-red-400 hover:text-white border border-red-600 hover:bg-red-800 transition-all duration-300 px-4 py-2 rounded-full font-semibold transform hover:scale-105 hover:shadow-lg hover:shadow-red-500/20"
        >
          <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </button>
      </header>

      <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-red-900 to-red-800 flex flex-col py-6 border-r border-red-700">
          <nav class="flex-1 px-4 space-y-2">
            <a href="#" class="flex items-center p-3 bg-red-800/50 rounded-lg hover:bg-red-700/50 transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-red-500/20 border border-red-700/50">
              <i class="fas fa-car mr-3 text-red-400"></i> Vehicles
            </a>
          </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-8">
          <!-- Create Form -->
          <div v-if="!editMode" class="bg-gray-800/50 p-8 rounded-xl shadow-lg mb-8 backdrop-blur-sm border border-gray-700/50 transform transition-all duration-300 hover:shadow-xl hover:shadow-red-500/10">
            <h2 class="text-2xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-white to-red-200">Add New Ferrari Car</h2>
            <form @submit.prevent="addCar" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block mb-2 text-gray-300">Car Model</label>
                  <input v-model="newCar.model" type="text" class="w-full p-3 rounded-lg bg-gray-700/50 text-white border border-gray-600 focus:border-red-500 focus:ring-2 focus:ring-red-500/50 transition-all duration-300" placeholder="e.g. Ferrari 488" required />
                </div>
                <div>
                  <label class="block mb-2 text-gray-300">Price</label>
                  <input 
                    v-model="newCar.price" 
                    type="number" 
                    @input="handlePriceInput" 
                    min="0" 
                    step="0.01"
                    class="w-full p-3 rounded-lg bg-gray-700/50 text-white border border-gray-600 focus:border-red-500 focus:ring-2 focus:ring-red-500/50 transition-all duration-300" 
                    placeholder="e.g. 300000" 
                    required 
                  />
                  <p v-if="errors.price" class="mt-2 text-red-400 text-sm animate-fade-in">{{ errors.price }}</p>
                </div>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block mb-2 text-gray-300">Type</label>
                  <select v-model="newCar.type" class="w-full p-3 rounded-lg bg-gray-700/50 text-white border border-gray-600 focus:border-red-500 focus:ring-2 focus:ring-red-500/50 transition-all duration-300" required>
                    <option value="">Select Type</option>
                    <option value="sport">Sport</option>
                    <option value="hybrid">Hybrid</option>
                    <option value="gt">GT</option>
                    <option value="suv">SUV</option>
                  </select>
                </div>
                <div>
                  <label class="block mb-2 text-gray-300">Car Image</label>
                  <input type="file" @change="handleImageUpload" class="w-full p-3 rounded-lg bg-gray-700/50 text-white border border-gray-600 focus:border-red-500 focus:ring-2 focus:ring-red-500/50 transition-all duration-300" accept="image/*" required />
                </div>
              </div>
              <div>
                <label class="block mb-2 text-gray-300">Specifications</label>
                <textarea v-model="newCar.specs" class="w-full p-3 rounded-lg bg-gray-700/50 text-white border border-gray-600 focus:border-red-500 focus:ring-2 focus:ring-red-500/50 transition-all duration-300" rows="4" placeholder="e.g. V8 Engine, 720HP, 0-100km/h in 2.9s" required></textarea>
              </div>
              <button type="submit" class="w-full bg-gradient-to-r from-red-700 to-red-600 hover:from-red-600 hover:to-red-500 text-white px-6 py-3 rounded-lg font-semibold transform transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-red-500/20">
                Add Car
              </button>
            </form>
          </div>

          <!-- Edit Form -->
          <div v-if="editMode" class="bg-gray-800/50 p-8 rounded-xl shadow-lg mb-8 backdrop-blur-sm border border-gray-700/50 transform transition-all duration-300 hover:shadow-xl hover:shadow-red-500/10">
            <h2 class="text-2xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-white to-red-200">Edit Ferrari</h2>
            <form @submit.prevent="updateCar" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block mb-2 text-gray-300">Car Model</label>
                  <input v-model="editCarData.model" type="text" class="w-full p-3 rounded-lg bg-gray-700/50 text-white border border-gray-600 focus:border-red-500 focus:ring-2 focus:ring-red-500/50 transition-all duration-300" required />
                </div>
                <div>
                  <label class="block mb-2 text-gray-300">Price</label>
                  <input 
                    v-model="editCarData.price" 
                    type="number" 
                    @input="(e) => handlePriceInput(e, true)" 
                    min="0" 
                    step="0.01"
                    class="w-full p-3 rounded-lg bg-gray-700/50 text-white border border-gray-600 focus:border-red-500 focus:ring-2 focus:ring-red-500/50 transition-all duration-300" 
                    required 
                  />
                  <p v-if="errors.price" class="mt-2 text-red-400 text-sm animate-fade-in">{{ errors.price }}</p>
                </div>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block mb-2 text-gray-300">Type</label>
                  <select v-model="editCarData.type" class="w-full p-3 rounded-lg bg-gray-700/50 text-white border border-gray-600 focus:border-red-500 focus:ring-2 focus:ring-red-500/50 transition-all duration-300" required>
                    <option value="">Select Type</option>
                    <option value="sport">Sport</option>
                    <option value="hybrid">Hybrid</option>
                    <option value="gt">GT</option>
                    <option value="suv">SUV</option>
                  </select>
                </div>
                <div>
                  <label class="block mb-2 text-gray-300">Car Image</label>
                  <input type="file" @change="handleImageUploadEdit" class="w-full p-3 rounded-lg bg-gray-700/50 text-white border border-gray-600 focus:border-red-500 focus:ring-2 focus:ring-red-500/50 transition-all duration-300" accept="image/*" />
                </div>
              </div>
              <div>
                <label class="block mb-2 text-gray-300">Specifications</label>
                <textarea v-model="editCarData.specs" class="w-full p-3 rounded-lg bg-gray-700/50 text-white border border-gray-600 focus:border-red-500 focus:ring-2 focus:ring-red-500/50 transition-all duration-300" rows="4" required></textarea>
              </div>
              <div class="flex space-x-4">
                <button type="submit" class="flex-1 bg-gradient-to-r from-blue-700 to-blue-600 hover:from-blue-600 hover:to-blue-500 text-white px-6 py-3 rounded-lg font-semibold transform transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-blue-500/20">
                  Update Car
                </button>
                <button @click.prevent="cancelEdit" class="flex-1 bg-gradient-to-r from-gray-700 to-gray-600 hover:from-gray-600 hover:to-gray-500 text-white px-6 py-3 rounded-lg font-semibold transform transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-gray-500/20">
                  Cancel
                </button>
              </div>
            </form>
          </div>
          
          <!-- List Table -->
          <div class="bg-gray-800/50 p-8 rounded-xl shadow-lg backdrop-blur-sm border border-gray-700/50 transform transition-all duration-300 hover:shadow-xl hover:shadow-red-500/10">
            <h2 class="text-2xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-white to-red-200">Ferrari Vehicles</h2>
            <!-- Search Input -->
            <div class="mb-6">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search by model..."
                class="w-full p-3 rounded-lg bg-gray-700/50 text-white border border-gray-600 focus:border-red-500 focus:ring-2 focus:ring-red-500/50 transition-all duration-300"
              />
            </div>
            <div class="overflow-x-auto">
              <table class="min-w-full">
                <thead>
                  <tr class="text-left">
                    <th class="py-4 px-4 text-gray-300 font-semibold">#</th>
                    <th class="py-4 px-4 text-gray-300 font-semibold">Model</th>
                    <th class="py-4 px-4 text-gray-300 font-semibold">Price</th>
                    <th class="py-4 px-4 text-gray-300 font-semibold">Type</th>
                    <th class="py-4 px-4 text-gray-300 font-semibold">Specifications</th>
                    <th class="py-4 px-4 text-gray-300 font-semibold">Image</th>
                    <th class="py-4 px-4 text-gray-300 font-semibold">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(car, index) in filteredCars" :key="car.id" class="border-t border-gray-700/50 hover:bg-gray-700/50 transition-all duration-300">
                    <td class="py-4 px-4">{{ index + 1 }}</td>
                    <td class="py-4 px-4">{{ car.name }}</td>
                    <td class="py-4 px-4">${{ car.price.toLocaleString() }}</td>
                    <td class="py-4 px-4">
                      <span class="px-3 py-1 rounded-full text-sm" 
                            :class="{
                              'bg-blue-500': car.type === 'sport',
                              'bg-green-500': car.type === 'hybrid',
                              'bg-yellow-500': car.type === 'gt',
                              'bg-purple-500': car.type === 'suv'
                            }">
                        {{ car.type.charAt(0).toUpperCase() + car.type.slice(1) }}
                      </span>
                    </td>
                    <td class="py-4 px-4">{{ car.specifications }}</td>
                    <td class="py-4 px-4">
                      <img :src="getImageUrl(car.image)" alt="Car Image" class="w-24 h-16 object-cover rounded-lg transform transition-transform duration-300 hover:scale-110" />
                    </td>
                    <td class="py-4 px-4 space-x-2">
                      <button @click="editCar(index)" class="bg-blue-600/50 hover:bg-blue-600 px-4 py-2 rounded-lg text-sm font-semibold transform transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-blue-500/20">
                        Edit
                      </button>
                      <button @click="deleteCar(index)" class="bg-red-600/50 hover:bg-red-600 px-4 py-2 rounded-lg text-sm font-semibold transform transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-red-500/20">
                        Delete
                      </button>
                    </td>
                  </tr>
                  <tr v-if="filteredCars.length === 0">
                    <td colspan="6" class="text-center py-8 text-gray-400">No cars found matching your search.</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </main>
      </div>
    </div>
  </transition>
</template>

<script setup>
import axios from 'axios'
import { ref, computed } from 'vue'

const searchQuery = ref('')
const newCar = ref({ model: '', price: '', specs: '', image: null, type: '' })
const cars = ref([])
const editMode = ref(false)
const editCarData = ref({ id: null, model: '', price: '', specs: '', image: null, type: '' })
const errors = ref({ price: '' })

// Fixed filtered cars implementation
const filteredCars = computed(() => {
  if (!searchQuery.value) return cars.value
  const query = searchQuery.value.toLowerCase()
  return cars.value.filter(car => 
    car.name && car.name.toLowerCase().includes(query)
  )
})

const validatePrice = (price) => {
  if (price < 0) {
    errors.value.price = 'Harga tidak boleh negatif'
    return false
  }
  errors.value.price = ''
  return true
}

const handlePriceInput = (event, isEdit = false) => {
  const value = parseFloat(event.target.value)
  if (isEdit) {
    editCarData.value.price = value
    validatePrice(value)
  } else {
    newCar.value.price = value
    validatePrice(value)
  }
}

const getImageUrl = (imagePath) => `http://127.0.0.1:8000/storage/${imagePath}`

const handleImageUpload = (event) => {
  newCar.value.image = event.target.files[0]
}

const handleImageUploadEdit = (event) => {
  editCarData.value.image = event.target.files[0]
}

const addCar = () => {
  if (!validatePrice(newCar.value.price)) return

  const formData = new FormData()
  formData.append('name', newCar.value.model)
  formData.append('price', newCar.value.price)
  formData.append('specifications', newCar.value.specs)
  formData.append('image', newCar.value.image)
  formData.append('type', newCar.value.type)

  axios.post('/api/cars', formData, {
    headers: { 'Content-Type': 'multipart/form-data' },
  }).then(res => {
    cars.value.push(res.data.car)
    newCar.value = { model: '', price: '', specs: '', image: null, type: '' }
    errors.value.price = ''
  }).catch(err => console.error('Error:', err.response.data))
}

const editCar = (index) => {
  const carToEdit = filteredCars.value[index]
  editCarData.value = {
    id: carToEdit.id,
    model: carToEdit.name,
    price: carToEdit.price,
    specs: carToEdit.specifications,
    type: carToEdit.type,
    image: null
  }
  editMode.value = true
}

const updateCar = () => {
  if (!validatePrice(editCarData.value.price)) return

  const formData = new FormData()
  formData.append('name', editCarData.value.model)
  formData.append('price', editCarData.value.price)
  formData.append('specifications', editCarData.value.specs)
  formData.append('type', editCarData.value.type)
  if (editCarData.value.image) {
    formData.append('image', editCarData.value.image)
  }
  formData.append('_method', 'PUT')

  axios.post(`/api/cars/${editCarData.value.id}`, formData, {
    headers: { 'Content-Type': 'multipart/form-data' },
  }).then(res => {
    const index = cars.value.findIndex(car => car.id === editCarData.value.id)
    if (index !== -1) {
      cars.value[index] = res.data.car
    }
    editMode.value = false
    editCarData.value = { id: null, model: '', price: '', specs: '', image: null, type: '' }
    errors.value.price = ''
  }).catch(err => console.error('Error updating car:', err.response?.data || err))
}

const cancelEdit = () => {
  editMode.value = false
  editCarData.value = { id: null, model: '', price: '', specs: '', image: null, type: '' }
}

const deleteCar = (index) => {
  const carToDelete = filteredCars.value[index]
  axios.delete(`/api/cars/${carToDelete.id}`).then(res => {
    if (res.status === 200) {
      // Remove from the original cars array
      const originalIndex = cars.value.findIndex(car => car.id === carToDelete.id)
      if (originalIndex !== -1) cars.value.splice(originalIndex, 1)
    }
  }).catch(err => console.error("Error deleting car:", err))
}

const fetchCars = async () => {
  try {
    const res = await axios.get('/api/cars')
    cars.value = res.data
  } catch (err) {
    console.error("Error fetching cars:", err)
  }
}

const logout = () => {
  axios.post('/logout').then(() => {
    window.location.href = '/login'
  }).catch(err => {
    console.error("Logout failed:", err)
  })
}

fetchCars()
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

/* Reset default body margin and padding */
body {
  margin: 0;
  padding: 0;
  overflow: hidden;
}

/* Page Transition Animation */
.page-transition-enter-active,
.page-transition-leave-active {
  transition: all 0.8s ease;
}

.page-transition-enter-from {
  opacity: 0;
  transform: translateY(20px);
}

.page-transition-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}

/* Error Message Animation */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fadeIn 0.3s ease-out forwards;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.1);
}

::-webkit-scrollbar-thumb {
  background: rgba(220, 38, 38, 0.5);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(220, 38, 38, 0.7);
}
</style>