<template>
  <transition name="fade">
    <div class="min-h-screen flex flex-col items-center justify-center bg-black text-white relative overflow-hidden">
      <!-- Logo Ferrari -->
      <div class="absolute top-6 flex justify-center w-full z-20">
        <img src="/public/images/logoFerrari.svg" alt="Ferrari Logo" class="w-24" />
      </div>

      <!-- Background Mobil dengan Animasi -->
      <div class="absolute inset-0 z-0">
        <img src="/public/images/bgFerrari.svg" alt="Ferrari Car" class="object-cover w-full h-full opacity-30 bg-animate" />
      </div>

      <!-- Tulisan "REGISTER TO FERRARI!" -->
      <h1 class="text-5xl font-monsieur font-bold text-center mb-12 z-10">
        Register To Ferrari!
      </h1>

      <!-- TOAST NOTIFICATION -->
      <transition name="toast">
        <div v-if="flashMessage" class="fixed top-6 right-6 bg-green-600 text-white px-4 py-2 rounded-md shadow-lg z-50">
          {{ flashMessage }}
        </div>
      </transition>

      <!-- Form -->
      <div class="z-10 w-full max-w-sm p-8 bg-black bg-opacity-80 rounded-xl shadow-lg">
        <form @submit.prevent="submit">
          <div class="mb-4">
            <input v-model="form.username" type="text" placeholder="Username" class="w-full p-3 rounded-lg bg-white text-black" required />
            <div v-if="form.errors.username" class="text-red-400 text-sm mt-1">{{ form.errors.username }}</div>
          </div>

          <div class="mb-4">
            <input v-model="form.password" type="password" placeholder="Password" class="w-full p-3 rounded-lg bg-white text-black" required />
            <div v-if="form.errors.password" class="text-red-400 text-sm mt-1">{{ form.errors.password }}</div>
          </div>

          <div class="mb-6">
            <input v-model="form.password_confirmation" type="password" placeholder="Confirm Password" class="w-full p-3 rounded-lg bg-white text-black" required />
            <div v-if="form.errors.password_confirmation" class="text-red-400 text-sm mt-1">{{ form.errors.password_confirmation }}</div>
          </div>

          <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg" :disabled="form.processing">
            Register
          </button>
        </form>

        <p class="text-center text-sm mt-4">
          Already have an account?
          <Link href="/login" class="text-red-400 hover:underline">Login</Link>
        </p>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import { ref, watchEffect } from 'vue'

const form = useForm({
  username: '',
  password: '',
  password_confirmation: ''
})

// Tangkap flash message dari backend
const flashMessage = ref(usePage().props.flash?.success || '')

// Hilangkan toast setelah 3 detik
watchEffect(() => {
  if (flashMessage.value) {
    setTimeout(() => {
      flashMessage.value = ''
    }, 3000)
  }
})

const submit = () => {
  form.post('/register')
}
</script>

<style scoped>
/* Font Import */
@import url('https://fonts.googleapis.com/css2?family=Monsieur+La+Doulaise&display=swap');

.font-monsieur {
  font-family: 'Monsieur La Doulaise', cursive;
  font-weight: 400;
}

/* Animasi background */
.bg-animate {
  animation: slideBackground 10s ease-in-out infinite;
}

@keyframes slideBackground {
  0% { transform: translateX(0); }
  50% { transform: translateX(-50%); }
  100% { transform: translateX(0); }
}

/* Transition animasi */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.8s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

/* TOAST NOTIFICATION ANIMATION */
.toast-enter-active, .toast-leave-active {
  transition: all 0.5s ease;
}
.toast-enter-from, .toast-leave-to {
  transform: translateY(-20px);
  opacity: 0;
}
</style>
  