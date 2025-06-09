<template>
  <div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Recipe Finder</h1>
    <form @submit.prevent="searchRecipes" class="mb-6 space-y-4 max-w-xl">
      <div>
        <label for="keyword" class="block font-semibold mb-1">Keyword</label>
        <input
          id="keyword"
          v-model="keyword"
          type="text"
          placeholder="Search by keyword..."
          class="w-full p-2 border rounded"
        />
      </div>
      <div>
        <label for="ingredient" class="block font-semibold mb-1">Ingredient</label>
        <input
          id="ingredient"
          v-model="ingredient"
          type="text"
          placeholder="Search by ingredient..."
          class="w-full p-2 border rounded"
        />
      </div>
      <div>
        <label for="authorEmail" class="block font-semibold mb-1">Author Email</label>
        <input
          id="authorEmail"
          v-model="email"
          type="email"
          placeholder="Filter by author email..."
          class="w-full p-2 border rounded"
        />
      </div>
      <button
        type="submit"
        class="mt-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        Search
      </button>
    </form>

    <div v-if="loading" class="text-center">Loading...</div>

    <div v-if="!recipeStore.recipes || (recipeStore.recipes?.length === 0 && !loading)" class="text-center pt-6">
      No recipes found.
    </div>

    <ul v-if="recipeStore.recipes && recipeStore.recipes?.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <li
        v-for="recipe in recipeStore.recipes"
        :key="recipe.id"
        class="border rounded overflow-hidden shadow hover:shadow-lg transition"
      >
        <NuxtLink :to="`/recipe/${recipe.slug}`">
          <img :src="recipe.image" :alt="recipe.title" class="w-full h-48 object-cover" />
          <div class="p-4">
            <h2 class="font-semibold text-lg">{{ recipe.title }}</h2>
            <p v-if="recipe.user" class="text-sm text-gray-500">By: {{ recipe.user?.email }}</p>
          </div>
        </NuxtLink>
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRecipeStore, useSearchStore } from '~/store'
import { fetchRecipes } from '~/services/recipe'
import type { Recipe } from '~/types/recipe.types'

const loading = ref(false)
const keyword = computed({
  get: () => useSearchStore().keyword,
  set: (value) => useSearchStore().setKeyword(value)
})
const ingredient = computed({
  get: () => useSearchStore().ingredient,
  set: (value) => useSearchStore().setIngredient(value)
})
const email = computed({
  get: () => useSearchStore().email,
  set: (value) => useSearchStore().setEmail(value)
})

const recipeStore = useRecipeStore()


const searchRecipes = () => {
  loading.value = true

  recipeStore.setRecipes([]) // Clear previous recipes in store
  fetchRecipes({ keyword: keyword.value, ingredient: ingredient.value, email: email.value })
    .then((recipes: Recipe[]) => {
      console.log('Fetched recipes:', recipes)
      recipeStore.setRecipes(recipes)
    })
    .catch((error) => {
      console.error('Error fetching recipes:', error)
    })
    .finally(() => {
      loading.value = false
    })
}

</script>