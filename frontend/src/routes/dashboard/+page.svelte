<script lang='ts'>
	import { goto } from "$app/navigation";
	import { onMount } from "svelte";
	import { current_user } from "$lib/user";
	import { get } from "svelte/store";
	import UserIcon from "$lib/icons/UserIcon.svelte";
	
	let tickets: any[] | null = null;
	let username = "User";
	onMount(async () => {
		// get the user's info
		let options: any = {method: 'GET', headers: {'User-Agent': 'insomnia/2023.5.8', 'ngrok-skip-browser-warning': 'true', 'no-cors': 'true'}};
		let response: any = await fetch('https://chow-coherent-actually.ngrok-free.app/DBProjectTest/get_user_info.php?userID=' + $current_user, options)
		// console.log(await response.text());
		response = await response.json();	
		username = response.UserName;

		// get the user's tickets
		const form = new FormData();
		form.append("UserID", $current_user);

		options = {
			method: 'POST',
			headers: {
				'User-Agent': 'insomnia/2023.5.8',
				'ngrok-skip-browser-warning': 'true',
				}
		};

		options.body = form;

		response = await fetch('https://chow-coherent-actually.ngrok-free.app/DBProjectTest/get_user_tickets.php', options)
		// console.log(await response.text());
		response = await response.json();
		tickets = response;
		console.log(tickets);

		
	});
	
</script>

<main>
	<div id="form">
		<a href='/userprofile'>
			<div id="user-info">
				<UserIcon css={"width: 30px; height: 30px;"}/>	
			</div>
		</a>
		<h2>Welcome, {username}!</h2>
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

	#user-info {
		position: absolute;
		top: 25px;
		right: 25px;
	}

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
		flex-direction: column;
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
		position: relative;
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