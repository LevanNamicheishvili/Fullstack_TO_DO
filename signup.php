<?php
include "db_conn.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['uname']) && isset($_POST['password'])) {
        // Function to validate user input
        function validate($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $uname = validate($_POST['uname']);
        $pass = validate($_POST['password']);

        if (empty($uname) || empty($pass)) {
            header("Location: signup.php?signup_error=All fields are required for signup");
            exit();
        } else {
            // Check if the username is already taken
            $sql = "SELECT * FROM users WHERE user_name='$uname'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                header("Location: signup.php?signup_error=Username already taken. Please choose a different one.");
                exit();
            } else {
                // Insert the new user into the database
                $sql = "INSERT INTO users (user_name, password) VALUES ('$uname', '$pass')";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    header("Location: index.php?signup_success=Signup successful. You can now login.");
                    exit();
                } else {
                    header("Location: signup.php?signup_error=Signup failed. Please try again later.");
                    exit();
                }
            }
        }
    } else {
        header("Location: signup.php");
        exit();
    }
} else {
    header("Location: signup.php");
    exit();
}
?>
