import { Ingredient } from './ingredient.types';
import { User } from './user.types';
import { Step } from './step.types';

export interface Recipe {
    id: string;
    title: string;
    description: string;
    ingredients: Ingredient[];
    steps: Step[];
    image?: string;
    userId: string;
    user?: User; // Optional, to include user details if needed
    prepTime?: number; // in minutes
    cookTime?: number; // in minutes
}

