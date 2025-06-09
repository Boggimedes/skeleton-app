import { type UseFetchOptions } from '#app';
import { createError, useFetch, useRuntimeConfig } from '#imports';
import { defu } from 'defu';
import type { NitroFetchOptions } from 'nitropack';

interface ErrorType {
	errors: Record<string, string[]>;
}

type FetchOptions<T> = UseFetchOptions<T> & { timeout?: number };

export function useAPI<T = unknown>(url: string | (() => string), userOptions: FetchOptions<T> = {}) {

	/**
	 * Aborting a fetch with timeout
	 */
	const controller = new AbortController();
	const timeoutId = setTimeout(() => {
		controller.abort(createError({ statusCode: 408, statusMessage: 'aborted', message: 'This request has been automatically aborted.' }));
	}, userOptions.timeout);

	const defaultOptions: FetchOptions<T> = {
		baseURL: `http://localhost:8888/api`, // Replace with your actual API base URL
		method: 'GET',
		retry: 3,
		signal: userOptions.timeout ? controller.signal : undefined,

		// cache request
		key: typeof url === 'string' ? url : url(),

		onRequest({ options }) {
				options.headers = new Headers({
					...(options.headers instanceof Headers ? Object.fromEntries(options.headers.entries()) : options.headers),
					Accept: 'application/json',
					'Content-type': 'application/json',
				});
		},

		onResponse({ response }) {
			const hasError = !response.status.toString().startsWith('2') || response._data.status === 'error';

			if (hasError) {
				throw createError({
					statusCode: response.status,
					statusMessage: response._data.status,
					message: response._data?.message || JSON.stringify(response._data?.errors),
				});
			}
		},

		onResponseError({ response }) {
			const statusCode = response.status || 500;
			const statusMessage = response.statusText || '';
			const errorsMsg = (response._data || {}) as ErrorType;

			const errorEntries = Object.entries(errorsMsg.errors);

			const message = errorEntries.reduce((acc: string[], [key, value]) => {
				return [...acc, ...value.map((item) => `${key} ${item}`)];
			}, []);

			throw createError({ statusCode, statusMessage, message: message.join("::|::") });
		},
	};

	const options = defu(userOptions, defaultOptions) as UseFetchOptions<T>;

	return $fetch(typeof url === 'function' ? url() : url, options as NitroFetchOptions<string>).finally(() => {
		if (userOptions.timeout && timeoutId) {
			clearTimeout(timeoutId);
		}
	});
}