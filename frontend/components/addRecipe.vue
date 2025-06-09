<script setup lang="ts">
import { computed, ref } from '#imports';
// import { marked } from 'marked';
import { Ref } from 'nuxt/dist/app/compat/vue-demi';
// import sanitizeHtml from 'sanitize-html';
import { Recipe, Ingredient, Step } from '~/types';
import { validator } from '~/utils';

const props = withDefaults(defineProps<Partial<Recipe> & { loading: boolean }>(), {
	title: '',
	description: '',
	steps: [],
    ingredients: [],
});

const emit = defineEmits<{
	(e: 'onSubmit', data: Ref<Recipe>): void;
}>();
const ingredientValue = ref({ name: '', quantity: 1, unit: 'each' });
const ingredientInput = ref<HTMLInputElement>();

const stepValue = ref({ name: '', order: '', description: '' });
const stepInput = ref<HTMLInputElement>();

const refValidation = ref<Record<keyof Recipe, ErrorValidation>>({
	title: {
		hasError: false,
		message: undefined,
	},
	description: {
		hasError: false,
		message: undefined,
	},
	steps: {
		hasError: false,
		message: undefined,
	},
	ingredients: {
		hasError: false,
		message: undefined,
	},
});

const data = ref<Recipe>({
	title: props.title,
	description: props.description,
	ingredients: props.ingredients || [],
    steps: props.steps || [],
});

const titleValidation = computed(() => {
	const title = data.value.title;

	return () =>
		validator({
			validate: [
				{
					condition: title.length === 0,
					message: 'Please enter the recipe title',
				},
				{
					condition: title.length <= 5,
					message: 'A minimum of 5 characters must be included in the recipe title',
				},
			],
			ref: refValidation.value.title,
		});
});

const descriptionValidation = computed(() => {
	const description = data.value.description;

	return () =>
		validator({
			validate: [
				{
					condition: description.length === 0,
					message: 'Please enter the recipe description',
				},
				{
					condition: description.length <= 5,
					message: 'A minimum of 5 characters must be included in the recipe description',
				},
			],
			ref: refValidation.value.description,
		});
});

const ingredientListValidation = computed(() => {
	const ingredients = data.value.ingredients;

	return () =>
		validator({
			validate: [
				{
					condition: ingredients.length === 0,
					message: 'A minimum of 1 ingredient is required for a recipe',
				},
				{
					condition: ingredientList.includes(ingredientValue.name.trim()),
					message: 'Duplicate ingredient entered',
				},
			],
			ref: refValidation.value.ingredientList,
		});
});


const stepListValidation = computed(() => {
	const stepList = data.value.stepList;

	return () =>
		validator({
			validate: [
				{
					condition: stepList.length === 0,
					message: 'A minimum of 1 step is required for this recipe',
				},
				{
					condition: stepList.includes(stepValue.value.trim()),
					message: 'Duplicate step entered',
				},
			],
			ref: refValidation.value.stepList,
		});
});

const handleAddIngredients = () => {
	const value = ingredientValue.value.trim();
	const ingredientList = data.value.ingredientList;

	if (value.length === 0 || ingredientList.includes(value)) return;

	ingredientValue.value = '';
	data.value.ingredientList.push(value);
};

const handleAddSteps = () => {

	const stepList = data.value.stepList;

	if (value.length === 0 || stepList.includes(value)) return;

	stepValue.value = '';
	data.value.stepList.push(value);
};

const handleDeleteTags = (value: string) => {
	data.value.tagList = data.value.tagList.filter((tag) => tag !== value);
};

const previewMarkdown = computed(() => {
	if (!isShowPreviewMarkdown.value || data.value.body.length === 0 || refValidation.value.body.hasError) return;

	const body = data.value.body.replace(/\\n/gi, '<br />');

	return sanitizeHtml(marked.parse(body, { mangle: false, headerIds: false }));
});

const handleSubmitForm = computed(() => {
	return () => {
		const hasTitleError = titleValidation.value();
		const hasDescriptionError = descriptionValidation.value();
		const hasBodyError = bodyValidation.value();
		const hasTagListError = tagListValidation.value();

		if (hasTitleError || hasDescriptionError || hasBodyError || hasTagListError) return;

		emit('onSubmit', data);
	};
});
</script>

