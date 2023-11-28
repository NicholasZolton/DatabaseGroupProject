<script lang='ts'>
	import { onMount, tick } from "svelte";
	import { base_url } from "$lib/dbfuncs";
	import type { event } from "$lib/utiltypes";
	import { current_user } from "$lib/user";
	import { get } from "svelte/store";
	
	let ticketInfo: any = null;
	let ticketId: any = null;
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
		
		console.log(get(current_user));
	});

	async function buyTicket(event: any) {
		event.preventDefault();
		
		// send the username and password to the server and check if they are correct
		const form = new FormData();
		form.append("TicketID", ticketId);
		form.append("BuyerID", get(current_user));
		console.log(form);
		

		let options: any = {
		method: 'POST',
		headers: {
			'User-Agent': 'insomnia/2023.5.8',
			 'ngrok-skip-browser-warning': 'true',
			}
		};

		options.body = form;

		let response: any = await fetch('https://chow-coherent-actually.ngrok-free.app/DBProjectTest/buy_ticket.php', options)
		response = await response.json();
		console.log(response);
		
	}

</script>

<main>
	{#if ticketInfo == null}
		<h1>Loading...</h1>
	{:else if $current_user == -1}
		<h1>You must be logged in to buy tickets</h1>
	{:else}
		<div id="form">
			<h2>Buy Tickets to: {ticketInfo.EventName}</h2>
			<img src="https://cdn.shopify.com/s/files/1/0651/9639/2689/files/DGD-DESKTOP-HERO_1000x1000.jpg?v=1656558793">

			<h3>Venue: {ticketInfo.VenueName}</h3>

			<h3>Date: {ticketInfo.EventTime} pm - {ticketInfo.EventDate}</h3>

			<h3>Venue Address:</h3>
			<h3>{ticketInfo.StreetAddress}</h3>

			<h3>Price:</h3>
			<h3>${(Math.round(ticketInfo.Price * 100) / 100).toFixed(2)}</h3>
			
			<div id="button-centerer">
				<button on:click={buyTicket}>Buy Now!</button>
			</div>
		</div>
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
		text-align: center;
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