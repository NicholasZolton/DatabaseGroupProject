<script lang='ts'>
	import { onMount } from "svelte";
	import { current_user } from "$lib/user";
	import { goto } from "$app/navigation";
	import Layout from "../+layout.svelte";

	let username = "User";
	let date_of_birth = "01/01/2000";	
	let email = "test@example.com";
	let cardNumber = "1234567890123456";
	let cardExpiration = "01/01/2022";
	let finishedLoading = false;

	onMount(async () => {
		let options: any = {method: 'GET', headers: {'User-Agent': 'insomnia/2023.5.8', 'ngrok-skip-browser-warning': 'true', 'no-cors': 'true'}};
		let response: any = await fetch('https://chow-coherent-actually.ngrok-free.app/DBProjectTest/get_user_info.php?userID=' + $current_user, options)
		response = await response.json();	
		username = response.UserName;
		date_of_birth = response.DOB;
		email = response.Email;
		cardNumber = response.CardN;
		cardExpiration = response.CardExpr;
		finishedLoading = true;
	});
	
	async function deleteUser() {
		// delete the current user using the api and then goto the /	
		const form = new FormData();
		form.append("userID", $current_user);
		console.log(form);
		

		let options: any = {
		method: 'POST',
		headers: {
			'User-Agent': 'insomnia/2023.5.8',
			 'ngrok-skip-browser-warning': 'true',
			}
		};

		options.body = form;

		let response: any = await fetch('https://chow-coherent-actually.ngrok-free.app/DBProjectTest/delete_user.php', options)
		response = await response.json();
		console.log(response);
		
		current_user.set(-1);
		
		goto("/");
	}

</script>

<main>
	<div id="form">
		{#if !finishedLoading}
			<h1>Loading...</h1>
		{:else}
			<h2 id="username-header">Welcome, {username}!</h2>
			<br/>
			<h3>Date of Birth:</h3>
			<h4>{date_of_birth}</h4>
			
			<h3>Email:</h3>
			<h4>{email}</h4>

			<h3>Card Number:</h3>
			<h4>{#if cardNumber}{cardNumber}{:else}Not Set.{/if}</h4>

			<h3>Card Expiration:</h3>
			<h4>{#if cardExpiration}{cardExpiration}{:else}Not Set.{/if}</h4>

			<h3>Security Code:</h3>
			<h4>{#if cardExpiration}***{:else}Not Set.{/if}</h4>
			
			<div id="button-centerer">
				<a href="/editprofile"><button id="edit-button">Edit Profile</button></a>
			</div>

			<div id="button-centerer">
				<button id="delete-button" on:click={deleteUser}>Delete My Account</button>
			</div>
		{/if}
	</div>
</main>

<style>

	#button-centerer a {
		width: 100%;
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	#delete-button {
		background-color: #ff3d3d;
	}

	#delete-button:hover {
		background-color: #e33434;
	}

	#form h3 {
		margin-bottom: 0;
	}

	#username-header {
		margin-bottom: 3vh;
	}

	#button-centerer button {
		width: 80%;
	}

	#button-centerer {
		display: flex;
		width: 100%;
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	#form img {
		width: 50%;
		object-fit: cover;
	}
	
	#form input {
		width: 60%;
	}
	
	select {
		width: 60%;
	
	}

	#form {
		height: 90%;
		min-height: 85vh;
		width: 40%;
		display: flex;
		justify-content: flex-start;
		align-items: center;
		flex-direction: column;
		box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
		border-radius: 10px;
		padding-top: 40px;
		margin-top: 3%;
		margin-bottom: 3%;
	}

	main {
		min-height: 95vh;
		width: 100vw;
		display: flex;
		justify-content: center;
		align-items: center;
	}
</style>