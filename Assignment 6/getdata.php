 <html lang="en">
	<head>
		<meta charset="utf-8" />
		<link rel="icon" href="./favicon.png" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link href="./_app/immutable/assets/0.f5cba8e3.css" rel="stylesheet">
		<link href="./_app/immutable/assets/5.0d42ccc9.css" rel="stylesheet">
        <link href="./_app/immutable/assets/0.f5cba8e3.css" rel="stylesheet">
		<link href="./_app/immutable/assets/5.0d42ccc9.css" rel="stylesheet">
		<link rel="modulepreload" href="./_app/immutable/entry/start.36e3d960.js">
		<link rel="modulepreload" href="./_app/immutable/chunks/scheduler.63274e7e.js">
		<link rel="modulepreload" href="./_app/immutable/chunks/singletons.d9622086.js">
		<link rel="modulepreload" href="./_app/immutable/entry/app.c7397ffc.js">
		<link rel="modulepreload" href="./_app/immutable/chunks/index.fbaf6ba9.js">
		<link rel="modulepreload" href="./_app/immutable/nodes/0.ed9b1b4f.js">
		<link rel="modulepreload" href="./_app/immutable/nodes/5.00614feb.js"><title>SeatSeeker</title><!-- HEAD_svelte-1is2hkg_START -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css"><!-- HEAD_svelte-1is2hkg_END -->
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
                    <main class="svelte-1l6c635" data-svelte-h="svelte-dkq008">
                        <div id="form" class="svelte-1l6c635">
                            <?php
                                $userN = $_POST['User'];
                                $pwd = $_POST['Password'];
                                $conn = new mysqli("127.0.0.1","root","","forassignment8");
                                if ($conn->connect_error) { 
                                    die("Connection failed: " . $conn->connect_error); 
                                }
                                $sql = "SELECT  Email, DateOfBirth
                                        FROM    USER
                                        WHERE   UserName = '$userN' AND PassW = '$pwd';";
                                $result = $conn->query($sql);
                                if($result->num_rows > 0){
                                    while($row = $result->fetch_assoc()){
                                        echo "<p style = \"margin-bottom:0;\">Your Email is ", $row["Email"] ,"</p>
                                              <p>Your Date of Birth is ", $row["DateOfBirth"],"</p>";
                                    }
                                    $result->free();
                                }
                                else{
                                    echo "<p> Invalid Username or Password </p>
                                          <a href =\"./login.html\">Click here to go back</a>";
                                }
                                $conn->close();
                            ?>
                        </div>
                    </main> 
                </html> 
		</div>
	</body>
</html>