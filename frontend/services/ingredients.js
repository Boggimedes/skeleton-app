// ingredients.js
// Service for managing ingredients-related API calls

import axios from 'axios';

const API_BASE_URL = '/ingredients';

/**
 * Fetch all ingredients from the API.
 * @returns {Promise<Array>} A promise that resolves to an array of ingredients.
 */
export const fetchIngredients = async (partial) => {
    try {
        const response = await axios.get(`${API_BASE_URL}/${partial}`);
        return response.data;
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
export const fetchIngredientById = async (id) => {
    try {
        const response = await axios.get(`${API_BASE_URL}/${id}`);
        return response.data;
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
export const addIngredient = async (ingredient) => {
    try {
        const response = await axios.post(API_BASE_URL, ingredient);
        return response.data;
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
export const updateIngredient = async (id, updates) => {
    try {
        const response = await axios.put(`${API_BASE_URL}/${id}`, updates);
        return response.data;
    } catch (error) {
        console.error(`Error updating ingredient with ID ${id}:`, error);
        throw error;
    }
};

/**
 * Delete an ingredient by its ID.
 * @param {string} id - The ID of the ingredient to delete.
 * @returns {Promise<void>} A promise that resolves when the ingredient is deleted.
 */
export const deleteIngredient = async (id) => {
    try {
        await axios.delete(`${API_BASE_URL}/${id}`);
    } catch (error) {
        console.error(`Error deleting ingredient with ID ${id}:`, error);
        throw error;
    }
};