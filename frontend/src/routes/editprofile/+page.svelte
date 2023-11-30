<script lang='ts'>
	import { onMount } from "svelte";
	import { current_user } from "$lib/user";
	import { goto } from "$app/navigation";

	let originalUsername = "User";
	let username = "User";
	let originalDOB = "2003-01-01";
	let date_of_birth = "2003-01-01";	
	let originalEmail = "test@example.com";
	let email = "test@example.com";
	let originalCardNumber = "1234567890123456";
	let cardNumber = "1234567890123456";
	let originalCardExpiration = "03/25";
	let cardExpiration = "03/25";
	let originalSecurityCode = "123";
	let securityCode = "123";
	
	let firstUsername = "User";
	let firstDOB = "2003-01-01";
	let firstEmail = "test@example.com";
	let firstCardNumber = "1234567890123456";
	let firstCardExpiration = "03/25";
	let firstSecurityCode = "123";
	
	let finishedLoading = false;

	onMount(async () => {
		let options: any = {method: 'GET', headers: {'User-Agent': 'insomnia/2023.5.8', 'ngrok-skip-browser-warning': 'true', 'no-cors': 'true'}};
		let response: any = await fetch('https://chow-coherent-actually.ngrok-free.app/DBProjectTest/get_user_info.php?userID=' + $current_user, options)
		// console.log(await response.text());
		response = await response.json();	
		originalUsername = response.UserName;
		originalDOB = response.DOB;
		originalEmail = response.Email;
		originalCardNumber = response.CardN;
		originalCardExpiration = response.CardExpr;
		originalSecurityCode = response.SecCode;
		
		
		username = originalUsername;
		date_of_birth = originalDOB;
		email = originalEmail;
		cardNumber = originalCardNumber;
		cardExpiration = originalCardExpiration;
		securityCode = originalSecurityCode;
		
		firstUsername = originalUsername;
		firstDOB = originalDOB;
		firstEmail = originalEmail;
		firstCardNumber = originalCardNumber;
		firstCardExpiration = originalCardExpiration;
		firstSecurityCode = originalSecurityCode;
		finishedLoading = true;
	});
	
	async function deleteUser() {
		// delete the current user using the api and then goto the /	
		
		goto("/");
	}
	
	async function checkDOB() {
		// check if date_of_birth is in the form yyyy-mm-dd
		// and also make sure that the date is valid
		
		let date_of_birth_split = date_of_birth.split("-");
		
		if (date_of_birth_split.length != 3) {
			return false;
		}
		
		if (date_of_birth_split[0].length != 4 || date_of_birth_split[1].length != 2 || date_of_birth_split[2].length != 2) {
			return false;
		}

		let month = parseInt(date_of_birth_split[1]);

		if (month < 1 || month > 12) {
			return false;
		}

		let day = parseInt(date_of_birth_split[2]);

		if (day < 1 || day > 31) {
			return false;
		}

		let year = parseInt(date_of_birth_split[0]);

		if (year < 1900 || year > 2022) {
			return false;
		}

		return true;
	}
	
	async function checkEmail() {
		// check that the email is in a valid form

		let email_split = email.split("@");

		if (email_split.length != 2) {
			return false;
		}
		
		if (email_split[0].length < 1) {
			return false;
		}
		
		if (email_split[1].length < 1) {
			return false;
		}

		let domain_split = email_split[1].split(".");

		if (domain_split.length < 2) {
			return false;
		}
		
		if (domain_split[0].length < 1) {
			return false;
		}

		if (domain_split[1].length < 1) {
			return false;
		}

		return true;
	}
	
	async function emailEdited(event: any) {
		if (!await checkEmail()) {
			email = originalEmail;
		} else {
			originalEmail = email;
		}
	}
	
	async function DOBEdited(event: any) {
		if (!await checkDOB()) {
			date_of_birth = originalDOB;
		} else {
			originalDOB = date_of_birth;
		}
	}
	
	async function cardNumberEdited(event: any) {
		// check that the card number is in a valid form
		// and also make sure that the card number is valid
		// check the length of the card number and make sure it is 16 digits
		// check that the card number is all digits
		
		if (cardNumber.length != 16) {
			cardNumber = originalCardNumber;
			return;
		}

		for (let i = 0; i < cardNumber.length; i++) {
			if (cardNumber[i] < '0' || cardNumber[i] > '9') {
				cardNumber = originalCardNumber;
				return;
			}
		}
		
		originalCardNumber = cardNumber;
	}
	
	async function cardExpirationEdited(event: any) {
		// check that the card expiration is in a valid form
		// and also make sure that the card expiration is valid
		// check the length of the card expiration and make sure it is 5 characters
		// check that the card expiration is in the form mm/yy
		
		if (cardExpiration.length != 5) {
			cardExpiration = originalCardExpiration;
			return;
		}

		if (cardExpiration[2] != '/') {
			cardExpiration = originalCardExpiration;
			return;
		}

		let month = parseInt(cardExpiration[0] + cardExpiration[1]);

		if (month < 1 || month > 12) {
			cardExpiration = originalCardExpiration;
			return;
		}

		let year = parseInt(cardExpiration[3] + cardExpiration[4]);

		if (year < 23 || year > 99) {
			cardExpiration = originalCardExpiration;
			return;
		}
		
		originalCardExpiration = cardExpiration;
	}
	
	async function securityCodeEdited(event: any) {
		// check that the security code is in a valid form
		// and also make sure that the security code is valid
		// check the length of the security code and make sure it is 3 digits
		// check that the security code is all digits
		
		if (securityCode.length != 3) {
			securityCode = originalSecurityCode;
			return;
		}

		for (let i = 0; i < securityCode.length; i++) {
			if (securityCode[i] < '0' || securityCode[i] > '9') {
				securityCode = originalSecurityCode;
				return;
			}
		}
		
		originalSecurityCode = securityCode;
	}
	
	async function saveChanges() {
		// use the API to save the new changes	

		const form = new FormData();
		form.append("UserID", $current_user);
		if (username != originalUsername) {
			form.append("UserN", username);
		} else {
			form.append("UserN", "");
		}
		form.append("PassW", "");
		if (originalEmail != firstEmail) {
			form.append("Email", originalEmail);
		} else {
			form.append("Email", "");
		}
		if (date_of_birth != originalDOB) {
			form.append("DOB", date_of_birth);
		} else {
			form.append("DOB", "");
		}
		if (firstCardNumber != originalCardNumber) {
			form.append("CardN", originalCardNumber);
		} else {
			form.append("CardN", "");
		}
		if (originalCardExpiration != firstCardExpiration) {
			form.append("CardExpr", originalCardExpiration);
		} else {
			form.append("CardExpr", "");
		}
		if (originalSecurityCode != firstSecurityCode) {
			form.append("SecCode", originalSecurityCode);
		} else {
			form.append("SecCode", "");
		}
		console.log(form);
		

		let options: any = {
		method: 'POST',
		headers: {
			'User-Agent': 'insomnia/2023.5.8',
			 'ngrok-skip-browser-warning': 'true',
			}
		};

		options.body = form;

		let response: any = await fetch('https://chow-coherent-actually.ngrok-free.app/DBProjectTest/edit_user_info.php', options)
		response = await response.json();
		console.log(response);
		
		goto("/userprofile");
	}

