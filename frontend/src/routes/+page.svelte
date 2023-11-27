<script lang='ts'>
	import type { event } from '$lib/utiltypes';	
	import { base_url } from '$lib/dbfuncs';
	import { onMount } from 'svelte';
	
	async function getEvents() {
		allEvents = [
			{
				id: 1,
				name: 'Event 1',
				date: new Date(),
				venue: 'Venue 1',
				artist: 'Artist 1',
				genre: 'Genre 1'
			},
			{
				id: 2,
				name: 'Event 2',
				date: new Date(),
				venue: 'Venue 2',
				artist: 'Artist 2',
				genre: 'Genre 2'
			},
			{
				id: 3,
				name: 'Event 3',
				date: new Date(),
				venue: 'Venue 1',
				artist: 'Artist 1',
				genre: 'Genre 1'
			},
			{
				id: 4,
				name: 'Event 4',
				date: new Date(),
				venue: 'Venue 2',
				artist: 'Artist 2',
				genre: 'Genre 2'
			},
			{
				id: 5,
				name: 'Event 5',
				date: new Date(),
				venue: 'Venue 1',
				artist: 'Artist 1',
				genre: 'Genre 1'
			},
		];
		
		events = allEvents;

		// const response = await fetch(`${base_url}/events`);
		// const data = await response.json();
		// return data;
	}
	
	let searchInput: String = "";
	let debounce: any = false;
	async function searchEvents() {
		if (debounce != false) {
			// set it to rerun in 200ms
			clearTimeout(debounce);
			debounce = setTimeout(() => {
				debounce = false;
				searchEvents();
			}, 200);	
			return;
		}

		events = [];
		
		// // split the search input into words by spaces
		// let searchInputWords = searchInput.split(" ");

		for (let i = 0; i < allEvents.length; i++) {
			
			// for every word in the search input, check if it is a subsequence of any of the event properties
			// for (let j = 0; j < searchInputWords.length; j++) {
				if (checkSubsequence(allEvents[i].name.toLowerCase(), searchInput.toLowerCase()) ||
				 checkSubsequence(allEvents[i].artist.toLowerCase(), searchInput.toLowerCase()) ||
				  checkSubsequence(allEvents[i].genre.toLowerCase(), searchInput.toLowerCase()) ||
				   checkSubsequence(allEvents[i].venue.toLowerCase(), searchInput.toLowerCase())) {
					events.push(allEvents[i]);
				}
			// }	
		}
		
		debounce = setTimeout(() => {
			debounce = false;
		}, 200);
	}
	
	function checkSubsequence(primaryString: String, subsequence: String): Boolean {
		let stack = [];
		
		for (let i = 0; i < subsequence.length; i++) {
			if (subsequence[i] == " ") {
				continue;
			}
			stack.push(subsequence[i]);
		}
		
		// traverse str1 in reverse order
		for (let i = primaryString.length - 1; i >= 0; i--) {
			// if the top of the stack is the same as the current character in str2, pop it off
			if (stack[stack.length - 1] == primaryString[i]) {
				stack.pop();
			}
		}

		return stack.length == 0;
	}
	
	onMount(async () => {
		const events = await getEvents();
		console.log(events);
	});
	
	let allEvents: event[] = [];
	let events: event[] = [];
	
</script>

<main data-theme="light">
	<div id="searchbar">
		<h1 id="main-title">Filter Events:</h1>
		<input bind:value={searchInput} on:input={searchEvents} id='searchinput' type="text" placeholder="Search by event name, artist, genre, or city">
	</div>
	<div id="events">
		{#each events as event}
			<div id="event-card">
				<h1>{event.name}</h1>
				<img src="https://cdn.shopify.com/s/files/1/0651/9639/2689/files/DGD-DESKTOP-HERO_1000x1000.jpg?v=1656558793">
				<a href="/ticketinfo?id={event.id}"><button>Reserve Tickets</button></a>
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
		align-items: center;
		justify-content: center;
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
		width: 20%;
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