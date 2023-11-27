<script lang='ts'>
	import { onMount, tick } from "svelte";
	import { base_url } from "$lib/dbfuncs";
	import type { event } from "$lib/utiltypes";

	let ticketSeats: String[] = [];
	let ticketInfo: event | null = null;
	onMount(() => {
		ticketSeats = [
			"A52",
			"B23",
			"C56",
			"C57",
			"D12"
		]

		// get the id from the url
		const urlParams = new URLSearchParams(window.location.search);
		const ticketId = urlParams.get('id');
		console.log(ticketId);
		
		// get the ticket info from the server
		ticketInfo = {
			id: 1,
			name: "Jackpot Juicer",
			date: new Date(),
			venue: "The Factory",
			artist: "Alec Benjamin",
			genre: "Rock"
		}	
	});
</script>

<main>
	{#if ticketInfo == null}
		<h1>Loading...</h1>
	{:else}
		<div id="form">
			<h2>Buy Tickets to: {ticketInfo.name}</h2>
			<img src="https://cdn.shopify.com/s/files/1/0651/9639/2689/files/DGD-DESKTOP-HERO_1000x1000.jpg?v=1656558793">

			<h3>Venue: {ticketInfo.venue}</h3>

			<h3>Date: {ticketInfo.date.toLocaleString()}</h3>

			<h3>Seat Number:</h3>
			<select>
				<option selected disabled value="">
					Select 
				</option>
				{#each ticketSeats as seat}
					<option>
						{seat}
					</option>
				{/each}
			</select>

			<h3>Price:</h3>
			<h3>$100</h3>
			
			<div id="button-centerer">
				<button>Buy Now!</button>
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