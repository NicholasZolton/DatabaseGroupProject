import { writable } from "svelte/store";
import type { Writable } from "svelte/store";

export const current_user: Writable<any> = writable(-1);
// export const current_user: Writable<any> = writable(1357);