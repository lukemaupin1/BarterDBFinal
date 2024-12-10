<?php
//include auth_session.php file on all user panel pages
include("authSession.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Partnership Settings</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="HomeStyle.css">
    <link rel="stylesheet" href="registerStyle.css">
    <link rel="stylesheet" href="homeStyle.css">
    <link rel="stylesheet" href="accountSettingsStyle.css">
    
    <style>
        .items {
            margin: 50px auto;
            width: 500px;
            padding: 30px 25px;
            background: var(--ash-gray);
        }
		.BarterDB {
			font-size: 48px;
			font-weight: bold;
			text-align: center;
			padding-top: 10px;
		}

        .addPartner {
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
            <li class="hideOnMobile"><a href="createTrade.php">Create Trade</a></li>
            <li class="hideOnMobile"><a href="contactUs2.php">Contact Us</a></li>
            <li class="hideOnMobile"><a href="accountSettings.php" class="active">Account Settings</a></li>
            <li class="menuButton" onclick=showSidebar()><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="26px" viewBox="0 -960 960 960" width="26px"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg></a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="sidebar2">
            <a href="itemInput.php">Add Item to Account</a><br>
            <a href="addPartner.php" class="active">Add Partner</a>
        </div>

        <div class="content">
            <?php
                require('database.php');

                $userid = $_SESSION['userid']; // Get current user's ID

                // Fetch partner information for the current user
                $query = "SELECT partnerid FROM users WHERE userid = '$userid'";
                $result = mysqli_query($con, $query);
                $userRow = mysqli_fetch_assoc($result);
                $partnerid = $userRow['partnerid'];

                // Determine partnership status message
                if ($partnerid == 0) {
                    $partnershipStatus = "No partner added.";
                } else {
                    // Check if the partner has reciprocated
                    $partnerQuery = "SELECT partnerid, username FROM users WHERE userid = '$partnerid'";
                    $partnerResult = mysqli_query($con, $partnerQuery);
                    if ($partnerResult && mysqli_num_rows($partnerResult) == 1) {
                        $partnerRow = mysqli_fetch_assoc($partnerResult);
                        if ($partnerRow['partnerid'] == $userid) {
                            $partnershipStatus = "Partnership accepted with " . htmlspecialchars($partnerRow['username']) . ".";
                        } else {
                            $partnershipStatus = "Partnership pending.";
                        }
                    } else {
                        $partnershipStatus = "No partner found with that ID.";
                    }
                }

                if (isset($_POST['partner'])) { // If form submitted
                    $partnerUsername = stripslashes($_POST['partner']); // Sanitize input
                    $partnerUsername = mysqli_real_escape_string($con, $partnerUsername); 
                    
                    // Check if the username exists and is not the current user
                    $query = "SELECT userid FROM users WHERE username = '$partnerUsername' AND userid != '$userid'";
                    $result = mysqli_query($con, $query); // Check database & store result
                    
                    if (mysqli_num_rows($result) == 1) { // If the partner's username is found
                        $partner = mysqli_fetch_assoc($result); // Gets row associated with partner
                        $partnerid = $partner['userid']; // Stores partner's id

                        // Update partner ID for the current user
                        $update = "UPDATE users SET partnerid = '$partnerid' WHERE userid = '$userid'"; // Update partnerid column in users
                        
                        if (mysqli_query($con, $update)) { // If partner set successfully
                            echo "<div class='form'>
                                    <h3>Partner set successfully!</h3><br/>
                                    <p class='link'>Click here to <a href='dashboard.php'>return to your dashboard</a></p>
                                </div>";
                        } else {
                            echo "<div class='form'>
                                    <h3>Failed to set partner. Please try again.</h3><br/>
                                    <p class='link'><a href='addPartner.php'>Try again</a></p>
                                </div>";
                        }
                    } else { // If partner's username isn't found or user tried to set themselves
                        echo "<div class='form'>
                                <h3>Username not found or invalid. Please try again.</h3><br/>
                                <p class='link'><a href='addPartner.php'>Try again</a></p>
                            </div>";
                    }
                } else { // If form isn't submitted yet, display the form
            ?>      

            <div class="BarterDB"> <p>Set Partner</p> </div>

            <div class="items">
                <form method="post" action="addPartner.php">
                    <p>
                        <label for="partner">Enter Partner's Username:</label>
                        <input type="text" id="partner" name="partner" required><br><br>
                    </p>
                    <input class="addPartner" type="submit" value="Add Partner">
                </form>
                <p><?php echo $partnershipStatus; ?></p>
            </div>

            <?php
            }
            ?>
        </div>
    </div>
</body>
</html>
