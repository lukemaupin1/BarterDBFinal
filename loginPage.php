<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarterDB</title>
    <link rel="stylesheet" href="registerStyle.css">
    <link rel="stylesheet" href="homeStyle.css">
</head>
<body>
    <nav>
        <ul class="sidebar">
            <li onclick=hideSidebar()><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="26px" viewBox="0 -960 960 960" width="26px" fill="#"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg></a></li>
            <li><a href="index.html">Home</a></li>
            <li><a href="aboutUs.html">About Us</a></li>
            <li><a href="contactUs">Contact Us</a></li>
            <li><a href="registerPage">Sign Up / Login</a></li>
        </ul>
        <ul>
            <li><a href="index.html">BarterDB</a></li>
            <li class="hideOnMobile"><a href="index.html">Home</a></li>
            <li class="hideOnMobile"><a href="aboutUs.html">About Us</a></li>
            <li class="hideOnMobile"><a href="contactUs.php">Contact Us</a></li>
            <li class="hideOnMobile"><a href="registerPage.php" class="active">Sign Up / Login</a></li>
            <li class="menuButton" onclick=showSidebar()><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="26px" viewBox="0 -960 960 960" width="26px"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg></a></li>
        </ul>
    </nav>

    <main>
    <?php
        require('database.php');
        session_start();

        if (isset($_POST['username'])) {
            $username = stripslashes($_REQUEST['username']);    // removes backslashes
            $username = mysqli_real_escape_string($con, $username);
            $password = stripslashes($_REQUEST['password']);
            $password = mysqli_real_escape_string($con, $password);
            // Check user is exist in the database
            // Changed '" . md5($password) "' to '$password' in query so that password would be displayed correctly in menu table
            $query    = "SELECT * FROM `users` WHERE username='$username'
                         AND password='$password'";
            $result = mysqli_query($con, $query) or die(mysql_error());
            $rows = mysqli_num_rows($result);
            if ($rows == 1) {
				$users = mysqli_fetch_assoc($result);
				
                $_SESSION['username'] = $username;
				$_SESSION['userid'] = $users['userid'];
                // Redirect to user dashboard page
                header("Location: dashboard.php");
            } else {
                echo "<div class='form'>
                      <h3>Incorrect Username/password.</h3><br/>
                      <p class='link'>Click here to <a href='loginPage.php'>Login</a> again.</p>
                      </div>";
            }
        } else {
    ?>
        <form class="form" method="post" name="login">
            <h1 class="loginTitle">Login</h1>
            <input type="text" class="loginInput" name="username" placeholder="Username" autofocus="true"/>
            <input type="password" class="loginInput" name="password" placeholder="Password"/>
            <input type="submit" value="Login" name="submit" class="loginButton"/>
            <p class="link"><a href="registerPage.php">New Registration</a></p>
        </form>
    <?php
        }
    ?>
    </main>
    <script src="main.js">
    </script>
</body>
</html>