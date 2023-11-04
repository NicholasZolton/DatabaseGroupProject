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
		<h1>Sign Up</h1>
		<p>Username:</p>
		<input type="text" placeholder="adalovelace">
		<p>Password:</p>
		<input type="password" placeholder="password123">
		<div id="button-spacer"></div>
		<button>Sign Up</button>
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