<?php
session_start();
include "db_conn.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['uname'], $_POST['password'])) {
        $uname = htmlspecialchars(trim($_POST['uname']));
        $pass = htmlspecialchars(trim($_POST['password']));

        if (empty($uname) || empty($pass)) {
            redirectWithMessage("signup.php", "All fields are required for signup");
        }

        // Check if the username is already taken
        $sql = "SELECT * FROM users WHERE user_name='$uname'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            redirectWithMessage("signup.php", "Username already taken. Please choose a different one.");
        }

        // Insert the new user into the database
        $sql = "INSERT INTO users (user_name, password) VALUES ('$uname', '$pass')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            redirectWithMessage("index.php", "Signup successful. You can now login.", "signup_success");
        } else {
            redirectWithMessage("signup.php", "Signup failed. Please try again later.", "signup_error");
        }
    } else {
        header("Location: signup.php");
        exit();
    }
} else {
    header("Location: signup.php");
    exit();
}

function redirectWithMessage($location, $message, $param = "") {
    $url = $location;
    if (!empty($param)) {
        $url .= "?" . $param . "=" . urlencode($message);
    }
    header("Location: " . $url);
    exit();
}
?>
