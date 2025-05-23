<script setup>
// Properti yang diterima dari controller akan otomatis tersedia dari prop page
import { usePage } from '@inertiajs/vue3';

// Dapatkan data dari prop page
const page = usePage();
const nama = page.props.nama;
const asal = page.props.asal;

// Alternatif: Menggunakan defineProps jika Anda ingin mendapatkan props langsung
// const props = defineProps({
//   nama: String,
//   asal: String
// });
</script>

<template>
  <!DOCTYPE html>
  <html>
    <head><title>Latihan 1</title></head>
    <body>
      Nama: {{ nama }} <br/>
      Asal: {{ asal }} <br/>
    </body>
  </html>
</template>

<style scoped>
body {
  margin: 0;
  padding: 0;
  overflow: hidden;
}
</style>