<?php
	include("authSession.php");
	require("database.php");
	
	// Get trade board info from database so we can display it on dashboard
	$tradeQuery = "SELECT u.username, t.itemWant, t.quantity, t.itemOffer, t.forPartner, t.date_posted, t.tradeStatus
				   FROM trade t
				   JOIN users u ON t.userid = u.userid
				   ORDER BY t.date_posted DESC";
	
	$tradeResult = mysqli_query($con, $tradeQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarterDB</title>
    <link rel="stylesheet" href="registerStyle.css">
    <link rel="stylesheet" href="homeStyle.css">
	<link rel="stylesheet" href="dashboardStyle.css">
</head>

<body>
    <nav>
        <ul class="sidebar">
            <li onclick=hideSidebar()><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="26px" viewBox="0 -960 960 960" width="26px" fill="#"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg></a></li>
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="createTrade.php">Create Trade</a></li>
            <li><a href="contactUs2.php">Contact Us</a></li>
            <li><a href="accountSettings.php">Account Settings</a></li>
        </ul>
        <ul>
            <li><a href="#">BarterDB</a></li>
            <li class="hideOnMobile"><a href="dashboard.php" class="active">Dashboard</a></li>
            <li class="hideOnMobile"><a href="createTrade.php">Create Trade</a></li>
            <li class="hideOnMobile"><a href="contactUs2.php">Contact Us</a></li>
            <li class="hideOnMobile"><a href="accountSettings.php">Account Settings</a></li>
            <li class="menuButton" onclick=showSidebar()><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="26px" viewBox="0 -960 960 960" width="26px"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg></a></li>
        </ul>
    </nav>

    <main>
		<!-- Show welcome message to user -->
        <div class="form">
            <p class="welcomeText">Welcome, <?php echo $_SESSION['username']; ?>!</p><br>
            <p class="welcomeText">You are now on the Dashboard page.</p>
            <p class="welcomeText">Click the Create Trade tab to submit a new trade offer or browse the currently active trades listed below.</p><br>
            <p class="welcomeText"><a href="logoutPage.php">Logout</a></p>
        </div>
        
        <!-- Show all of the trades found in the trade table -->
		<table class="tradeBoard">
			<tr>
				<th>Username (Temp):</th>
				<th>Looking For:</th>
				<th>Quantity:</th>
				<th>Offering:</th>
				<th>Date Posted:</th>
				<th>Trade Status:</th>
			</tr>
			
			<?php
				if(mysqli_num_rows($tradeResult) > 0) // If we find that there are active trades
				{
					while($tradeRow = mysqli_fetch_assoc($tradeResult))
					{
						echo "<tr>
								<th>{$tradeRow['username']}</th>
								<th>{$tradeRow['itemWant']}</th>
								<th>{$tradeRow['quantity']}</th>
								<th>{$tradeRow['itemOffer']}</th>
								<th>{$tradeRow['date_posted']}</th>
								<th>{$tradeRow['tradeStatus']}</th>
							</tr>";
					}
				} else {
					echo "<tr><td colspan='6'>There are currently no active trades.</td></tr>";
				}
			?>
		</table>
		
        <a href="menuPage.php" class="menuBtn"> Menu </a>
		
    </main>

    <script src="main.js"></script>

</body>

</html>