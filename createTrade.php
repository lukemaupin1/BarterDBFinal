<?php
	include("authSession.php");
	require('database.php');

	$userid = $_SESSION['userid'];
				
	// Get partnerid of user's assigned partner
	$partneridQuery = "SELECT partnerid FROM users WHERE userid = '$userid'";
	$partnerResult = mysqli_query($con, $partneridQuery);
	$partnerRow = mysqli_fetch_assoc($partnerResult);
	
	$partnerid = $partnerRow ? ($partnerRow['partnerid'] != 0 ? $partnerRow['partnerid'] : null) : null; // Sets partnerid to partner's userid or sets it to null if the user has no partner
	
	// Get the items that the user and the partner own
	$itemQuery = "SELECT itemName, quantity 
				  FROM items 
				  JOIN owns ON items.itemid = owns.itemid 
				  WHERE owns.userid IN ('$userid'" . ($partnerid ? ", '$partnerid'" : "") . ")";
	$itemResult = mysqli_query($con, $itemQuery);
?>
<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarterDB</title>
	<link rel="stylesheet" href="registerStyle.css">
    <link rel="stylesheet" href="homeStyle.css">
	<link rel="stylesheet" href="createTrade.css">
	
	<style>
		.tradeSelect {
			font-size: 15px;
			border: 1px solid var(--carmine);
			padding: 10px;
			margin-top: 10px;
			margin-bottom: 10px;
			height: 45px; /* Adjusted height to match other inputs */
			width: calc(100% - 23px);
			background-color: var(--white); /* Matches input background */
			color: var(--black);
			appearance: none; /* Removes default select styling for a cleaner look */
		}

		.tradeSelect:focus {
			border-color: darkred;
			outline: none;
		}
		
		.tradeHeading {
			text-align: center;
			color: white;
			margin-top: 45px;
		}

		.postTrade {
			color: var(--carmine);
			background: #97b29e;
			border: 0;
			outline: 0;
			width: 100%;
			height: 50px;
			font-size: 16px;
			text-align: center;
			cursor: pointer;
		}
	</style>
</head>

