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
        <button type="button" id="signup-button" class="signup-btn">Sign Up</button> 
    </form>

    <!-- Signup Form -->
    <form id="signup-form" action="signup.php" method="post" style="display: none;">
        <h2>SIGN UP</h2>
        <?php if (isset($_GET['signup_error'])) { ?>
            <p class="error"><?php echo $_GET['signup_error']; ?></p>
        <?php } ?>

        <label>User Name</label>
        <input type="text" name="uname" placeholder="Please enter your User Name">

        <label>Password</label>
        <input type="password" name="password" placeholder="Please enter yout Password">

        <button type="submit">Sign Up</button>
    </form>

    <script>
        // Get references to the login and signup forms
        const loginForm = document.getElementById('login-form');
        const signupForm = document.getElementById('signup-form');
        const signupButton = document.getElementById('signup-button');

        // Function to toggle visibility of forms
        function toggleForms() {
            loginForm.style.display = (loginForm.style.display === 'none') ? 'block' : 'none';
            signupForm.style.display = (signupForm.style.display === 'none') ? 'block' : 'none';
        }

        // Attach click event to the signup button
        signupButton.addEventListener('click', toggleForms);
    </script>
</body>
</html>
