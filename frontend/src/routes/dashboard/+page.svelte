<script lang='ts'>
	import { goto } from "$app/navigation";
	import { onMount } from "svelte";
	import { current_user } from "$lib/user";
	import { get } from "svelte/store";

	// let tickets = [
	// 	{
	// 		id: 1,
	// 		name: "ticket1"
	// 	},
	// 	{
	// 		id: 2,
	// 		name: "ticket2"
	// 	},
	// 	{
	// 		id: 3,
	// 		name: "ticket4"
	// 	},
	// 	{
	// 		id: 4,
	// 		name: "ticket4"
	// 	},
	// 	{
	// 		id: 5,
	// 		name: "ticket5"
	// 	},
	// 	{
	// 		id: 6,
	// 		name: "ticket6"
	// 	},
	// 	{
	// 		id: 7,
	// 		name: "ticket7"
	// 	},
	// 	{
	// 		id: 8,
	// 		name: "ticket8"
	// 	},
	// ]
	
	let tickets: any[] | null = null;
	onMount(async () => {
		const form = new FormData();
		form.append("UserID", $current_user);

		let options: any = {
		method: 'POST',
		headers: {
			'User-Agent': 'insomnia/2023.5.8',
			 'ngrok-skip-browser-warning': 'true',
			}
		};

		options.body = form;

		let response: any = await fetch('https://chow-coherent-actually.ngrok-free.app/DBProjectTest/get_user_tickets.php', options)
		response = await response.json();
		tickets = response;
		console.log(tickets);
	});
	
</script>

<main>
	<div id="form">
		<h2>Welcome, User!</h2>
		<div id="left-aligned">
			<h3>My Tickets:</h3>
		</div>
		<div id="main-section">
			{#if tickets !== null && tickets.length > 0}
				<div id="spacer" style="height: 20px; width: 10px;"/>
				{#each tickets as ticket}
					<div id="ticket">
						<div id="ticket-information">
							<h5>{ticket.EventName}</h5>
							<h5>{ticket.EventType}</h5>
							<h5>{ticket.VenueName}</h5>
							<h5>{ticket.EventTime} pm, {ticket.EventDate}</h5>
						</div>
						<a href="/selltickets?id={ticket.TicketID}"><button>Sell Ticket</button></a>
					</div>
				{/each}
			{:else if tickets !== null && tickets.length <= 0}
				<div id="loading-box">
					<h4>You have no tickets!</h4>
					<a href="/"><h4>Purchase some now!</h4></a>
				</div>
			{:else}
				<div id="loading-box">
					<h4>Loading tickets...</h4>
				</div>
			{/if}
		</div>
	</div>
</main>

<style>

	#ticket-information {
		width: 100%;
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-right: 20px;
	}

	#loading-box {
		width: 100%;
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	#ticket button {
		width: 100px;
		height: 50px;
		margin: 0;
		padding: 0;
		margin-right: 20px;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		text-align: center;
	}

	#left-aligned {
		width: 90%;
		display: flex;
		justify-content: flex-start;
		align-items: center;
	}
	
	#ticket h5 {
		margin: 0;
	}

	#ticket {
		width: 90%;
		margin: 10px;
		height: 60px;
		min-height: 60px;
		display: flex;
		flex-direction: row;
		box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
		border-radius: 10px;
		padding-left: 20px;
		align-items: center;
		/*space items as wide as possible  */
		justify-content: space-between;
	}

	#main-section {
		width: 100%;
		height: 60vh;
		display: flex;
		flex-direction: column;
		justify-content: start;
		align-items: center;
		overflow-y: scroll;
		/* padding: 20px; */
		
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
	
	#form input {
		width: 60%;
	}
	
	select {
		width: 60%;
	}

	#form {
		height: 90%;
		min-height: 85vh;
		width: 80%;
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