<body>
    <nav>
        <ul class="sidebar">
            <li onclick=hideSidebar()><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="26px" viewBox="0 -960 960 960" width="26px" fill="#"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg></a></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="createTrade.php" class="active">Create Trade</a></li>
            <li><a href="contactUs2.php">Contact Us</a></li>
            <li><a href="accountSettings.php">Account Settings</a></li>
        </ul>
        <ul>
            <li><a href="#">BarterDB</a></li>
            <li class="hideOnMobile"><a href="dashboard.php">Dashboard</a></li>
            <li class="hideOnMobile"><a href="createTrade.php" class="active">Create Trade</a></li>
            <li class="hideOnMobile"><a href="contactUs2.php">Contact Us</a></li>
            <li class="hideOnMobile"><a href="accountSettings.php">Account Settings</a></li>
            <li class="menuButton" onclick=showSidebar()><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="26px" viewBox="0 -960 960 960" width="26px"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg></a></li>
        </ul>
    </nav>

    <main>
		<h1 class="tradeHeading"> Create Trade </h1>
		<form class='form' method="post" action="createTrade.php">
            <label for="itemWant">Item Wanted:</label>
            <input class='loginInput' type="text" id="itemWant" name="itemWant" required><br>

            <label for="quantity">Amount Needed:</label>
            <input class='loginInput' type="number" id="quantity" name="quantity" min="1" required><br>

            <label for="itemOffer">Item to Offer:</label>
            <select class='tradeSelect' type="text" id="itemOffer" name="itemOffer" required>
				<?php
                if (mysqli_num_rows($itemResult) > 0) {
                    while ($itemRow = mysqli_fetch_assoc($itemResult)) {
                        echo "<option value='{$itemRow['itemName']}'>{$itemRow['itemName']} - Quantity: {$itemRow['quantity']}</option>";
                    }
                } else {
                    echo "<option value='' disabled>No items available</option>";
                }
				?>
			</select><br>
            <label for="forPartner">For Partner?</label>
            <input type="checkbox" id="forPartner" name="forPartner"><br><br>

            <input class='postTrade' type="submit" name="submit_trade" value="Post Trade">
        </form>
		
		<?php	
			if($_SERVER["REQUEST_METHOD"] == "POST") // When user submits the form
			{
				$itemWant = mysqli_real_escape_string($con, $_POST['itemWant']);
				$quantity = intval($_POST['quantity']);
				
				$itemOffer = mysqli_real_escape_string($con, $_POST['itemOffer']);
				
				$forPartner = isset($_POST['forPartner']) ? 1 : 0;
				$date_posted = date("Y-m-d H:i:s");
				
				$query = "INSERT INTO trade (userid, itemWant, quantity, itemOffer, forPartner, date_posted) 
						  VALUES ('$userid', '$itemWant', '$quantity', '$itemOffer', '$forPartner', '$date_posted')";
				
				if (mysqli_query($con, $query)) // Add trade info to the trade table so it can be shown on the dashboard
				{
					echo "<p>Trade offer created successfully!</p>";
					
					$postid = mysqli_insert_id($con); // Get the postid of the trade offer that was just created
					
					// Checks to see if there are matching trades for this post
					checkTrades($con, $userid, $itemWant, $itemOffer, $partnerid, $forPartner);
				} else {
					echo "<p>Failed to create trade offer. Please try again.</p>";
				}
			}
			
			function checkTrades($con, $userid, $itemWant, $itemOffer, $partnerid, $forPartner)
			{
				// Check the database for any entries that have the item the user wants, and need the item the user has
				$checkQuery = "SELECT * FROM trade
							   WHERE itemWant = '$itemOffer' AND itemOffer = '$itemWant'
							   AND userid != '$userid' LIMIT 1";
				$checkResult = mysqli_query($con, $checkQuery);
				
				if(mysqli_num_rows($checkResult) > 0) // If a match for this trade is found
				{
					// If a match was found, now exchange the user's items
					$matchedTrade = mysqli_fetch_assoc($checkResult);
					$matchedUserId = $matchedTrade['userid']; // Get userid of the user we matched with
					
					$matchedUserPartnerQuery = "SELECT partnerid FROM users WHERE userid = '$matchedUserId'";
					$matchedUserPartnerResult = mysqli_query($con, $matchedUserPartnerQuery);
					
					$matchedUserPartnerRow = mysqli_fetch_assoc($matchedUserPartnerResult);
					$matchedUserPartnerId = $matchedUserPartnerRow['partnerid'] != 0 ? $matchedUserPartnerRow['partnerid'] : null;
					
					// Uses the forTrade bool in the trade table to see if the item is for the poster or their partner
					$useridReciever = $forPartner ? $partnerid : $userid;
					$matchedUseridReceiver = $matchedTrade['forPartner'] ? $matchedUserPartnerId : $matchedUserId;
					
					// Updates the owns table by overwritting the matchedUserId with the current user's userid
					$updateQuery1 = "UPDATE owns SET userid = '$useridReciever' 
									 WHERE userid IN ('$matchedUserId'" . ($matchedUserPartnerId ? ", '$matchedUserPartnerId'" : "") . ")
									 AND itemid = (SELECT itemid FROM items WHERE itemName = '$itemWant' LIMIT 1)"; 
									 
					mysqli_query($con, $updateQuery1); // Executes the first half of the trade
					
					// Updates the owns table by overwritting the current user's userid with the matchedUserId
					$updateQuery2 = "UPDATE owns SET userid = '$matchedUseridReceiver' 
									 WHERE userid IN ('$userid'" . ($partnerid ? ", '$partnerid'" : "") . ")
									 AND itemid = (SELECT itemid FROM items WHERE itemName = '$itemOffer' LIMIT 1)"; 
					
					mysqli_query($con, $updateQuery2); // Executes the second half of the trade
					
					// Gets the postid of the current user's post so it can be removed since the trade went through
					$getCurrentUserPostQuery = "SELECT postid FROM trade WHERE userid = '$userid' ORDER BY date_posted DESC LIMIT 1";
					$currentPostResult = mysqli_query($con, $getCurrentUserPostQuery);
					$currentPost = mysqli_fetch_assoc($currentPostResult);
					
					// Deletes the posts added to the table once the items have been exchanged
					$deleteQuery1 = "DELETE FROM trade WHERE postid = '{$matchedTrade['postid']}'";
					$deleteQuery2 = "DELETE FROM trade WHERE postid = '{$currentPost['postid']}'";
					
					mysqli_query($con, $deleteQuery1); // Deletes matched user's trade post
					mysqli_query($con, $deleteQuery2); // Deletes current user's trade post
					
					echo "<p>A matching trade has been found and completed!</p>";
				} else {
					echo "<p>No matching trade has been found, your offer has been added as an active trade on the dashboard.</p>";
				}
			}
		?>
		
    </main>

    <script src="main.js"></script>

</body>

</html>