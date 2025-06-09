<template>
    <div class="container mx-auto p-6">
      <NuxtLink to="/" class="mb-4 inline-block text-blue-600 hover:underline">&lt; Back to search</NuxtLink>
  
      <div v-if="loading" class="text-center">Loading recipe...</div>
  
      <div v-else-if="recipe" class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-bold mb-4">{{ recipe.title }}</h1>
        <NuxtLink :to="`${recipe.slug}/edit`" class="float-right">
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Edit Recipe</button>
        </NuxtLink>
        <img :src="recipe.image"  class="rounded shadow mb-6" />
        
        <h2 class="text-2xl font-semibold mb-2">Ingredients</h2>
        <ul class="list-disc ml-6 mb-6">
          <li v-for="(item, index) in recipe.ingredients" :key="index">
            {{ item.quantity }} {{ item.unit }} {{ item.name }}
          </li>
        </ul>
  
        <h2 class="text-2xl font-semibold mb-2">Instructions</h2>
        <ul class="list-none ml-6 mb-6">
          <li v-for="(item, index) in recipe.steps" :key="index">
            {{ item.order }}. {{ item.description }}
          </li>
        </ul>
  
      </div>
  
      <div v-else class="text-center">Recipe not found.</div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref } from 'vue'
  import { useRoute } from 'vue-router'
  import { fetchRecipeBySlug } from '@/services/recipe' // Adjust the import path as needed
  import type { Recipe } from '@/types/recipe.types' // Adjust the import path as needed
  
  const route = useRoute()
  
  let recipe:Recipe;
  const loading = ref(true)
  
  onBeforeMount(async () => {
    const slug = route.params.slug as string
    console.log('Recipe slug:', slug)
    if (slug) {
      recipe = await fetchRecipeBySlug(slug)
      loading.value = false
    } else {
    }
  })
  

  </script>