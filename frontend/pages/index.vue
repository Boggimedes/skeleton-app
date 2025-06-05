<template>
    <div class="home">
        <header class="header">
            <h1>Recipe Search App</h1>
            <p>Find the perfect recipe for any occasion!</p>
        </header>
        <section class="search-section">
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Search for recipes..."
                @keyup.enter="searchRecipes"
            />
            <button @click="searchRecipes">Search</button>
        </section>
        <section v-if="paginatedRecipes.length" class="results-section">
            <h2>Search Results</h2>
            <ul>
                <li v-for="recipe in paginatedRecipes" :key="recipe.id">
                    <h3>{{ recipe.title }}</h3>
                    <p>{{ recipe.description }}</p>
                </li>
            </ul>
            <div class="pagination">
                <button @click="prevPage" :disabled="currentPage === 1">Previous</button>
                <span>Page {{ currentPage }} of {{ totalPages }}</span>
                <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>
            </div>
        </section>
        <section v-else-if="searchPerformed" class="no-results">
            <p>No recipes found. Try a different search term.</p>
        </section>
    </div>
</template>

<script>
export default {
    data() {
        return {
            searchQuery: '',
            recipes: [],
            searchPerformed: false,
            currentPage: 1,
            itemsPerPage: 5,
        };
    },
    computed: {
        totalPages() {
            return Math.ceil(this.recipes.length / this.itemsPerPage);
        },
        paginatedRecipes() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.recipes.slice(start, end);
        },
    },
    methods: {
        async searchRecipes() {
            this.searchPerformed = true;
            this.currentPage = 1; // Reset to the first page on new search
            // Simulate an API call
            const mockRecipes = [
                { id: 1, title: 'Spaghetti Bolognese', description: 'A classic Italian dish.' },
                { id: 2, title: 'Chicken Curry', description: 'A spicy and flavorful curry.' },
                { id: 3, title: 'Beef Stroganoff', description: 'A creamy and hearty dish.' },
                { id: 4, title: 'Vegetable Stir Fry', description: 'A quick and healthy meal.' },
                { id: 5, title: 'Fish Tacos', description: 'A fresh and zesty treat.' },
                { id: 6, title: 'Pancakes', description: 'Perfect for breakfast or brunch.' },
                { id: 7, title: 'Caesar Salad', description: 'A classic salad with a tangy dressing.' },
            ];
            this.recipes = mockRecipes.filter((recipe) =>
                recipe.title.toLowerCase().includes(this.searchQuery.toLowerCase())
            );
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
    },
};
</script>

<style scoped>
.home {
    text-align: center;
    padding: 20px;
}

.header {
    margin-bottom: 20px;
}

.search-section {
    margin-bottom: 20px;
}

input {
    padding: 10px;
    width: 300px;
    margin-right: 10px;
}

button {
    padding: 10px 20px;
}

.results-section ul {
    list-style: none;
    padding: 0;
}

.results-section li {
    margin-bottom: 15px;
}

.pagination {
    margin-top: 20px;
}

.pagination button {
    padding: 5px 10px;
    margin: 0 5px;
}

.no-results {
    color: gray;
}
</style>