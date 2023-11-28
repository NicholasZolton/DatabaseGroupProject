<script lang='ts'>
	import { current_user } from "$lib/user";	
	import { get } from "svelte/store";
	import { goto } from "$app/navigation";
	import { invalidateAll } from "$app/navigation";
	
	function logoutUser() {
		current_user.set(-1);
		invalidateAll();
	}
</script>

<svelte:head>
	<title>SeatSeeker</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
</svelte:head>

<div id="navbar" data-theme="light">
	<grad-text><a href="/">SeatSeeker</a></grad-text>	
	<a href="/">Events</a>
	{#if $current_user != -1}
	<a href="/dashboard">Personal Dashboard</a>
	<a on:click={logoutUser}>Log Out</a>
	{:else}
	<a href="/login">Login</a>
	<a href="/signup">Sign Up</a>
	{/if}
</div>

<html data-theme='light' lang='en'>
	<slot />
</html>

<style>

	grad-text a {
		text-decoration: none;
	}

	navbar a {
		text-decoration: none;
		color: #5fa8d3;
	}

	#navbar {
		height: 10vh;
		width: 100%;
		display: flex;
		justify-content: space-around;
		align-items: center;
		box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
		font-size: 1.5rem;
	}

	grad-text {
		color: #5fa8d3;
		background-image: -webkit-linear-gradient(135deg, #5fa8d3 0%, #1b4965 100%);
		background-clip: text;
		-webkit-background-clip: text;
		text-fill-color: transparent;
		-webkit-text-fill-color: transparent;
		font-weight: 800;
		font-size: 2rem;
	}
</style>