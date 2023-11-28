<script lang='ts'>
	import type { event } from '$lib/utiltypes';	
	import { base_url } from '$lib/dbfuncs';
	import { onMount } from 'svelte';
	import { current_user } from '$lib/user';
	import { get } from 'svelte/store';
	
	// ngrok-skip-browser-warning
	
	async function getEvents() {

		const options = {method: 'GET', headers: {'User-Agent': 'insomnia/2023.5.8', 'ngrok-skip-browser-warning': 'true', 'no-cors': 'true'}};
		let response: any = await fetch('https://chow-coherent-actually.ngrok-free.app/DBProjectTest/get_tickets.php', options)
		response = await response.json();	
		
		allEvents = []
		for (let i = 0; i < response.length; i++) {
			allEvents.push({
				id: response[i].Ticket_ID,
				name: response[i].EventName,
				date: new Date(response[i].EventDate),
				venue: response[i].VenueName,
				raw_data: response[i]
			})
		}

		events = allEvents;
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
			
			if (checkSubsequence(JSON.stringify(allEvents[i]).toLowerCase(), searchInput.toLowerCase())) {
				events.push(allEvents[i]);
			}

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
		console.log('current user is ' + $current_user);
	});
	
	let allEvents: any[] = [];
	let events: any[] = [];
	
</script>

<main data-theme="light">
	<div id="searchbar">
		<h1 id="main-title">Filter Events:</h1>
		<input bind:value={searchInput} on:input={searchEvents} id='searchinput' type="text" placeholder="Search by event name, artist, genre, or city">
	</div>
	<div id="events">
		{#each events as event}
			<div id="event-card">
				<hgroup>
					<h6>{event.name}</h6>
					<h6>{event.raw_data.VenueName}</h6>
				</hgroup>
				<img src="https://cdn.shopify.com/s/files/1/0651/9639/2689/files/DGD-DESKTOP-HERO_1000x1000.jpg?v=1656558793">
				<a href="/ticketinfo?id={event.id}"><button>Reserve Tickets</button></a>
			</div>
		{/each}
	</div>
</main>

<style>

	hgroup {
		display: flex;
		flex-direction: column;
		align-items: center;
		margin: 0;
	}

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