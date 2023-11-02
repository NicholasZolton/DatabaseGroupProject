<script lang='ts'>
	import type { event } from '$lib/utiltypes';	
	
	let events: event[] = [
			{
				name: 'Event 1',
				date: new Date(),
				venue: 'Venue 1',
				artist: 'Artist 1',
				genre: 'Genre 1'
			},
			{
				name: 'Event 2',
				date: new Date(),
				venue: 'Venue 2',
				artist: 'Artist 2',
				genre: 'Genre 2'
			},
			{
				name: 'Event 3',
				date: new Date(),
				venue: 'Venue 1',
				artist: 'Artist 1',
				genre: 'Genre 1'
			},
			{
				name: 'Event 4',
				date: new Date(),
				venue: 'Venue 2',
				artist: 'Artist 2',
				genre: 'Genre 2'
			},
			{
				name: 'Event 5',
				date: new Date(),
				venue: 'Venue 1',
				artist: 'Artist 1',
				genre: 'Genre 1'
			},
			{
				name: 'Event 6',
				date: new Date(),
				venue: 'Venue 2',
				artist: 'Artist 2',
				genre: 'Genre 2'
			},
			{
				name: 'Event 7',
				date: new Date(),
				venue: 'Venue 1',
				artist: 'Artist 1',
				genre: 'Genre 1'
			},
			{
				name: 'Event 8',
				date: new Date(),
				venue: 'Venue 2',
				artist: 'Artist 2',
				genre: 'Genre 2'
			},
			{
				name: 'Event 9',
				date: new Date(),
				venue: 'Venue 1',
				artist: 'Artist 1',
				genre: 'Genre 1'
			},
			{
				name: 'Event 10',
				date: new Date(),
				venue: 'Venue 2',
				artist: 'Artist 2',
				genre: 'Genre 2'
			},
			{
				name: 'Event 11',
				date: new Date(),
				venue: 'Venue 1',
				artist: 'Artist 1',
				genre: 'Genre 1'
			},
			{
				name: 'Event 12',
				date: new Date(),
				venue: 'Venue 2',
				artist: 'Artist 2',
				genre: 'Genre 2'
			},
			{
				name: 'Event 13',
				date: new Date(),
				venue: 'Venue 1',
				artist: 'Artist 1',
				genre: 'Genre 1'
			},
			{
				name: 'Event 14',
				date: new Date(),
				venue: 'Venue 2',
				artist: 'Artist 2',
				genre: 'Genre 2'
			},
		];
	
</script>

<main data-theme="light">
	<div id="searchbar">
		<h1 id="main-title">Filter Events:</h1>
		<input id='searchinput' type="text" placeholder="Search by event name, artist, genre, or city">
	</div>
	<div id="events">
		{#each events as event}
			<div id="event-card">
				<h1>{event.name}</h1>
				<img src="https://cdn.shopify.com/s/files/1/0651/9639/2689/files/DGD-DESKTOP-HERO_1000x1000.jpg?v=1656558793">
				<a href="/ticketinfo"><button>Reserve Tickets</button></a>
			</div>
		{/each}
	</div>
</main>

<style>

	#event-card img {
		width: 80%;
	}

	a {
		text-decoration: none;
		color: white;
		width: 100%;
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	#searchinput {
		width: 60%;
		margin: 0;
	}

	#searchbar {
		width: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
		flex-direction: row;
	}

	#main-title {
		margin: 20px;
	}

	main {
		width: 100%;
		display: flex;
		justify-content: center;
		flex-direction: column;
		min-height: 90vh;
	}	
	
	#events {
		width: 100%;
		min-height: 80vh;
		height: 100%;
		display: flex;
		flex-wrap: wrap;
		justify-content: left;
		/* background-color: #f5f5f5; */
	}
	
	grad-text {
		color: #5fa8d3;
		background-image: -webkit-linear-gradient(180deg, #5fa8d3 0%, #62b6cb 100%);
		background-clip: text;
		-webkit-background-clip: text;
		text-fill-color: transparent;
		-webkit-text-fill-color: transparent;
	}
	
	#event-card {
		width: 300px;
		height: 300px;
		background-color: #fff;
		border-radius: 10px;
		box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
		margin: 10px;
		display: flex;
		justify-content: center;
		align-items: center;
		flex-direction: column;
	}	
	
	#event-card button {
		width: 80%;
	}
	
	#event-card h1 {
		margin: 0;
		margin-bottom: 20px;
	}
</style>