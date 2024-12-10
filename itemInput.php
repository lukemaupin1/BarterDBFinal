<?php
//include auth_session.php file on all user panel pages
include("authSession.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarterDB</title>
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
		
		items{display: table;}
		p{display: table-row}
		label{display: table-cell}
		input{display: table-cell}
		
		.item-list {
            margin-top: 30px;
        }
        .item-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .item-list th, .item-list td {
            padding: 10px;
            text-align: left;
            border: 1px solid black;
        }

        .addItem {
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
            <a href="itemInput.php" class="active">Add Item to Account</a><br>
            <a href="addPartner.php">Add Partner</a>
        </div>
            <?php
            require('database.php');

            // When form is submitted, insert values into the database.
            if (isset($_REQUEST['itemName'])) {
                // Sanitize the inputs to prevent SQL injection
                $itemName = stripslashes($_REQUEST['itemName']);
                $itemName = mysqli_real_escape_string($con, $itemName);
                
                $description = stripslashes($_REQUEST['description']);
                $description = mysqli_real_escape_string($con, $description);
                
                $value = stripslashes($_REQUEST['value']);
                $value = mysqli_real_escape_string($con, $value);
                
                $quantity = (int) $_REQUEST['quantity'];

                // Insert item into the database
                $query = "INSERT INTO `items` (itemName, description, value) 
                        VALUES ('$itemName', '$description', '$value')"; 
                $result = mysqli_query($con, $query);

                if ($result) {
                    // Get the last inserted itemID
                    $itemid = mysqli_insert_id($con);

                    // Insert into the 'owns' table to associate the user with the item
                    $userid = $_SESSION['userid']; // Get the user ID from session
                    $queryOwns = "INSERT INTO `owns` (userid, itemid, quantity) 
                                VALUES ('$userid', '$itemid', '$quantity')";
                    $resultOwns = mysqli_query($con, $queryOwns);

                    if ($resultOwns) {
                        echo "<div class='form'>
                            <h3>Item added successfully to your account</h3><br/>
                            <p class='link'>Click to <a href='itemInput.php'>add another item</a></p>
                            </div>";
                    } else {
                        echo "<div class='form'>
                            <h3>There was an error associating the item with your account. Please try again.</h3><br/>
                            <p class='link'>Click to <a href='itemInput.php'>try again</a></p>
                            </div>";
                    }
                    } else {
                        echo "<div class='form'>
                            <h3>There was an error adding the item. Please try again.</h3><br/>
                            <p class='link'>Click to <a href='itemInput.php'>try again</a></p>
                            </div>";
                    }
                } else {
        // Form not submitted, display the input form
        ?>
        <div class="content">
            <div class="BarterDB"> <p> Input Items </p> </div>
            
            <form class="items" method="post" action="itemInput.php">
            <p>
                <label for="itemName">Item Name:</label>
                <input type="text" id="itemName" name="itemName" required><br><br>
            </p>
            <p>
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea><br><br>
            </p>
            <p>
                <label for="value">Value:</label>
                <input type="number" id="value" name="value" step="0.01" min="0" required><br><br>
            </p>
            <p>
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" min="1" required><br><br>
            </p>
                <input class='addItem' type="submit" value="Add Item">
            </form>
        </div>

        <?php
        // Get user ID and partner ID from session
        $userid = $_SESSION['userid'];

        // Get the user's partner ID from the database
        $queryPartner = "SELECT partnerid FROM users WHERE userid = '$userid'";
        $resultPartner = mysqli_query($con, $queryPartner);
        $partnerid = mysqli_fetch_assoc($resultPartner)['partnerid'];

        // Fetch the user's items
        $queryUserItems = "SELECT i.itemName, i.description, o.quantity FROM items i 
                            JOIN owns o ON i.itemID = o.itemID 
                            WHERE o.userid = '$userid'";
        $resultUserItems = mysqli_query($con, $queryUserItems);

        // Fetch the partner's items if they have one
        if ($partnerid > 0) {
            $queryPartnerItems = "SELECT i.itemName, i.description, o.quantity FROM items i 
                                  JOIN owns o ON i.itemID = o.itemID 
                                  WHERE o.userid = '$partnerid'";
            $resultPartnerItems = mysqli_query($con, $queryPartnerItems);
        }
    ?>

    <?php
            // Get user ID and partner ID from session
            $userid = $_SESSION['userid'];

            // Get the user's partner ID from the database
            $queryPartner = "SELECT partnerid FROM users WHERE userid = '$userid'";
            $resultPartner = mysqli_query($con, $queryPartner);
            $partnerid = mysqli_fetch_assoc($resultPartner)['partnerid'];

            // Fetch the user's items
            $queryUserItems = "SELECT i.itemName, i.description, o.quantity FROM items i 
                                JOIN owns o ON i.itemID = o.itemID 
                                WHERE o.userid = '$userid'";
            $resultUserItems = mysqli_query($con, $queryUserItems);

            // Fetch the partner's items if they have one
            if ($partnerid > 0) {
                $queryPartnerItems = "SELECT i.itemName, i.description, o.quantity FROM items i 
                                    JOIN owns o ON i.itemID = o.itemID 
                                    WHERE o.userid = '$partnerid'";
                $resultPartnerItems = mysqli_query($con, $queryPartnerItems);
            }
        ?>
        
        <!-- Current User's Items -->
        <div class="content">
            <div class="item-list">
                <h3>Your Items</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Description</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($resultUserItems) > 0) {
                            while ($row = mysqli_fetch_assoc($resultUserItems)) {
                                echo "<tr><td>{$row['itemName']}</td><td>{$row['description']}</td><td>{$row['quantity']}</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>You don't have any items yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Partner's Items -->
        <?php if ($partnerid > 0): ?>
            <div class="container">
                <div class="content">
                    <div class="item-list">
                        <h3>Your Partner's Items</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($resultPartnerItems) > 0) {
                                    while ($row = mysqli_fetch_assoc($resultPartnerItems)) {
                                        echo "<tr><td>{$row['itemName']}</td><td>{$row['description']}</td><td>{$row['quantity']}</td></tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='3'>Your partner doesn't have any items yet.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <?php
            }
        ?>
    </div>
    
    <script src="main.js"></script>

</body>

</html>