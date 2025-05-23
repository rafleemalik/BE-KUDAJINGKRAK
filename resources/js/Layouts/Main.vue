<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <header class="bg-white text-black shadow-lg">
        <nav class="flex items-center justify-between p-4 mx-auto max-w-screen-xl">
            <!-- Logo Ferrari -->
            <div class="flex items-center space-x-4">
                <img 
                    src="/public/images/logoFerrari.svg"
                    alt="Ferrari Logo" 
                    class="h-10"
                />
                <img 
                    src="/public/images/kudajingkrak.svg"
                    alt="Ferrari Logo" 
                    class="h-10"
                />
                <span class="text-2xl font-bold black-red-600">Jingkrak - Autosport</span>
            </div>

            <!-- Navigation -->
            <div>
                <Link :href="route('home')" class="text-white hover:text-red-500 transition">Home</Link>
            </div>
        </nav>
    </header>

    <main class="p-6 bg-gray-100 min-h-screen">
        <slot />
    </main>
</template>
