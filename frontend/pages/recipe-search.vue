<script>
import { fetchRecipes } from '../services/recipe';

export default {
  data() {
    return {
      query: '',
      recipes: [],
    };
  },
  methods: {
    async searchRecipes() {
      try {
        this.recipes = await fetchRecipes(this.query);
      } catch (error) {
        console.error('Error searching recipes:', error);
      }
    },
  },
};
</script>

<template>
  <div>
    <input v-model="query" placeholder="Search for recipes" />
    <button @click="searchRecipes">Search</button>
    <ul>
      <li v-for="recipe in recipes" :key="recipe.id">{{ recipe.name }}</li>
    </ul>
  </div>
</template>