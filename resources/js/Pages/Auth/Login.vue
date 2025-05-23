<template>
  <transition name="slide">
    <div class="min-h-screen flex flex-col items-center justify-center bg-black text-white relative overflow-hidden">
      <!-- Logo Ferrari -->
      <div class="absolute top-6 flex justify-center w-full z-20">
        <img src="/public/images/logoFerrari.svg" alt="Ferrari Logo" class="w-24" />
      </div>

      <!-- Background Mobil dengan Animasi Zoom -->
      <div class="absolute inset-0 z-0">
        <img src="/public/images/lewis-hamilton-ferrari-sf-25.jpg" alt="Ferrari Car" class="object-cover w-full h-full opacity-30 bg-zoom-animation" />
      </div>

      <!-- Tulisan "WELCOME TO FERRARI!" -->
      <h1 class="text-6xl font-monsieur font-bold text-center mb-12 z-10">
        Welcome To Ferrari!
      </h1>

      <!-- Form -->
      <div class="z-10 w-full max-w-sm p-8 bg-black bg-opacity-80 rounded-xl shadow-lg">
        <form @submit.prevent="submit">
          <div class="mb-4">
            <input
              v-model="form.username"
              type="text"
              placeholder="Username"
              class="w-full p-3 rounded-lg bg-white text-black"
              required
            />
          </div>

          <div class="mb-6">
            <input
              v-model="form.password"
              type="password"
              placeholder="Password"
              class="w-full p-3 rounded-lg bg-white text-black"
              required
            />
          </div>

          <button
            type="submit"
            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg"
          >
            Login
          </button>
        </form>

        <p class="text-center text-sm mt-4">
          Don't have an account?
          <Link href="/register" class="text-red-400 hover:underline">Register</Link>
        </p>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

const form = useForm({
  username: '',
  password: ''
})

const submit = () => {
  form.post('/login')
}
</script>

<style scoped>
/* Font Import */
@import url('https://fonts.googleapis.com/css2?family=Monsieur+La+Doulaise&display=swap');

.font-monsieur {
  font-family: 'Monsieur La Doulaise', cursive;
  font-weight: 400;
}

/* Animasi zoom in & zoom out */
.bg-zoom-animation {
  animation: zoomEffect 15s ease-in-out infinite;
  transform-origin: center;
}

@keyframes zoomEffect {
  0% { transform: scale(1); }
  50% { transform: scale(1.2); }
  100% { transform: scale(1); }
}

/* Transition Slide Animation */
.slide-enter-active, .slide-leave-active {
  transition: all 2.0s ease;
}
.slide-enter-from {
  transform: translateX(100%);
  opacity: 3;
}
.slide-leave-to {
  transform: translateX(-100%);
  opacity: 3;
}
</style>