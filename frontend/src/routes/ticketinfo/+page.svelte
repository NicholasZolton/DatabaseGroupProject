<script lang='ts'>
	import { onMount } from "svelte";
	import type { event } from "$lib/utiltypes";

	let ticketId: String | null = "";
	let ticketInfo: event | null = null;
	onMount(() => {
		// get the ticket id from the url
		const urlParams = new URLSearchParams(window.location.search);
		ticketId = urlParams.get('id');
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

	<div id="event-picture">
		<div id="event-image">
		</div>
	</div>
	
	<div id="event-info">
		{#if ticketInfo == null}
			<h1>Loading...</h1>
		{:else}
			<h2>Event Name</h2>
			<h5>{ticketInfo.name}</h5>
			<h2>Event Date</h2>
			<h5>{ticketInfo.date.toLocaleString()}</h5>
			<h2>Event Venue</h2>
			<h5>{ticketInfo.venue}</h5>
			<h2>Event Artist</h2>
			<h5>{ticketInfo.artist}</h5>
			<h2>Event Genre</h2>	
			<h5>{ticketInfo.genre}</h5>
		{/if}
	</div>
	
	<div id="event-tickets">
		<a href="/buytickets?id={ticketId}"><button>Buy now!</button></a>
	</div>

</main>

<style>

	button a {
		text-decoration: none;
		color: white;
		width: 100%;
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	#event-info h2 {
		margin: 0;
	}

	#event-info {
		display: flex;
		flex-direction: column;
		justify-content: space-around;
		align-items: flex-start;
		height: 100%;
		width: 100%;
	}

	button {
		width: 100%;
		height: 100%;
		font-size: 1.8rem;
	}

	#event-tickets {
		display: flex;
		justify-content: center;
		align-items: center;
		height: 100%;
		width: 100%;
	}

	#event-picture {
		width: 100%;
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
	}
	
	#event-image {
		width: 80%;
		height: 80%;
		background-color: grey;
		background-image: url(https://cdn.shopify.com/s/files/1/0651/9639/2689/files/DGD-DESKTOP-HERO_1000x1000.jpg?v=1656558793);
		background-size: cover;
	}
	
	#event-picture img {
		width: 100%;
		height: 100%;
	}

	main {
		height: 90vh;
		/* make this a grid with 2 columns, but let the left one be twice the size of the right one */
		/* have two rows in the left column with the top one being twice the size of the bottom one */
		/* only have one row in the right column */
		display: grid;
		grid-template-columns: 2fr 1fr;
		grid-template-rows: 2fr 1fr;
	}
</style>