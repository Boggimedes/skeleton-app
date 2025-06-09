<template>
    <header class="bg-blue-600 text-white p-4 shadow">
      <div class="container mx-auto flex items-center justify-between">
        <NuxtLink to="/" class="text-2xl font-bold hover:text-blue-200">
          Nuxt Recipe Finder
        </NuxtLink>
        <nav>
          <NuxtLink
            to="/"
            class="mr-4 hover:underline"
            active-class="underline decoration-2 underline-offset-4"
            exact
            >Home</NuxtLink
          >
          <NuxtLink
            to="/recipe/new/edit"
            class="mr-4 hover:underline"
            active-class="underline decoration-2 underline-offset-4"
            exact
            >New Recipe</NuxtLink
          >
          <!-- Add more nav links here if needed -->
        </nav>
      </div>
    </header>
  </template>
  
  <script setup lang="ts">
  // No script logic needed currently
  </script>