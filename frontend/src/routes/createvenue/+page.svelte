<script lang='ts'>
	import { onMount } from "svelte";

	let ticketSeats: String[] = [];
	let venues: String[] = [];
	onMount(() => {
		ticketSeats = [
			"A52",
			"B23",
			"C56",
			"C57",
			"D12"
		]
		
		venues = [
			"The Factory",
			"The Stadium",
			"The Arena",	
		]
	});
</script>

<main>
	<div id="form">
		<h2>Create a new Venue</h2>

		<h3>Name:</h3>
		<input type="text" aria-label="Name" />

		<h3>Capacity:</h3>
		<input type="number" aria-label="Capacity" />

		<h3>Thumbnail:</h3>
		<input type="file" aria-label="Thumbnail" />
		
		<div id="button-centerer">
			<button>Sell Tickets!</button>
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