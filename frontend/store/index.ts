import { defineStore } from 'pinia'
import type { RecipeSearchParams } from '~/types/recipe.types'
import type { Recipe } from '~/types/recipe.types'

export const useRecipeStore = defineStore('recipe', {
  state: () => ({
    recipes: [] as Recipe[],
    lastSearch: {
      keyword: '',
      ingredient: '',
      email: '',
    } as RecipeSearchParams,
  }),
  actions: {
    setRecipes(recipes: any[]) {
      this.recipes = recipes
    },
    setSearch({keyword, ingredient, email}: {keyword: string, ingredient: string, email: string}) {
      this.lastSearch = {keyword, ingredient, email}
    },
  },
})

export const useSearchStore = defineStore('search', {
  state: () => ({
      keyword: '',
      ingredient: '',
      email: '',
  }),
  actions: {
    setSearch(recipeSearchParams: RecipeSearchParams) {
      const { keyword, ingredient, email } = recipeSearchParams
      this.keyword = keyword ? keyword : ''
      this.ingredient = ingredient ? ingredient : ''
      this.email = email ? email : ''
    },
    setKeyword(keyword: string) {
      this.keyword = keyword ? keyword : ''
    },
    setIngredient(ingredient: string) {
      this.ingredient = ingredient ? ingredient : ''
    },
    setEmail(email: string) {
      this.email = email ? email : ''
    },
  },
})