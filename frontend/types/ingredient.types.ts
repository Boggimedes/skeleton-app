export interface Ingredient {
    id?: string;
    name: string;
    type?: string;
    measurement_unit?: string;
    unit?: string;
    serving_quantity?: number;
    quantity?: number;
    recipeId?: string;
    calories?: number;
    protein?: number;
    fat?: number;
    carbs?: number;
}