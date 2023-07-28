<?php
session_start();

// If the user is already logged in, redirect them to the home page
if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
    header("Location: home.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>LOGIN</title>
    <link rel="stylesheet" type="text/css" href="./styles/style.css?v=<?php echo time(); ?>" >
</head>
<body>
    <div class="container">
        <div class="left_side">
        <div class="wrapper">
    <div class="typing-demo">
      Just Do It ...
    </div>
</div>
        </div>
        <div class="right_side"> 
            <form id="login-form" action="login.php" method="post">
                <div class= "form_flex">
                    <h2>LOGIN</h2>
                    <?php if (isset($_GET['error'])) { ?>
                        <p class="error"><?php echo $_GET['error']; ?></p>
                    <?php } ?>
                    <label>User Name</label>
                    <input type="text" name="uname" placeholder="User Name">

                    <label>Password</label>
                    <input type="password" name="password" placeholder="Password">

                    <button type="submit">Login</button>
                    <span class="choos_style">or</span>
                    <!-- Signup Button -->
                    <button type="button" id="signup-button" class="signup-btn" >Sign Up</button> 
                </div>
            </form>
        </div>
    </div>
</body>
<script src="./js/index.js"></script>
</html>
