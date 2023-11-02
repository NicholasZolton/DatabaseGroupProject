<script>
	import { onMount } from "svelte";

	onMount(() => {

	});
</script>

<main>

	<div id="event-picture">
		<div id="event-image">
		</div>
	</div>
	
	<div id="event-info">
		<h2>Event Name</h2>
		<h5>Jackpot Juicer</h5>
		<h2>Event Date</h2>
		<h5>Alec Benjamin</h5>
		<h2>Event Venue</h2>
		<h5>The Factory</h5>
		<h2>Event Artist</h2>
		<h5>Alec Benjamin</h5>
		<h2>Event Genre</h2>	
		<h5>Rock</h5>
	</div>
	
	<div id="event-tickets">
		<a href="/buytickets"><button>Buy now!</button></a>
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