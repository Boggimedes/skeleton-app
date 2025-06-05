<template>
    <div class="recipe-page">
        <h1>{{ recipe.title }}</h1>
        <img :src="recipe.image" :alt="recipe.title" class="recipe-image" />
        <div class="recipe-details">
            <p><strong>Preparation Time:</strong> {{ recipe.prepTime }} minutes</p>
            <p><strong>Cooking Time:</strong> {{ recipe.cookTime }} minutes</p>
            <p><strong>Servings:</strong> {{ recipe.servings }}</p>
        </div>
        <div class="recipe-ingredients">
            <h2>Ingredients</h2>
            <ul>
                <li v-for="(ingredient, index) in recipe.ingredients" :key="index">
                    {{ ingredient }}
                </li>
            </ul>
        </div>
        <div class="recipe-instructions">
            <h2>Instructions</h2>
            <ol>
                <li v-for="(step, index) in recipe.instructions" :key="index">
                    {{ step }}
                </li>
            </ol>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            recipe: {
                title: "Spaghetti Bolognese",
                image: "https://via.placeholder.com/400",
                prepTime: 15,
                cookTime: 30,
                servings: 4,
                ingredients: [
                    "200g spaghetti",
                    "100g minced beef",
                    "1 onion, chopped",
                    "2 garlic cloves, minced",
                    "400g canned tomatoes",
                    "2 tbsp olive oil",
                    "Salt and pepper to taste",
                ],
                instructions: [
                    "Cook the spaghetti according to the package instructions.",
                    "Heat olive oil in a pan and sauté the onion and garlic until soft.",
                    "Add the minced beef and cook until browned.",
                    "Stir in the canned tomatoes and simmer for 15 minutes.",
                    "Season with salt and pepper.",
                    "Serve the sauce over the cooked spaghetti.",
                ],
            },
        };
    },
};
</script>

<style scoped>
.recipe-page {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    font-family: Arial, sans-serif;
}

.recipe-image {
    width: 100%;
    height: auto;
    margin-bottom: 20px;
}

.recipe-details {
    margin-bottom: 20px;
}

.recipe-ingredients,
.recipe-instructions {
    margin-bottom: 20px;
}

h1,
h2 {
    color: #333;
}
</style>