<template>
	<div class="container mx-auto p-6">
		<NuxtLink to="/" class="mb-4 inline-block text-blue-600 hover:underline">&lt; Back to search</NuxtLink>

		<div v-if="loading" class="text-center">Loading recipe...</div>

		<div v-else class="max-w-4xl mx-auto">
			<h1 class="text-4xl font-bold mb-4">{{ isNewRecipe ? 'New Recipe' : 'Edit Recipe' }}</h1>

			<form @submit.prevent="saveRecipe">
				<div class="mb-4">
					<label for="title" class="block font-semibold mb-2">Title</label>
					<input v-model="recipe.title" id="title" type="text" class="w-full border rounded p-2" />
				</div>

				<div class="mb-4">
					<label for="image" class="block font-semibold mb-2">Image URL</label>
					<input v-model="recipe.image" id="image" type="text" class="w-full border rounded p-2" />
				</div>

				<div class="mb-4">
					<label for="description" class="block font-semibold mb-2">Description</label>
					<textarea v-model="recipe.description" id="description" class="w-full border rounded p-2"></textarea>
				</div>

				<h2 class="text-2xl font-semibold mb-2">Ingredients</h2>
				<div v-for="(ingredient, index) in recipe.ingredients" :key="index" class="mb-4">
					<div class="flex gap-2">
						<input v-model="ingredient.quantity" placeholder="Quantity" type="text" class="border rounded p-2 flex-1" />
						<input v-model="ingredient.unit" placeholder="Unit" type="text" class="border rounded p-2 flex-1" />
						<input v-model="ingredient.name" placeholder="Ingredient" type="text" class="border rounded p-2 flex-2" />
						<button type="button" @click="removeIngredient(index)" class="text-red-600">Remove</button>
					</div>
				</div>
				<button type="button" @click="addIngredient" class="text-blue-600">+ Add Ingredient</button>

				<h2 class="text-2xl font-semibold mb-2 mt-6">Instructions</h2>
				<div v-for="(step, index) in recipe.steps" :key="index" class="mb-4">
					<div class="flex gap-2">
						<input v-model="step.order" placeholder="Step #" type="number" class="border rounded p-2 w-16" />
						<textarea v-model="step.description" placeholder="Instruction" class="border rounded p-2 flex-1"></textarea>
						<button type="button" @click="removeStep(index)" class="text-red-600">Remove</button>
					</div>
				</div>
				<button type="button" @click="addStep" class="text-blue-600">+ Add Step</button>

				<div class="mt-6">
					<button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save Recipe</button>
				</div>
			</form>
		</div>
	</div>
</template>

<script setup lang="ts">
import { ref, onBeforeMount } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { addRecipe, fetchRecipeBySlug, updateRecipe } from '@/services/recipe' // Adjust the import path as needed
import type { Recipe } from '@/types/recipe.types' // Adjust the import path as needed

const route = useRoute()

const router = useRouter()
const recipe = ref<Recipe>({
	id: '',
	title: '',
	image: '',
	description: '',
	ingredients: [],
	steps: [],
	slug: ''
})
const loading = ref(true)
const isNewRecipe = route.params.slug === 'new'

onBeforeMount(async () => {
	const slug = route.params.slug as string
	if (slug !== 'new') {
		recipe.value = await fetchRecipeBySlug(slug)
	}
	loading.value = false
})

const addIngredient = () => {
	recipe.value.ingredients.push({ quantity: 1, unit: '', name: '' })
}

const removeIngredient = (index: number) => {
	recipe.value.ingredients.splice(index, 1)
}

const addStep = () => {
	recipe.value.steps.push({ order: recipe.value.steps.length + 1, description: '' })
}

const removeStep = (index: number) => {
	recipe.value.steps.splice(index, 1)
}

const saveRecipe = async () => {
	const slug = route.params.slug as string
	if (slug === 'new') {
		await addRecipe(recipe.value) // Adjust the save logic as needed
	} else {
		await updateRecipe(slug, recipe.value) // Adjust the save logic as needed
	router.push(`/recipe/${recipe.value.slug}`) // Redirect to the recipe detail page after saving
	}
}
</script>