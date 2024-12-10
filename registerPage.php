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
            <li><a href="#">Home</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact Us</a></li>
            <li><a href="#">Sign Up / Login</a></li>
        </ul>
        <ul>
            <li><a href="#">BarterDB</a></li>
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
        if (isset($_REQUEST['username'])) 
        {
            // removes backslashes
            $username = stripslashes($_REQUEST['username']);
            //escapes special characters in a string
            $username = mysqli_real_escape_string($con, $username);
            
            $first_name = stripslashes($_REQUEST['first_name']);
            $first_name = mysqli_real_escape_string($con, $first_name);
            
            $last_name = stripslashes($_REQUEST['last_name']);
            $last_name = mysqli_real_escape_string($con, $last_name);
            
            $email    = stripslashes($_REQUEST['email']);
            $email    = mysqli_real_escape_string($con, $email);
            
            $password = stripslashes($_REQUEST['password']);
            $password = mysqli_real_escape_string($con, $password);
            
            $create_datetime = date("Y-m-d H:i:s");
            // Changed '" . md5($password) "' to '$password' in query so that password would be displayed correctly in menu table
            $query    = "INSERT into `users` (username, first_name, last_name, password, email, create_datetime)
                         VALUES ('$username', '$first_name', '$last_name', '$password', '$email', '$create_datetime')"; 
            $result   = mysqli_query($con, $query);
            if ($result) {
                echo "<div class='form'>
                      <h3>You are registered successfully.</h3><br/>
                      <p class='link'>Click here to <a href='loginPage.php'>Login</a></p>
                      </div>";
            } else {
                echo "<div class='form'>
                      <h3>Required fields are missing.</h3><br/>
                      <p class='link'>Click here to <a href='registerPage.php'>registration</a> again.</p>
                      </div>";
            }
        } else {
    ?>
        <form class="form" action="" method="post">
            <h1 class="loginTitle">Registration</h1>
            <input type="text" class="loginInput" name="username" placeholder="Username" required />
            <input type="text" class="loginInput" name="email" placeholder="Email Adress" required />
            <input type="text" class="loginInput" name="first_name" placeholder="First Name">
            <input type="text" class="loginInput" name="last_name" placeholder="Last Name">
            <input type="password" class="loginInput" name="password" placeholder="Password" required />
            <input type="submit" name="submit" value="Register" class="loginButton">
            <p class="link"><a href="loginPage.php">Already have an account? Login.</a></p>
        </form>
    <?php
        }
    ?>
    </main>
    <script>
        function showSidebar()
        {
            const sidebar = document.querySelector('.sidebar')
            sidebar.style.display = 'flex';
        }

        function hideSidebar()
        {
            const sidebar = document.querySelector('.sidebar')
            sidebar.style.display = 'none';
        }
    </script>
</body>
</html>