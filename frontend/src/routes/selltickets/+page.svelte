<script lang='ts'>
	import { onMount } from "svelte";

	let ticketSeats: String[] = [];
	onMount(() => {
		ticketSeats = [
			"A52",
			"B23",
			"C56",
			"C57",
			"D12"
		]
	});
</script>

<main>
	<div id="form">
		<h2>Buy Tickets to: Jackpot Juicer</h2>
		<img src="https://cdn.shopify.com/s/files/1/0651/9639/2689/files/DGD-DESKTOP-HERO_1000x1000.jpg?v=1656558793">

		<h3>Venue: The Factory</h3>

		<h3>Date: 11/28/2023</h3>

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