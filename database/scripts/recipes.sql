INSERT INTO recipes (id, title, description, created_at, updated_at, slug, user_id)
VALUES
(1, 'Spaghetti Bolognese', 'A classic Italian pasta dish with rich meat sauce.', NOW(), NOW(), 'spaghetti-bolognese',1),
(2, 'Chicken Curry', 'A flavorful and spicy chicken curry.', NOW(), NOW(), 'chicken-curry',1),
(3, 'Vegetable Stir Fry', 'A quick and healthy vegetable stir fry.', NOW(), NOW(), 'vegetable-stir-fry',2),
(4, 'Beef Tacos', 'Mexican-style beef tacos with fresh toppings.', NOW(), NOW(), 'beef-tacos',2),
(5, 'Margherita Pizza', 'Traditional pizza with tomato, mozzarella, and basil.', NOW(), NOW(), 'margherita-pizza',3),
(6, 'The Best Recipe Ever', 'This is the best recipe ever created, with a perfect balance of flavors and textures.', NOW(), NOW(), 'the-best-recipe-ever', 4);