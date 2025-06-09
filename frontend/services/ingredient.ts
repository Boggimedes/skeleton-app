import { useAPI } from '@/composables/useAPI';
import type { Ingredient } from '~/types/ingredient.types';

const API_BASE_URL = '/ingredients';

/**
 * Fetch all ingredients from the API.
 * @returns {Promise<Array>} A promise that resolves to an array of ingredients.
 */
export const fetchIngredients = async (partial:string) => {
    try {
        const response = await useAPI(`${API_BASE_URL}/${partial}`);
        return response;
    } catch (error) {
        console.error('Error fetching ingredients:', error);
        throw error;
    }
};

/**
 * Fetch a single ingredient by its ID.
 * @param {string} id - The ID of the ingredient.
 * @returns {Promise<Object>} A promise that resolves to the ingredient object.
 */
export const fetchIngredientById = async (id:number) => {
    try {
        const response = await useAPI(`${API_BASE_URL}/${id}`);
        return response;
    } catch (error) {
        console.error(`Error fetching ingredient with ID ${id}:`, error);
        throw error;
    }
};

/**
 * Add a new ingredient to the database.
 * @param {Object} ingredient - The ingredient data to add.
 * @returns {Promise<Object>} A promise that resolves to the added ingredient.
 */
export const addIngredient = async (recipeId: number, ingredient: Ingredient) => {
    try {
        const response = await useAPI(`${API_BASE_URL}/recipe/${recipeId}/ingredient`, { method: 'POST' , body: ingredient });

        return response;
    } catch (error) {
        console.error('Error adding ingredient:', error);
        throw error;
    }
};

/**
 * Update an existing ingredient by its ID.
 * @param {string} id - The ID of the ingredient to update.
 * @param {Object} updates - The updated ingredient data.
 * @returns {Promise<Object>} A promise that resolves to the updated ingredient.
 */
export const updateIngredient = async (ingredientId: number, updates:Ingredient) => {
    try {
        const response = await useAPI(`${API_BASE_URL}/ingredient/${ingredientId}`, { method: 'PUT' , body: updates });
        return response;
    } catch (error) {
        console.error(`Error updating ingredient with ID ${ingredientId}:`, error);
        throw error;
    }
};

/**
 * Delete an ingredient by its ID.
 * @param {string} id - The ID of the ingredient to delete.
 * @returns {Promise<void>} A promise that resolves when the ingredient is deleted.
 */
export const deleteIngredient = async (ingredientId: number) => {
    try {
        const response = await useAPI(`${API_BASE_URL}/ingredient/${ingredientId}`, { method: 'DELETE'});
        return response;
    } catch (error) {
        console.error(`Error updating ingredient with ID ${ingredientId}:`, error);
        throw error;
    }
};