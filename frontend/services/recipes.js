// filepath: /Users/danielboggs/Documents/interview-recipe-search/frontend/services/recipes.js
import axios from 'axios';

const API_BASE_URL = '/recipes'; // Replace with your actual API URL

export const fetchRecipes = async (query) => {
  try {
    const response = await axios.get(`${API_BASE_URL}/search`, {
      params: { query },
    });
    return response.data;
  } catch (error) {
    console.error('Error fetching recipes:', error);
    throw error;
  }
};

export const fetchRecipeBySlug = async (slug) => {
  try {
    const response = await axios.get(`${API_BASE_URL}/${slug}`);
    return response.data;
  } catch (error) {
    console.error('Error fetching recipe by Slug:', error);
    throw error;
  }
};

export const addRecipe = async (recipe) => {
  try {
    const response = await axios.post(API_BASE_URL, recipe);
    return response.data;
  } catch (error) {
    console.error('Error adding recipe:', error);
    throw error;
  }
};

export const updateRecipe = async (slug, updates) => {
  try {
    const response = await axios.put(`${API_BASE_URL}/${slug}`, updates);
    return response.data;
  } catch (error) {
    console.error('Error updating recipe:', error);
    throw error;
  }
};