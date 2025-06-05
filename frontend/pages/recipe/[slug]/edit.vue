<script setup lang="ts">
import { computed, definePageMeta, navigateTo, ref, useRoute } from '#imports';

import { Ref } from 'nuxt/dist/app/compat/vue-demi';
import { fetchRecipeBySlug, updateRecipe } from '~/services';
import { Recipe } from '~/types';

definePageMeta({
	middleware: [
		async () => {
			const route = useRoute();

			const { data } = await fetchRecipeBySlug(route.params.slug as string);

			if (!data || !data.value) return '/';
		},
	],
});

const route = useRoute();
const isLoading = ref(false);

const slug = computed(() => {
	return route.params.slug as string;
});

const { data } = await fetchRecipeBySlug(slug.value);

const handleSubmitForm = (newData: Ref<Recipe>) => {
	isLoading.value = true;

	return updateRecipe(newData.value)
		.then((res) => {
			const resData = res.data.value;
			const error = res.error.value;

			if (resData) {
                console.log('Recipe updated successfully:', resData);   
				navigateTo(`/recipe/${resData.slug}`);
			}

			if (error) {
				useNotify({
					message: error.message.split(ERROR_SEPARATOR).join('<br />'),
					type: 'error',
				});
			}

			return res;
		})
		.catch((err) => {
			useNotify({
				message: 'An error has occurred, please check them again.',
				type: 'error',
			});

			console.error(err);
		})
		.finally(() => {
			isLoading.value = false;
		});
};
</script>

<template>
	<Head>
		<Title>Nuxt3 Realworld | create new article</Title>
	</Head>

	<p class="text-h4 q-pb-lg">Edit Article:</p>

	<AddArticle
		:loading="isLoading"
		:body="data?.article.body"
		:description="data?.article.description"
		:tag-list="data?.article.tagList"
		:title="data?.article.title"
		@on-submit="handleSubmitForm"
	/>
</template>




<template>
    <div class="create-update-recipe">
        <h1>{{ isEditMode ? 'Update Recipe' : 'Create Recipe' }}</h1>
        <form @submit.prevent="handleSubmit">
            <div class="form-group">
                <label for="title">Title</label>
                <input
                    type="text"
                    id="title"
                    v-model="recipe.title"
                    placeholder="Enter recipe title"
                    required
                />
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea
                    id="description"
                    v-model="recipe.description"
                    placeholder="Enter recipe description"
                    required
                ></textarea>
            </div>
            <div class="form-group">
                <label for="ingredients">Ingredients</label>
                <textarea
                    id="ingredients"
                    v-model="recipe.ingredients"
                    placeholder="Enter ingredients (comma-separated)"
                    required
                ></textarea>
            </div>
            <div class="form-group">
                <label for="instructions">Instructions</label>
                <textarea
                    id="instructions"
                    v-model="recipe.instructions"
                    placeholder="Enter cooking instructions"
                    required
                ></textarea>
            </div>
            <button type="submit">{{ isEditMode ? 'Update' : 'Create' }}</button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            recipe: {
                title: '',
                description: '',
                ingredients: '',
                instructions: '',
            },
            isEditMode: false,
        };
    },
    created() {
        const recipeId = this.$route.params.id;
        if (recipeId) {
            this.isEditMode = true;
            this.fetchRecipe(recipeId);
        }
    },
    methods: {
        fetchRecipe(id) {
            // Replace with actual API call
            const mockRecipe = {
                title: 'Sample Recipe',
                description: 'This is a sample recipe.',
                ingredients: 'Ingredient1, Ingredient2',
                instructions: 'Step 1, Step 2',
            };
            this.recipe = mockRecipe;
        },
        handleSubmit() {
            if (this.isEditMode) {
                this.updateRecipe();
            } else {
                this.createRecipe();
            }
        },
        createRecipe() {
            // Replace with actual API call
            console.log('Creating recipe:', this.recipe);
            this.$router.push('/');
        },
        updateRecipe() {
            // Replace with actual API call
            console.log('Updating recipe:', this.recipe);
            this.$router.push('/');
        },
    },
};
</script>

<style scoped>
.create-update-recipe {
    max-width: 600px;
    margin: 0 auto;
}
.form-group {
    margin-bottom: 1rem;
}
label {
    display: block;
    margin-bottom: 0.5rem;
}
input,
textarea {
    width: 100%;
    padding: 0.5rem;
    margin-bottom: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 4px;
}
button {
    padding: 0.5rem 1rem;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
button:hover {
    background-color: #0056b3;
}
</style>