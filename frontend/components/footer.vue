<template>
    <footer class="bg-gray-100 text-center p-4 border-t mt-auto">
      <p class="text-gray-600">
        &copy; {{ new Date().getFullYear() }} Nuxt Recipe Finder
      </p>
    </footer>
  </template>
  
  <script setup lang="ts">
  const currentYear = new Date().getFullYear()
  </script>