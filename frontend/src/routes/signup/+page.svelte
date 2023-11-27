<script lang='ts'>
	import { onMount } from "svelte";
	import { base_url } from "$lib/dbfuncs";

	onMount(() => {
	});
	
	async function sendSignup(event: any) {
		event.preventDefault();

		// send the username and password to the server and check if they are correct
		const response = await fetch(`${base_url}/signup`, {
			method: "POST",
			headers: {
				"Content-Type": "application/json"
			},
			body: JSON.stringify({
				username: username,
				password: password
			})
		});
		
		const data = await response.json();
		
		if (data.success) {
			// redirect to the home page
			window.location.href = "/";
		} else {
			// display an error message
			alert("Incorrect username or password");
		}
	}
	
	let username: String = "";
	let password: String = "";
</script>

<main>
	<div id="form">
		<h1>Sign Up</h1>
		<p>Username:</p>
		<input type="text" placeholder="adalovelace" bind:value={username}>
		<p>Password:</p>
		<input type="password" placeholder="password123" bind:value={password}>
		<div id="button-spacer"></div>
		<button on:click={sendSignup}>Sign Up</button>
	</div>
</main>

<style>

	#button-spacer {
		height: 10px;
	}

	#form button {
		width: 80%;
		margin-bottom: 30px;
	}

	#form p {
		margin: 0;
	}

	#form input {
		width: 60%;
	}

	#form h1 {
		margin-bottom: 1rem;
	}

	#button-centerer button {
		width: 80%;
	}

	#button-centerer {
		width: 100%;
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	#form h2 {
		margin: 0;
	}

	#form h3 {
		margin: 0;
	}

	#form img {
		width: 50%;
		object-fit: cover;
	}

	#form {
		height: 90%;
		width: 40%;
		display: flex;
		justify-content: flex-start;
		align-items: center;
		flex-direction: column;
		box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
		border-radius: 10px;
		padding-top: 40px;
	}

	main {
		min-height: 95vh;
		width: 100vw;
		display: flex;
		justify-content: center;
		align-items: center;
	}
</style>