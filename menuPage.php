<?php
//include auth_session.php file on all user panel pages
include("authSession.php");
require('database.php');

$query = "SELECT * FROM users"; // Get table info from db
$result = mysqli_query($con, $query); // Run query
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>User Menu</title>
    <link rel="stylesheet" href="style.css" />
	<link rel="stylesheet" href="HomeStyle.css">
</head>
<body>
	<div class="BarterDB"> <p> Database Entries </p> </div>
	
	<table border="1">
		<tr>
			<th> UserID </th>
			<th> UserName </th>
			<th> First Name </th>
			<th> Last Name </th>
			<th> Email </th>
			<th> Password </th>
			<th> Creation Time </th>
			<th> Action </th>
		</tr>
		
		<?php
			if($result)
			{
				while($row = mysqli_fetch_assoc($result))
				{
					echo "<tr>";
					echo "<td>" . $row['userid'] . "</td>";
					echo "<td>" . $row['username'] . "</td>";
					echo "<td>" . $row['first_name'] . "</td>";
					echo "<td>" . $row['last_name'] . "</td>";
					echo "<td>" . $row['email'] . "</td>";
					echo "<td>" . $row['password'] . "</td>";
					echo "<td>" . $row['create_datetime'] . "</td>";
					echo "<td> <form method='post' action=''>
									<input type='hidden' name='userid' value='" . $row['userid'] . "' />
									<input type='submit' name='delete' value='Delete' onclick='return confirm(\"Confirm user deletion:\");' />
								</form>
						</td>";
					echo "</tr>";
				}
			} else {
				echo "<tr> <td colspan='8'> Data couldn't be retrieved. </td> </tr>";
			}
		?>
	</table>
	
	<!-- Delete button function -->
	<?php
		// Handle deletion when delete button is clicked
		if (isset($_POST['delete'])) {
			$userDelete = mysqli_real_escape_string($con, $_POST['userid']);
			
			$deleteQuery = "DELETE FROM users WHERE userid='$userDelete'";
			
			if (mysqli_query($con, $deleteQuery)) 
			{
				echo "<script>alert('User deleted successfully.'); window.location.reload();</script>";
			} else {
				echo "<script>alert('Error deleting user: " . mysqli_error($con) . "');</script>";
			}
		
			header("Location: " .$_SERVER['PHP_SELF']);
			exit();
		}
	?>
	
	<a href="dashboard.php" class="menuBtn"> Back </a>
</body>
</html>