<template>
	<q-form :class="$style.addRecipe" class="row q-col-gutter-lg" autocomplete="off">
		<q-input
			v-model="data.title"
			filled
			class="text-body1 col-12"
			type="text"
			inputmode="text"
			label="Recipe Title"
			name="title"
			:error-message="refValidation.title.message"
			:error="refValidation.title.hasError"
			no-error-icon
			:disable="props.loading"
			@blur="titleValidation"
		/>

		<q-input
			v-model="data.description"
			filled
			class="text-body1 col-12"
			type="text"
			inputmode="text"
			label="Describe the dish?"
			name="description"
			:error-message="refValidation.description.message"
			:error="refValidation.description.hasError"
			no-error-icon
			:disable="props.loading"
			@blur="descriptionValidation"
		/>

		<div class="col-12">
			<q-input
				ref="ingredientInput"
				v-model="ingredient.name"
				filled
				class="text-body1"
				:class="$style.tagsWrapper"
				type="text"
				inputmode="text"
				label="Enter tags (Press enter to add each tag)"
				name="tags"
				:disable="props.loading"
				:error-message="refValidation.tagList.message"
				:error="refValidation.tagList.hasError"
				no-error-icon
				@keyup.enter="
					() => {
						handleAddTags();
						tagListValidation();
					}
				"
				@keyup.delete="
					() => {
						if (data.tagList.length === 0 || tagValue.length !== 0) return;
						data.tagList.splice(-1);
					}
				"
				@blur="tagListValidation"
			>
				<template #prepend>
					<q-list
						v-if="data.tagList.length !== 0"
						tag="ul"
						dense
						class="flex inline row wrap text-body2 items-center justify-start"
						@click="ingredientInput?.focus()"
					>
						<q-item v-for="(item, index) in data.tagList" :key="index" dense tag="li">
							<q-btn dense flat no-caps :icon="fasTimes" :label="item" @click="handleDeleteTags(item)" />
						</q-item>
					</q-list>
				</template>
			</q-input>
		</div>

		<div class="col-12">
			<q-btn
				label="Publish recipe"
				class="full-width"
				size="21.8px"
				no-caps
				type="button"
				:loading="props.loading"
				@click.prevent="handleSubmitForm"
			/>
		</div>
	</q-form>

	<Modal :show="isShowPreviewMarkdown" :hide="() => (isShowPreviewMarkdown = false)" title="Preview Markdown">
		<div :class="$style.previewMarkdown" v-html="previewMarkdown" />
	</Modal>
</template>

<style lang="scss" module>
.addrecipe {
	:global {
		.q-field {
			textarea {
				line-height: 1.65rem;
				height: 188px;
				min-height: 188px !important;
				padding: 0;
				resize: none !important;
			}
		} // .q-field

		.q-field__prepend {
			height: auto;
		}

		.q-btn {
			background-color: #d1caff;
			color: #202433;
		} // .q-btn
	} // :global
} // .changeSettings

.bodyWrapper {
	:global {
		.q-field__append {
			position: absolute;
			top: auto;
			bottom: 0;
			right: 12px;
			z-index: 10;
			padding: 0;
			pointer-events: none;
		} // .q-field__append

		.q-btn {
			pointer-events: auto;
		}
	}
} // .bodyWrapper

.tagsWrapper {
	:global {
		.q-field__control {
			height: auto;
		}

		.q-field__prepend {
			max-width: 75%;
			padding-right: 0;
		}
		.q-list {
			gap: 8px;
			padding: 10px 10px 10px 0;

			.q-item {
				min-height: auto;
				padding: 0;
				position: relative;
			} // .q-item

			.q-btn {
				padding: 0 5px 0 7px;
			}

			.on-left {
				margin-right: 3px;
			}

			.q-icon {
				font-size: 0.7rem;
			}
		}
	} // :global
} // .tagsWrapper

.previewMarkdown {
	* {
		font-size: 1.2rem;
		font-weight: 300;
		line-height: 1.9rem;
		letter-spacing: 0.0125em;
		color: #fff;
	}

	h1 {
		font-size: 1.5rem;
		font-weight: 400;
		line-height: 2rem;
		letter-spacing: normal;
		padding-bottom: 16px;
	}

	h2,
	h3,
	h4,
	h5,
	h6 {
		font-size: 1.25rem;
		font-weight: 400;
		line-height: 2rem;
		letter-spacing: normal;
		padding-bottom: 16px;
	}

	blockquote {
		margin: 1em 0;
		border-left: 5px solid #ffffffcf;
		padding: 0 40px;
	}

	a {
		color: #ffffffcf;
		text-decoration: underline;
		text-underline-position: under;

		&:hover,
		&:focus {
			color: #fff;
		}
	}

	ul {
		padding-left: 20px;
	}
} // .previewMarkdown
</style>