import type { Ingredient } from './ingredient.types';
import type { User } from './user.types';
import type { Step } from './step.types';

export interface Recipe {
    id: string;
    title: string;
    slug: string;
    servings?: number;
    description: string;
    ingredients: Ingredient[];
    steps: Step[];
    image?: string;
    userId?: string;
    user?: User; // Optional, to include user details if needed
    prepTime?: number; // in minutes
    cookTime?: number; // in minutes
}

export interface RecipeSearchParams {
    keyword?: string;
    ingredient?: string;
    email?: string;
}