import { useAPI } from '@/composables/useAPI';
import type { Recipe } from '~/types/recipe.types';
import type { RecipeSearchParams } from '~/types/recipe.types';

const API_BASE_URL = '/recipe'; // Replace with your actual API URL

export const fetchRecipes = async (recipeSearchParams: RecipeSearchParams) => {
  try {
    console.log('Fetching recipes with params:', recipeSearchParams);
    const response = await useAPI(`${API_BASE_URL}s/search`, {
      params: recipeSearchParams,
    });
    return response as unknown as Recipe[];
  } catch (error) {
    console.error('Error fetching recipes:', error);
    throw error;
  }
};

export const fetchRecipeBySlug = async (slug:string) => {
  try {
    console.log('Fetching recipe by slug:', slug);
    const response = await useAPI(`${API_BASE_URL}/${slug}`);
    return response as unknown as Recipe;
  } catch (error) {
    console.error('Error fetching recipe by Slug:', error);
    throw error;
  }
};

export const addRecipe = async (recipe: Recipe) => {
  try {
    const response = await useAPI(API_BASE_URL, { method: 'POST' , body: recipe });
    return response as unknown as Recipe;
  } catch (error) {
    console.error('Error adding recipe:', error);
    throw error;
  }
};

export const updateRecipe = async (slug:string , updates: Recipe) => {
  try {
    const response = await useAPI(`${API_BASE_URL}/${slug}`, { method: 'PUT' , body: updates });
    return response as unknown as Recipe;
  } catch (error) {
    console.error('Error updating recipe:', error);
    throw error;
  }
};