<!DOCTYPE html>
<html>
<head>
    <title>LOGIN</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <form id="login-form" action="login.php" method="post">
        <h2>LOGIN</h2>
        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>
        <label>User Name</label>
        <input type="text" name="uname" placeholder="User Name">

        <label>Password</label>
        <input type="password" name="password" placeholder="Password">

        <button type="submit">Login</button>
        
        <!-- Signup Button -->
        <button type="button" id="signup-button" class="signup-btn" onclick="goToSignup()">Sign Up</button> 
    </form>

    <script>
        // JavaScript function to navigate to signup page
        function goToSignup() {
            window.location.href = "signupform.php";
        }
    </script>
</body>
</html>
