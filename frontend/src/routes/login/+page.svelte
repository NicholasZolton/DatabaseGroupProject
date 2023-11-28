<script lang='ts'>
	import { onMount } from "svelte";
	import { base_url } from "$lib/dbfuncs";
	import { current_user } from "$lib/user";
	import { get } from "svelte/store";
	import { goto } from '$app/navigation';
	
	onMount(async () => {
		
	});
	
	let username: any = "Keshav1";
	let password: any = "Keshavpass";
	
	async function sendLogin(event: any) {
		event.preventDefault();
		
		// send the username and password to the server and check if they are correct
		const form = new FormData();
		form.append("Username", username);
		form.append("Password", password);

		let options: any = {
		method: 'POST',
		headers: {
			'User-Agent': 'insomnia/2023.5.8',
			 'ngrok-skip-browser-warning': 'true',
			}
		};

		options.body = form;

		let response: any = await fetch('https://chow-coherent-actually.ngrok-free.app/DBProjectTest/login.php', options)
		response = await response.json();
		
		console.log(response);
		
		if (response.UserID == null) {
			alert("Incorrect username or password");
			return;
		}
		current_user.set(parseInt(response.UserID));
		console.log(get(current_user).toString() + " logged in!");
		
		// redirect to "/"
		goto("/");
	}
</script>

<main>
	<div id="form">
		<h1>Login</h1>
		<p>Username:</p>
		<input type="text" placeholder="adalovelace" bind:value={username}>
		<p>Password:</p>
		<input type="password" placeholder="password123" bind:value={password}>
		<div id="button-spacer"></div>
		<button on:click={sendLogin}>Login</button>
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