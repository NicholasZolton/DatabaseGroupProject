<script lang='ts'>
	import { onMount } from "svelte";
	import { current_user } from "$lib/user";
	import { goto } from "$app/navigation";

	let ticketId: any = 0;
	let ticketInfo: any = null;
	let ticketPrice: number = 0;
	onMount(async () => {

		// get the id from the url
		const urlParams = new URLSearchParams(window.location.search);
		ticketId = urlParams.get('id');
		console.log(ticketId);
		
		// get the ticket info from the server
		const options = {method: 'GET', headers: {'User-Agent': 'insomnia/2023.5.8', 'ngrok-skip-browser-warning': 'true', 'no-cors': 'true'}};
		let response: any = await fetch('https://chow-coherent-actually.ngrok-free.app/DBProjectTest/get_ticket_info.php?TicketID=' + ticketId, options)
		response = await response.json();	
		
		// get the ticket info from the server
		ticketInfo = response;	
	});
	
	async function sellCurrentTicket(event: any) {
		event.preventDefault();

		// make call to actually sell ticket
		const form = new FormData();
		form.append("SellerID", $current_user);
		form.append("TicketID", ticketId);
		form.append("Price", ticketPrice.toString());

		let options: any = {
		method: 'POST',
		headers: {
			'User-Agent': 'insomnia/2023.5.8',
			 'ngrok-skip-browser-warning': 'true',
			}
		};

		options.body = form;

		let response: any = await fetch('https://chow-coherent-actually.ngrok-free.app/DBProjectTest/sell_ticket.php', options)
		response = await response.json();
		
		goto("/dashboard");
	}
</script>

<main>
	{#if ticketInfo !== null}
		<div id="form">
			<h3>Sell Ticket:</h3>
			<h4>{ticketInfo.EventName}</h4>
			<img src="https://cdn.shopify.com/s/files/1/0651/9639/2689/files/DGD-DESKTOP-HERO_1000x1000.jpg?v=1656558793">

			<h3>Venue:</h3>
			<h4>{ticketInfo.VenueName}</h4>

			<h3>Date:</h3>
			<h4>{ticketInfo.EventTime} pm - {ticketInfo.EventDate}</h4>

			<h3>Venue Address:</h3>
			<h4>{ticketInfo.StreetAddress}</h4>

			<h3>Price:</h3>
			<input type="text" placeholder="100" bind:value={ticketPrice}>
			
			<div id="button-centerer">
				<button on:click={sellCurrentTicket}>Sell Ticket</button>
			</div>
		</div>
	{:else}
		<h1>Loading...</h1>
	{/if}
</main>

<style>

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
	
	#form input {
		width: 60%;
	}
	
	select {
		width: 60%;
	
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