<html lang="en">
	<head>
		<meta charset="utf-8" />
		<link rel="icon" href="./favicon.png" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		
		<link href="./_app/immutable/assets/0.f5cba8e3.css" rel="stylesheet">
        <link href="./_app/immutable/assets/2.e3096441.css" rel="stylesheet">
		<link href="./_app/immutable/assets/5.0d42ccc9.css" rel="stylesheet">
		<link rel="modulepreload" href="./_app/immutable/entry/start.36e3d960.js">
		<link rel="modulepreload" href="./_app/immutable/chunks/scheduler.63274e7e.js">
		<link rel="modulepreload" href="./_app/immutable/chunks/singletons.d9622086.js">
		<link rel="modulepreload" href="./_app/immutable/entry/app.c7397ffc.js">
		<link rel="modulepreload" href="./_app/immutable/chunks/index.fbaf6ba9.js">
		<link rel="modulepreload" href="./_app/immutable/nodes/0.ed9b1b4f.js">
		<link rel="modulepreload" href="./_app/immutable/nodes/5.00614feb.js"><title>SeatSeeker</title><!-- HEAD_svelte-1is2hkg_START --><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css"><!-- HEAD_svelte-1is2hkg_END -->
	</head>
	<body data-sveltekit-preload-data="hover">
		<div style="display: contents">
               <div id="navbar" data-theme="light" class="svelte-dxtf3v" data-svelte-h="svelte-18ndwoz">
                    <grad-text class="svelte-dxtf3v">
                        <a href="/" class="svelte-dxtf3v">SeatSeeker</a>
                    </grad-text> 
                    <a href="/">Events</a>
                    <a href="/">Personal Dashboard</a>
                </div> 
                <html data-theme="light" lang="en">
                    <main data-theme="light" class="svelte-1vyamws">
                        <div id="events" class="svelte-1vyamws">
                            <?php
                                $eid = $_POST['User'];
                                $pwd = $_POST['Password'];
                                //echo "<p style = \"margin-bottom:0;\"> UserName: $eid </p><p> Password: $pwd </p>";
                                $conn = new mysqli("127.0.0.1","root","","forassignment8");
                                if ($conn->connect_error) { 
                                    die("Connection failed: " . $conn->connect_error); 
                                }
                                $sql = "SELECT  Event_ID, Venue_ID
                                        FROM    TICKET
                                        WHERE   Owner_ID = (SELECT User_ID
                                                            FROM USER
                                                            WHERE UserName = ' $eid' AND PassW = ' $pwd');";
                                $result = $conn->query($sql);
                                if($result->num_rows > 0){
                                    while($row = $result->fetch_assoc()){
                                        $eventInfo = $conn->query("SELECT EventName, EventType, EventTime, EventDate
                                                                   FROM   EVENT
                                                                   WHERE  Event_ID = $row[Event_ID];");
                                        $venueInfo = $conn->query("SELECT VenueName, StreetAddress, VenueCity, VenueState
                                                                   FROM   Venue
                                                                   WHERE  Venue_ID = $row[Venue_ID];");
                                        $eventRow = $eventInfo->fetch_assoc();
                                        $venueRow = $venueInfo->fetch_assoc();
                                        echo "<div id=\"event-card\" class=\"svelte-1vyamws\" style=\"height:auto;width:auto;\">
                                                <h1 class=\"svelte-1vyamws\" style =\"width:60%;font-size:auto;text-align:center;margin: 5px 5px 20px;\">",$eventRow["EventName"],"</h1>
                                                <p style = \"margin-bottom:0;\">",$eventRow["EventTime"],"</p>
                                                <p style = \"margin-bottom:0;\">",$venueRow["VenueName"],"</p> 
                                                <a href=\"/ticketinfo\" class=\"svelte-1vyamws\" data-svelte-h=\"svelte-1sijl07\">
                                                    <button class=\"svelte-1vyamws\">More Info</button>
                                                </a>
                                              </div>";
                                        $eventInfo -> free();
                                        $venueInfo -> free();
                                        
                                    }
                                    $result -> free();
                                }
                                else{
                                    echo "<p> No Tickets found :( </p>";
                                }
                                $conn->close();
                               
                            ?>

                            </div>
                       
                        </div> 
                    </main> 
                </html> 
		</div>
	</body>
</html>