</script>

<main>
	<div id="form">
		{#if !finishedLoading}
			<h1>Loading...</h1>
		{:else}
			<div id="inputs">
				<h2 id="username-header">Welcome, {username}!</h2>
				<br/>
				<h3>Date of Birth:</h3>
				<input on:blur={DOBEdited} type="text" bind:value={date_of_birth}/>
				
				<h3>Email:</h3>
				<input on:blur={emailEdited} type="text" bind:value={email}/>

				<h3>Card Number:</h3>
				<input on:blur={cardNumberEdited} type="text" bind:value={cardNumber}/>

				<h3>Card Expiration:</h3>
				<input on:blur={cardExpirationEdited} type="text" bind:value={cardExpiration}/>

				<h3>Security Code:</h3>
				<input on:blur={securityCodeEdited} type="text" bind:value={securityCode}/>

			</div>
			
			<div id="button-centerer">
				<button id="edit-button" on:click={saveChanges}>Save Changes</button>
			</div>
		{/if}
	</div>
</main>

<style>

	#inputs {
		width: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
		flex-direction: column;
	}

	#delete-button {
		background-color: #ff3d3d;
	}

	#delete-button:hover {
		background-color: #e33434;
	}

	h3 {
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
		justify-content: center;
		align-items: center;
		height: 100%;
	}

	#form img {
		width: 50%;
		object-fit: cover;
	}
	
	input {
		width: 60%;
	}
	
	select {
		width: 60%;
	
	}

	#form {
		/* height: 90%; */
		height: 120vh;
		width: 40%;
		display: flex;
		/* justify-content: flex-start; */
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